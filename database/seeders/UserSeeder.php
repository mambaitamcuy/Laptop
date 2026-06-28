<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan firstOrCreate agar aman dari error ganda
        User::firstOrCreate(
            ['email' => 'phatethic27@gmail.com'],
            [
                'name' => 'Adhlir Razak Pusat',
                'password' => Hash::make('password123'),
                'role' => 'pusat',
                'id_role' => 1, 
                'id_cabang' => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.parigi@arkadia.com'],
            [
                'name' => 'Admin Cabang Parigi',
                'password' => Hash::make('password123'),
                'role' => 'cabang',
                'id_role' => 1, 
                'id_cabang' => 3,
            ]
        );
    }
}