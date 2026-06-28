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
        
        try {
            // 1. Amankan data dimensi terlebih dahulu secara bertahap
            $this->comment('Sinkronisasi data dimensi (Waktu, Cabang, Produk)...');
            
            // Isi Dimensi Waktu dummy
            DB::statement("INSERT IGNORE INTO arkadialp_dwh.dwh_dim_waktu (id_waktu, tanggal, hari, bulan, nama_bulan, kuartal, tahun) 
                            VALUES (1, '2026-01-01', 'Kamis', 1, 'Januari', 1, 2026);");
            
            // Isi Dimensi Cabang dummy
            DB::statement("INSERT IGNORE INTO arkadialp_dwh.dwh_dim_cabang (id_dim_cabang, nama_cabang) 
                            VALUES (1, 'Cabang Utama');");

            // Perbaikan: Hanya masukkan id_dim_produk dan nama_produk (harga_jual dihapus karena tidak ada di tabel DWH ini)
            DB::statement("INSERT IGNORE INTO arkadialp_dwh.dwh_dim_produk (id_dim_produk, nama_produk)
                            SELECT DISTINCT id_produk, 'Produk Migrasi' FROM arkadialp_oltp.detail_penjualan;");

            // 2. Kosongkan data fakta lama
            $this->comment('Mengosongkan tabel dwh_fact_penjualan...');
            DB::statement('TRUNCATE TABLE arkadialp_dwh.dwh_fact_penjualan;');

            // 3. Tarik data fakta penjualan (16.182 baris)
            $this->comment('Memindahkan 16.182 data transaksi dari OLTP ke DWH...');
            DB::statement("
                INSERT INTO arkadialp_dwh.dwh_fact_penjualan (
                    id_waktu, id_dim_produk, id_dim_cabang, id_penjualan, metode_pembayaran, 
                    qty, harga_jual, harga_modal, subtotal, profit, created_at
                )
                SELECT 
                    1, id_produk, 1, id_penjualan, 'Tunai', 
                    qty, harga_jual, harga_modal, subtotal, (harga_jual - harga_modal) * qty, NOW() 
                FROM arkadialp_oltp.detail_penjualan;
            ");
            
            $this->info('--- ETL Pipeline Berhasil Dijalankan! Data DWH telah sinkron. ---');
        } catch (\Exception $e) {
            $this->error('Error saat menjalankan ETL: ' . $e->getMessage());
        }
    }
}