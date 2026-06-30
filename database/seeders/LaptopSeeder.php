<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaptopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dataset Laptop Premium Arkadia sesuai struktur tabel 'produk' Om
        $datasetLaptop = [
            [
                'id_produk' => 1001, // ID di atas 1000 supaya aman dari auto-increment database
                'id_kategori' => 1,
                'nama_produk' => 'Arkadia Phantom X Elite',
                'merek' => 'ASUS',
                'harga_beli' => 12500000.00,
                'harga_jual' => 15000000.00,
                'status_produk' => 'AKTIF',
                'distribusi_stok' => [
                    ['id_cabang' => 1, 'jumlah_stok' => 45, 'stok_minimum' => 5],  // Cabang Palu
                    ['id_cabang' => 2, 'jumlah_stok' => 20, 'stok_minimum' => 5],  // Cabang Donggala
                    ['id_cabang' => 3, 'jumlah_stok' => 15, 'stok_minimum' => 5],  // Cabang Parigi
                ]
            ],
            [
                'id_produk' => 1002,
                'id_kategori' => 1,
                'nama_produk' => 'Arkadia Eco Air Slim',
                'merek' => 'Acer',
                'harga_beli' => 6000000.00,
                'harga_jual' => 7500000.00,
                'status_produk' => 'AKTIF',
                'distribusi_stok' => [
                    ['id_cabang' => 1, 'jumlah_stok' => 30, 'stok_minimum' => 5],  // Cabang Palu
                    ['id_cabang' => 2, 'jumlah_stok' => 25, 'stok_minimum' => 5],  // Cabang Donggala
                ]
            ],
            [
                'id_produk' => 1003,
                'id_kategori' => 1,
                'nama_produk' => 'Arkadia Pro Studio 16',
                'merek' => 'Lenovo',
                'harga_beli' => 18000000.00,
                'harga_jual' => 21500000.00,
                'status_produk' => 'AKTIF',
                'distribusi_stok' => [
                    ['id_cabang' => 1, 'jumlah_stok' => 12, 'stok_minimum' => 3],  // Cabang Palu
                    ['id_cabang' => 3, 'jumlah_stok' => 8, 'stok_minimum' => 2],   // Cabang Parigi
                ]
            ]
        ];

        foreach ($datasetLaptop as $laptop) {
            // 1. Suntik / Update data ke tabel 'produk'
            DB::table('produk')->updateOrInsert(
                ['id_produk' => $laptop['id_produk']], // Kunci pengecekan primary key
                [
                    'id_kategori' => $laptop['id_kategori'],
                    'nama_produk' => $laptop['nama_produk'],
                    'merek' => $laptop['merek'],
                    'harga_beli' => $laptop['harga_beli'],
                    'harga_jual' => $laptop['harga_jual'],
                    'status_produk' => $laptop['status_produk'],
                ]
            );

            // 2. Suntik / Update data jumlah stok ke tabel 'stok_cabang' masing-masing wilayah
            foreach ($laptop['distribusi_stok'] as $stok) {
                DB::table('stok_cabang')->updateOrInsert(
                    [
                        'id_cabang' => $stok['id_cabang'], 
                        'id_produk' => $laptop['id_produk']
                    ], // Kombinasi unik cabang + produk
                    [
                        'jumlah_stok' => $stok['jumlah_stok'],
                        'stok_minimum' => $stok['stok_minimum']
                    ]
                );
            }
        }
    }
}