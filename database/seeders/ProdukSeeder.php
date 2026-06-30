<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrInsert agar tidak memicu error Duplicate Entry
        DB::table('produk')->updateOrInsert(
            ['id_produk' => 1001], // Kunci pencarian/Primary Key
            [
                'id_kategori' => 1,
                'nama_produk' => 'Arkadia Phantom X',
                'merek' => 'ASUS', 
                'harga_beli' => 12710000.00,
                'harga_jual' => 15000000.00,
                'status_produk' => 'AKTIF',
            ]
        );

        // Amankan juga data stok cabang agar tidak duplikat
        DB::table('stok_cabang')->updateOrInsert(
            ['id_cabang' => 1, 'id_produk' => 1001], // Kombinasi kunci unik
            [
                'jumlah_stok' => 45,
                'stok_minimum' => 5
            ]
        );
    }
}