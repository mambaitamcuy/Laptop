<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;

class ProcessETLPipeline
{
    /**
     * Menjalankan pemindahan, pembersihan, dan pengisian data ke gudang data (DWH).
     */
    public function handle()
    {
        // 1. Matikan pengecekan Foreign Key agar proses truncate/kosongkan tabel lancar
        DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 0;');
        
        // Kosongkan data lama di gudang data (DWH) sebelum diisi data baru yang bersih
        DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->truncate();
        DB::connection('mysql_dwh')->table('dwh_fact_stok')->truncate();

        // 2. PROSES ETL PENJUALAN
        // Mengambil data mentah dari database operasional (OLTP)
        $dataPenjualan = DB::connection('mysql_oltp')->table('penjualan')->get();
        
        foreach ($dataPenjualan as $row) {
            // Mengatasi field 'id_waktu' yang wajib diisi (NOT NULL)
            // Sambil menunggu tabel dimensi waktu lengkap minggu depan, kita bisa mapping sementara 
            // menggunakan format integer tanggal (contoh: 20251011 dari tanggal 2025-10-11)
            $tanggalRaw = $row->created_at ?? ($row->tanggal ?? now());
            $idWaktu = date('Ymd', strtotime($tanggalRaw));

            // Memasukkan data ke DWH lengkap dengan semua field wajib
            DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->insert([
                'id_penjualan'  => $row->id_penjualan ?? 0,
                'id_dim_produk' => 1, // Default sementara, sesuaikan dengan logic dimensimu
                'id_dim_cabang' => $row->id_cabang ?? 0,
                'id_waktu'      => $idWaktu, // MENGATASI ERROR 1364: Field id_waktu terisi otomatis
                'subtotal'      => $row->total ?? 0,
                'profit'        => ($row->total * 0.1) ?? 0, // Kalkulasi profit otomatis (10%)
                'qty'           => 1,
                'harga_jual'    => $row->total ?? 0,
                'harga_modal'   => ($row->total * 0.9) ?? 0
            ]);
        }

        // 3. PROSES ETL STOK
        // Mengambil data stok dari database operasional (OLTP)
        $dataStok = DB::connection('mysql_oltp')->table('stok_cabang')->get();
        
        foreach ($dataStok as $row) {
            DB::connection('mysql_dwh')->table('dwh_fact_stok')->insert([
                'id_dim_cabang' => $row->id_cabang ?? 0,
                'id_dim_produk' => $row->id_produk ?? 0,
                'jumlah_stok'   => $row->jumlah ?? 0,
                'stok_minimum'  => 5 // Batas aman stok sebelum dianggap KRITIS
            ]);
        }

        // 4. Nyalakan kembali pengecekan Foreign Key setelah proses pemuatan data selesai
        DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}