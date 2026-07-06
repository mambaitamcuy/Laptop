<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pilihan: Kosongkan tabel users terlebih dahulu agar bersih dari 1000 data lama
        // DB::table('users')->truncate(); 

        // 1. Akun Pusat (Super Admin)
        User::firstOrCreate(
            ['email' => 'phatethic27@gmail.com'],
            [
                'name'         => 'Adhlir Razak Pusat',
                'password'     => Hash::make('password123'),
                'role'         => 'pusat',
                'id_role'      => 1, // Role Pusat
                'id_cabang'    => 1, // Jika tidak boleh null, arahkan ke Cabang Utama (1)
                'login_method' => 'MANUAL',
            ]
        );

        // 2. Akun Admin Cabang Parigi
        User::firstOrCreate(
            ['email' => 'admin.parigi@arkadia.com'],
            [
                'name'         => 'Admin Cabang Parigi',
                'password'     => Hash::make('password123'),
                'role'         => 'cabang',
                'id_role'      => 2, // Diubah ke 2 (Sesuaikan dengan ID Role Cabang di tabel roles kamu)
                'id_cabang'    => 3, // Cabang Parigi
                'login_method' => 'MANUAL',
            ]
        );
    }
}