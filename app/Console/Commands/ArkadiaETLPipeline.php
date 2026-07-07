<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArkadiaETLPipeline extends Command
{
    protected $signature = 'arkadia:etl';
    protected $description = 'Menjalankan ETL Multi-Host dari Server OLTP ke Server DWH';

    public function handle()
    {
        $this->info('--- Memulai Pipa ETL Multi-Host ArkadiaLP ---');

        try {
            // =========================================================
            // LAKUKAN PROSES DI DATABASE DWH (MATIKAN PROTEKSI & KOSONGKAN)
            // =========================================================
            $this->comment('1. Membersihkan tabel fakta di Gudang Data (DWH)...');
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 0;');
            DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->truncate();
            DB::connection('mysql_dwh')->table('dwh_dim_waktu')->truncate();

            // =========================================================
            // EXTRACT & LOAD: SINKRONISASI DIMENSI PRODUK & CABANG
            // =========================================================
            $this->comment('2. Menyinkronkan Dimensi Produk dari Host OLTP...');
            $oltpLaptops = DB::connection('mysql')->table('laptops')->select('id', 'nama')->get();
            foreach ($oltpLaptops as $laptop) {
                DB::connection('mysql_dwh')->table('dwh_dim_produk')->updateOrInsert(
                    ['id_dim_produk' => $laptop->id],
                    ['nama_produk' => $laptop->nama]
                );
            }

            $this->comment('3. Memastikan Dimensi Cabang Terisi...');
            $cabangs = [
                ['id_dim_cabang' => 1, 'nama_cabang' => 'Parigi'],
                ['id_dim_cabang' => 2, 'nama_cabang' => 'Palu'],
                ['id_dim_cabang' => 3, 'nama_cabang' => 'Donggala'],
            ];
            foreach ($cabangs as $cabang) {
                DB::connection('mysql_dwh')->table('dwh_dim_cabang')->updateOrInsert(
                    ['id_dim_cabang' => $cabang['id_dim_cabang']],
                    ['nama_cabang' => $cabang['nama_cabang']]
                );
            }

            // =========================================================
            // EXTRACT & TRANSFORM: SINKRONISASI DIMENSI WAKTU
            // =========================================================
            $this->comment('4. Mentransformasi dan Mengisi Dimensi Waktu...');
            $oltpDates = DB::connection('mysql')->table('penjualan')
                ->whereNotNull('tanggal')
                ->distinct()
                ->pluck('tanggal');

            $dateToIdMap = [];
            $idWaktu = 1;

            $daysIndo = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $monthsIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            foreach ($oltpDates as $dateStr) {
                $carbonDate = Carbon::parse($dateStr);
                $formattedDate = $carbonDate->format('Y-m-d');

                DB::connection('mysql_dwh')->table('dwh_dim_waktu')->insert([
                    'id_waktu' => $idWaktu,
                    'tanggal' => $formattedDate,
                    'hari' => $daysIndo[$carbonDate->format('l')] ?? $carbonDate->format('l'),
                    'bulan' => $carbonDate->month,
                    'nama_bulan' => $monthsIndo[$carbonDate->month] ?? $carbonDate->format('F'),
                    'kuartal' => ceil($carbonDate->month / 3),
                    'tahun' => $carbonDate->year,
                ]);

                // Simpan map tanggal ke ID untuk mempermudah pengisian tabel fakta nanti
                $dateToIdMap[$formattedDate] = $idWaktu;
                $idWaktu++;
            }

            // =========================================================
            // EXTRACT, TRANSFORM, LOAD (ETL): TABEL FAKTA PENJUALAN
            // =========================================================
            $this->comment('5. Menarik data transaksi dari Host OLTP dan memproses data fakta...');
            
            // Ambil data murni dari server OLTP tanpa menyenggol server DWH
            $oltpDetails = DB::connection('mysql')->table('detail_penjualan as dp')
                ->leftJoin('laptops as l', 'dp.id_produk', '=', 'l.id')
                ->leftJoin('penjualan as p', 'dp.id_penjualan', '=', 'p.id_penjualan')
                ->leftJoin('users as u', 'p.id_user', '=', 'u.id_user')
                ->select([
                    'dp.id_produk',
                    'u.id_cabang',
                    'dp.id_penjualan',
                    'p.metode_pembayaran',
                    'dp.qty', // Menggunakan kolom dp.qty sesuai database asli kamu
                    'l.harga',
                    'p.tanggal'
                ])->get();

            $bulkFacts = [];
            foreach ($oltpDetails as $row) {
                $hargaJual = $row->harga ?? 0;
                $qty = $row->qty ?? 0;
                $subtotal = $hargaJual * $qty;
                $hargaModal = $hargaJual * 0.80; // Margin Profit Modal 80%
                $profit = ($hargaJual - $hargaModal) * $qty; // Profit Bersih 20%

                $dateKey = $row->tanggal ? Carbon::parse($row->tanggal)->format('Y-m-d') : null;
                $matchedIdWaktu = $dateToIdMap[$dateKey] ?? 1;

                $bulkFacts[] = [
                    'id_waktu' => $matchedIdWaktu,
                    'id_dim_produk' => $row->id_produk ?? 0,
                    'id_dim_cabang' => $row->id_cabang ?? 1,
                    'id_penjualan' => $row->id_penjualan,
                    'metode_pembayaran' => $row->metode_pembayaran ?? 'Cash',
                    'qty' => $qty,
                    'harga_jual' => $hargaJual,
                    'harga_modal' => $hargaModal,
                    'subtotal' => $subtotal,
                    'profit' => $profit,
                    'created_at' => now(),
                ];
            }

            // Kirim data secara massal (Bulk Insert) ke server DWH dengan chunking ramah memori
            if (!empty($bulkFacts)) {
                $this->comment('6. Memasukkan data bersih ke dalam Host DWH...');
                foreach (array_chunk($bulkFacts, 200) as $chunk) {
                    DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->insert($chunk);
                }
            }

            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            $this->info('--- ETL BERHASIL! Data Lintas Host Sinkron 100% Sempurna ---');

        } catch (\Exception $e) {
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            $this->error('Proses ETL Gagal: ' . $e->getMessage());
        }
    }
}