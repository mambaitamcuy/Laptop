<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArkadiaETLPipeline extends Command
{
    protected $signature = 'arkadia:etl';
    protected $description = 'Menjalankan ETL Pipeline Arkadia (Sinkronisasi OLTP ke DWH)';

    public function handle()
    {
        $this->info('--- Memulai ETL Pipeline Arkadia ---');
        
        // Ambil nama database dari env agar penulisan query lebih bersih
        $dwh = env('DB_DATABASE_DWH') . '.';
        $oltp = env('DB_DATABASE_OLTP') . '.';
        
        try {
            // 1. Amankan data dimensi terlebih dahulu secara bertahap
            $this->comment('Sinkronisasi data dimensi (Waktu, Cabang, Produk)...');
            
            // Isi Dimensi Waktu dummy (Ditambahkan titik setelah nama DB)
            DB::statement("INSERT IGNORE INTO {$dwh}dwh_dim_waktu (id_waktu, tanggal, hari, bulan, nama_bulan, kuartal, tahun) 
                            VALUES (1, '2026-01-01', 'Kamis', 1, 'Januari', 1, 2026);");
            
            // Isi Dimensi Cabang dummy (Ditambahkan titik setelah nama DB)
            DB::statement("INSERT IGNORE INTO {$dwh}dwh_dim_cabang (id_dim_cabang, nama_cabang) 
                            VALUES (1, 'Cabang Utama');");

            // Sinkronisasi data master produk langsung dari tabel laptops OLTP
            DB::statement("INSERT IGNORE INTO {$dwh}dwh_dim_produk (id_dim_produk, nama_produk)
                            SELECT id, nama FROM {$oltp}laptops;");

            // 2. Kosongkan data fakta lama sebelum diisi yang baru
            $this->comment('Mengosongkan tabel dwh_fact_penjualan...');
            DB::statement("TRUNCATE TABLE {$dwh}dwh_fact_penjualan;");

            // 3. Tarik data fakta penjualan dari tabel relasi baru
            $this->comment('Memindahkan data transaksi dari OLTP ke DWH via detail_penjualan...');
            
            DB::statement("
                INSERT INTO {$dwh}dwh_fact_penjualan (
                    id_waktu, id_dim_produk, id_dim_cabang, id_penjualan, metode_pembayaran, 
                    qty, harga_jual, harga_modal, subtotal, profit, created_at
                )
                SELECT 
                    1,                                     -- id_waktu (default)
                    dp.id_produk,                          -- Mengambil id produk dari detail
                    1,                                     -- id_dim_cabang (default)
                    dp.id_penjualan, 
                    p.metode_pembayaran,                   -- Mengambil metode pembayaran dari tabel induk
                    dp.jumlah,                             -- ⚠️ SESUAIKAN: ganti 'jumlah' menjadi 'qty' jika di tabel barumu namanya qty
                    l.harga AS harga_jual,                 -- Mengambil harga jual dari master laptops
                    (l.harga * 0.80) AS harga_modal,       -- Simulasi modal 80% dari harga jual
                    (l.harga * dp.jumlah) AS subtotal,     -- Kalkulasi subtotal (harga * qty)
                    (l.harga - (l.harga * 0.80)) * dp.jumlah AS profit, -- Kalkulasi profit untung bersih
                    NOW() 
                FROM {$oltp}detail_penjualan dp
                JOIN {$oltp}laptops l ON dp.id_produk = l.id
                JOIN {$oltp}penjualan p ON dp.id_penjualan = p.id_penjualan;
            ");
            
            $this->info('--- ETL Pipeline Berhasil Dijalankan! Data DWH telah sinkron dengan detail_penjualan. ---');
        } catch (\Exception $e) {
            $this->error('Error saat menjalankan ETL: ' . $e->getMessage());
        }
    }
}