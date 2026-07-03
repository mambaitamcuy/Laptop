<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. PROSES SEEDING KE DATABASE OLTP (Live App)
        // ==========================================
        
        $user1 = User::create([
            'name' => 'Adhlir Razak (Pusat)',
            'email' => 'phatethic27@gmail.com', 
            'password' => Hash::make('password123'),
            'role' => 'pusat',
            'id_role' => 1,
            'id_cabang' => null,
            'login_method' => 'GOOGLE',
        ]);

        $user2 = User::create([
            'name' => 'Admin Cabang Parigi',
            'email' => 'admin.parigi@arkadia.com',
            'password' => Hash::make('password123'),
            'role' => 'cabang',
            'id_role' => 2,
            'id_cabang' => 1,
            'login_method' => 'MANUAL',
        ]);

        $user3 = User::create([
            'name' => 'Test Karyawan',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'id_role' => 3,
            'id_cabang' => 1,
            'login_method' => 'MANUAL',
        ]);

        // ==========================================
        // 2. PROSES REPLIKASI / ETL KE DWH (Analisis)
        // ==========================================
        // Kita gunakan DB::connection() untuk menembak database dwh langsung
        
        $dwh = DB::connection('mysql_dwh')->table('users');
        
        // Hapus data lama di DWH agar tidak duplikat saat diseed ulang
        $dwh->truncate(); 

        $dwh->insert([
            [
                'id_user'   => $user1->id_user,
                'name'      => $user1->name,
                'email'     => $user1->email,
                'role'      => $user1->role,
                'id_cabang' => $user1->id_cabang,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'id_user'   => $user2->id_user,
                'name'      => $user2->name,
                'email'     => $user2->email,
                'role'      => $user2->role,
                'id_cabang' => $user2->id_cabang,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'id_user'   => $user3->id_user,
                'name'      => $user3->name,
                'email'     => $user3->email,
                'role'      => $user3->role,
                'id_cabang' => $user3->id_cabang,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);

        // ==========================================
        // 🌟 3. YANG BARU DITAMBAHKAN: SEED DATA PENJUALAN (OLTP)
        // ==========================================
        // Kita isi agar query `select count(*) from penjualan` di dashboard tidak kosong
        DB::table('penjualan')->insert([
            [
                'id_user' => $user1->id_user,
                'nomor_invoice' => 'INV-' . time() . '-001',
                'total_harga' => 15000000.00,
                'status_pembayaran' => 'SUCCESS',
                'tanggal' => now()->toDateString(),
                
                // 🌟 ISI METODE PEMBAYARAN:
                'metode_pembayaran' => 'MIDTRANS', 
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $user3->id_user,
                'nomor_invoice' => 'INV-' . time() . '-002',
                'total_harga' => 8500000.00,
                'status_pembayaran' => 'PENDING',
                'tanggal' => now()->toDateString(),
                
                // 🌟 ISI METODE PEMBAYARAN:
                'metode_pembayaran' => 'TRANSFER BANK', 
                
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }   
}