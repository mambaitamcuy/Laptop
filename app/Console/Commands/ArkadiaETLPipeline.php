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
            // 1. Kosongkan tabel di DWH
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 0;');
            DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->truncate();
            DB::connection('mysql_dwh')->table('dwh_dim_waktu')->truncate();

            // 2. Tarik Dimensi Produk dari OLTP ke DWH
            $oltpLaptops = DB::connection('mysql')->table('laptops')->select('id', 'nama')->get();
            foreach ($oltpLaptops as $laptop) {
                DB::connection('mysql_dwh')->table('dwh_dim_produk')->updateOrInsert(
                    ['id_dim_produk' => $laptop->id],
                    ['nama_produk' => $laptop->nama]
                );
            }

            // 3. Masukkan Dimensi Cabang ke DWH
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

            // 4. Tarik dan Proses Dimensi Waktu
            $oltpDates = DB::connection('mysql')->table('penjualan')
                ->whereNotNull('tanggal')
                ->distinct()
                ->pluck('tanggal');

            $dateToIdMap = [];
            $idWaktu = 1;

            $monthsIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $daysIndo = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
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
                $dateToIdMap[$formattedDate] = $idWaktu;
                $idWaktu++;
            }

            // 5. Tarik Tabel Fakta dari OLTP (Perhatikan kita menggunakan LEFT JOIN dan menarik dp.qty)
            $oltpDetails = DB::connection('mysql')->table('detail_penjualan as dp')
                ->leftJoin('laptops as l', 'dp.id_produk', '=', 'l.id')
                ->leftJoin('penjualan as p', 'dp.id_penjualan', '=', 'p.id_penjualan')
                ->leftJoin('users as u', 'p.id_user', '=', 'u.id_user')
                ->select([
                    'dp.id_produk',
                    'u.id_cabang',
                    'dp.id_penjualan',
                    'p.metode_pembayaran',
                    'dp.qty', // Pastikan kolom di database aslimu benar bernama qty (atau ganti jadi jumlah jika beda)
                    'l.harga',
                    'p.tanggal'
                ])->get();

            $bulkFacts = [];
            foreach ($oltpDetails as $row) {
                $hargaJual = $row->harga ?? 0;
                $qty = $row->qty ?? 0;
                $subtotal = $hargaJual * $qty;
                $hargaModal = $hargaJual * 0.80;
                $profit = ($hargaJual - $hargaModal) * $qty;

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

            // 6. Masukkan data ke DWH
            if (!empty($bulkFacts)) {
                foreach (array_chunk($bulkFacts, 200) as $chunk) {
                    DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->insert($chunk);
                }
            }

            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            
            return 0; // Sukses

        } catch (\Exception $e) {
            DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            $this->error('Gagal: ' . $e->getMessage());
            throw $e; // Lempar eror agar ditangkap oleh pop-up web
        }
    }
}