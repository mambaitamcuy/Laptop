<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Exception;

class ProcessETLPipeline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Set timeout job lebih panjang karena proses ETL membutuhkan waktu lebih lama.
     */
    public $timeout = 600;

    /**
     * Menjalankan pemindahan, pembersihan, dan pengisian data ke gudang data (DWH).
     */
    public function handle()
    {
        // Gunakan Database Transaction di sisi DWH agar data konsisten jika terjadi error di tengah jalan
        DB::connection('mysql_dwh')->beginTransaction();

        try {
            // 1. Matikan pengecekan Foreign Key agar proses truncate lancar
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 0;');

            // Kosongkan tabel fakta
            DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->truncate();
            DB::connection('mysql_dwh')->table('dwh_fact_stok')->truncate();

            // 2. PROSES ETL PENJUALAN (Menggunakan Chunk & Bulk Insert)
            // Menggunakan chunk agar ram tidak bengkak (mengambil 1000 data per baris bergantian)
            DB::connection('mysql_oltp')->table('penjualan')
                ->orderBy('id_penjualan') // Wajib ada orderBy jika menggunakan chunk
                ->chunk(1000, function ($rows) {
                    $bulkInsertPenjualan = [];

                    foreach ($rows as $row) {
                        $tanggalRaw = $row->created_at ?? ($row->tanggal ?? now());
                        $idWaktu = date('Ymd', strtotime($tanggalRaw));

                        $bulkInsertPenjualan[] = [
                            'id_penjualan'  => $row->id_penjualan ?? 0,
                            'id_dim_produk' => 1, // Default sementara
                            'id_dim_cabang' => $row->id_cabang ?? 0,
                            'id_waktu'      => $idWaktu,
                            'subtotal'      => $row->total ?? 0,
                            'profit'        => ($row->total * 0.1) ?? 0,
                            'qty'           => 1,
                            'harga_jual'    => $row->total ?? 0,
                            'harga_modal'   => ($row->total * 0.9) ?? 0
                        ];
                    }

                    // Hanya lakukan 1x query insert untuk 1000 data (SANGAT CEPAT)
                    if (!empty($bulkInsertPenjualan)) {
                        DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->insert($bulkInsertPenjualan);
                    }
                });

            // 3. PROSES ETL STOK (Menggunakan Chunk & Bulk Insert)
            DB::connection('mysql_oltp')->table('stok_cabang')
                ->orderBy('id_produk') // Sesuaikan primary key atau field uniknya untuk orderBy
                ->chunk(1000, function ($rows) {
                    $bulkInsertStok = [];

                    foreach ($rows as $row) {
                        $bulkInsertStok[] = [
                            'id_dim_cabang' => $row->id_cabang ?? 0,
                            'id_dim_produk' => $row->id_produk ?? 0,
                            'jumlah_stok'   => $row->jumlah ?? 0,
                            'stok_minimum'  => 5
                        ];
                    }

                    if (!empty($bulkInsertStok)) {
                        DB::connection('mysql_dwh')->table('dwh_fact_stok')->insert($bulkInsertStok);
                    }
                });

            // 4. Nyalakan kembali pengecekan Foreign Key
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');

            // Jika semua lancar, commit data ke database
            DB::connection('mysql_dwh')->commit();

        } catch (Exception $e) {
            // Jika ada error di tengah jalan, batalkan semua perubahan agar DWH tidak corrupt
            DB::connection('mysql_dwh')->rollBack();

            // Nyalakan kembali FK check untuk keamanan database ke depan
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');

            // Lemparkan error agar tercatat di failed_jobs Laravel
            throw $e;
        }
    }
}
