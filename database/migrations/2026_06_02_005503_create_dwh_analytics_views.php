<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan pembuatan SQL Views dengan mencocokkan kolom riil dwh.
     */
    public function up(): void
    {
        // 1. BUAT VIEW: view_tren_penjualan_bulanan
        DB::connection('mysql_dwh')->statement("
            CREATE OR REPLACE VIEW view_tren_penjualan_bulanan AS
            SELECT 
                w.tahun,
                w.bulan,
                w.nama_bulan,
                c.nama_cabang,
                SUM(f.qty) as total_unit_terjual,
                SUM(f.subtotal) as total_pendapatan,
                SUM(f.profit) as total_keuntungan,
                ROUND((SUM(f.profit) / SUM(f.subtotal)) * 100, 2) as net_profit_margin_persen
            FROM dwh_fact_penjualan f
            JOIN dwh_dim_waktu w ON f.id_waktu = w.id_waktu
            JOIN dwh_dim_cabang c ON f.id_dim_cabang = c.id_dim_cabang
            GROUP BY w.tahun, w.bulan, w.nama_bulan, c.nama_cabang
            ORDER BY w.tahun DESC, w.bulan DESC, total_pendapatan DESC;
        ");

        // 2. BUAT VIEW: view_rekomendasi_restock
        DB::connection('mysql_dwh')->statement("
            CREATE OR REPLACE VIEW view_rekomendasi_restock AS
            SELECT 
                c.nama_cabang,
                p.merek,
                p.nama_produk,
                p.kategori,
                s.jumlah_stok,
                s.stok_minimum,
                s.status_stok
            FROM dwh_fact_stok s
            JOIN dwh_dim_produk p ON s.id_dim_produk = p.id_dim_produk
            JOIN dwh_dim_cabang c ON s.id_dim_cabang = c.id_dim_cabang
            WHERE s.status_stok = 'KRITIS'
            ORDER BY c.nama_cabang ASC, s.jumlah_stok ASC;
        ");
    }

    /**
     * Hapus SQL Views jika dilakukan rollback migrasi.
     */
    public function down(): void
    {
        DB::connection('mysql_dwh')->statement("DROP VIEW IF EXISTS view_tren_penjualan_bulanan;");
        DB::connection('mysql_dwh')->statement("DROP VIEW IF EXISTS view_rekomendasi_restock;");
    }
};