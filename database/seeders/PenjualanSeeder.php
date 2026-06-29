<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan updateOrInsert untuk tabel induk penjualan
        DB::table('penjualan')->updateOrInsert(
            ['id_penjualan' => 20001], // Kunci utama transaksi
            [
                'invoice' => 'INV-20260629-00001',
                'id_cabang' => 1, 
                'id_user' => 1,   
                'metode_pembayaran' => 'QRIS',
                'total' => 15000000.00,
                'tanggal' => now(),
            ]
        );

        // Gunakan updateOrInsert untuk detail item penjualan
        DB::table('detail_penjualan')->updateOrInsert(
            ['id_penjualan' => 20001, 'id_produk' => 1001], // Kombinasi unik item
            [
                'qty' => 1,
                'harga_jual' => 15000000.00,
                'harga_modal' => 12710000.00,
                'subtotal' => 15000000.00,
            ]
        );
    }
}