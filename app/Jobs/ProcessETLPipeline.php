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

    public $timeout = 600;

    /**
     * Menjalankan pemindahan, pembersihan, dan pengisian data ke gudang data (DWH)
     * menggantikan logic Stored Procedure JalankanPipaETL().
     */
    public function handle()
    {
        // Gunakan transaksi pada database target (DWH) untuk menjaga integritas data
        DB::connection('mysql_dwh')->beginTransaction();

        try {
            // 1. Matikan proteksi foreign key sementara di DWH
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 0;');

            // 2. Kosongkan Tabel Fakta dan Dimensi Waktu di DWH
            DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->truncate();
            DB::connection('mysql_dwh')->table('dwh_dim_waktu')->truncate();

            // 3. Sinkronisasi Dimensi Produk dari OLTP ke DWH (INSERT IGNORE)
            DB::connection('mysql_oltp')->table('laptops')
                ->select('id as id_dim_produk', 'nama as nama_produk')
                ->orderBy('id')
                ->chunk(500, function ($products) {
                    $insertProducts = [];
                    foreach ($products as $product) {
                        $insertProducts[] = [
                            'id_dim_produk' => $product->id_dim_produk,
                            'nama_produk'   => $product->nama_produk,
                        ];
                    }
                    // Menggunakan insertOrIgnore agar perilakunya sama seperti INSERT IGNORE di SQL asli
                    DB::connection('mysql_dwh')->table('dwh_dim_produk')->insertOrIgnore($insertProducts);
                });

            // Sinkronisasi Dimensi Cabang (Hardcoded sesuai procedure asli)
            $cabangData = [
                ['id_dim_cabang' => 1, 'nama_cabang' => 'Parigi'],
                ['id_dim_cabang' => 2, 'nama_cabang' => 'Palu'],
                ['id_dim_cabang' => 3, 'nama_cabang' => 'Donggala'],
            ];
            DB::connection('mysql_dwh')->table('dwh_dim_cabang')->insertOrIgnore($cabangData);

            // 4. Sinkronisasi Dimensi Waktu dari Tanggal Unik di OLTP
            $unikTanggal = DB::connection('mysql_oltp')->table('penjualan')
                ->whereNotNull('tanggal')
                ->distinct()
                ->selectRaw('DATE(tanggal) AS tanggal')
                ->orderBy('tanggal', 'asc')
                ->get();

            $hariIndo = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $insertWaktu = [];
            foreach ($unikTanggal as $index => $row) {
                $time = strtotime($row->tanggal);
                $dayName = date('l', $time);
                $monthNum = (int)date('n', $time);

                $insertWaktu[] = [
                    'id_waktu'   => $index + 1, // Berfungsi menggantikan ROW_NUMBER() OVER (...)
                    'tanggal'    => $row->tanggal,
                    'hari'       => $hariIndo[$dayName] ?? $dayName,
                    'bulan'      => $monthNum,
                    'nama_bulan' => $bulanIndo[$monthNum] ?? '',
                    'kuartal'    => ceil($monthNum / 3),
                    'tahun'      => (int)date('Y', $time),
                ];
            }

            if (!empty($insertWaktu)) {
                DB::connection('mysql_dwh')->table('dwh_dim_waktu')->insert($insertWaktu);
            }

            // 5. Masukkan Data ke Tabel Fakta Penjualan (Transformasi & Gabungan Data dari OLTP)
            // Mengambil map dimensi waktu dari DWH terlebih dahulu untuk mencocokkan id_waktu di memori
            $waktuMap = DB::connection('mysql_dwh')->table('dwh_dim_waktu')
                ->pluck('id_waktu', 'tanggal')
                ->toArray();

            DB::connection('mysql_oltp')->table('detail_penjualan as dp')
                ->join('laptops as l', 'dp.id_produk', '=', 'l.id')
                ->join('penjualan as p', 'dp.id_penjualan', '=', 'p.id_penjualan')
                ->leftJoin('users as u', 'p.id_user', '=', 'u.id_user')
                ->select([
                    'dp.id_produk',
                    'dp.id_penjualan',
                    'dp.qty',
                    'l.harga as harga_jual',
                    'p.metode_pembayaran',
                    'p.tanggal',
                    'u.id_cabang'
                ])
                ->orderBy('dp.id_penjualan')
                ->chunk(1000, function ($rows) use ($waktuMap) {
                    $insertFactPenjualan = [];

                    foreach ($rows as $row) {
                        $tglKey = date('Y-m-d', strtotime($row->tanggal));
                        // COALESCE(w.id_waktu, 1)
                        $idWaktu = $waktuMap[$tglKey] ?? 1;

                        $hargaJual = (float)$row->harga_jual;
                        $qty = (int)$row->qty;

                        // Hitung kalkulasi bisnis sesuai rumus asli procedure kamu
                        $hargaModal = $hargaJual * 0.80;
                        $subtotal = $hargaJual * $qty;
                        $profit = ($hargaJual - $hargaModal) * $qty;

                        $insertFactPenjualan[] = [
                            'id_waktu'          => $idWaktu,
                            'id_dim_produk'     => $row->id_produk,
                            'id_dim_cabang'     => $row->id_cabang ?? 1, // COALESCE(u.id_cabang, 1)
                            'id_penjualan'      => $row->id_penjualan,
                            'metode_pembayaran' => $row->metode_pembayaran,
                            'qty'               => $qty,
                            'harga_jual'        => $hargaJual,
                            'harga_modal'       => $hargaModal,
                            'subtotal'          => $subtotal,
                            'profit'            => $profit,
                            'created_at'        => now()
                        ];
                    }

                    if (!empty($insertFactPenjualan)) {
                        DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->insert($insertFactPenjualan);
                    }
                });

            // 6. Nyalakan kembali proteksi foreign key di DWH
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');

            // Commit transaksi jika seluruh tahapan sukses berjalan
            DB::connection('mysql_dwh')->commit();

        } catch (Exception $e) {
            // Batalkan semua perubahan jika ada kegagalan di tengah jalan agar data tidak kotor
            DB::connection('mysql_dwh')->rollBack();
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            throw $e;
        }
    }
}
