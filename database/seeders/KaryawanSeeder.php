<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu agar tidak duplikat saat di-seed ulang
        DB::table('karyawan')->truncate();

        DB::table('karyawan')->insert([
            // 👑 Otoritas Tertinggi
            [
                'nama'       => 'Adhlir Razak',
                'email'      => 'adhlir.razak@arkadia.com',
                'jabatan'    => 'Super Admin',
                'id_cabang'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 🏢 Admin-Admin Cabang
            [
                'nama'       => 'Rian Hidayat',
                'email'      => 'rian.admin1@arkadia.com',
                'jabatan'    => 'admin',
                'id_cabang'  => 1, // Cabang 01
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Siti Rahma',
                'email'      => 'siti.admin2@arkadia.com',
                'jabatan'    => 'admin',
                'id_cabang'  => 2, // Cabang 02
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Fikri Haikal',
                'email'      => 'fikri.admin3@arkadia.com',
                'jabatan'    => 'admin',
                'id_cabang'  => 3, // Cabang 03
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 🧑‍💼 Karyawan / Staf Operasional
            [
                'nama'       => 'Rendi Pangestu',
                'email'      => 'rendi.sales@arkadia.com',
                'jabatan'    => 'kasir',
                'id_cabang'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Dinda Lestari',
                'email'      => 'dinda.sales@arkadia.com',
                'jabatan'    => 'kasir',
                'id_cabang'  => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Taufik Hidayat',
                'email'      => 'taufik.technician@arkadia.com',
                'jabatan'    => 'teknisi',
                'id_cabang'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Putri Ayu',
                'email'      => 'putri.sales@arkadia.com',
                'jabatan'    => 'kasir',
                'id_cabang'  => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}