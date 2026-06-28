<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $datasetCabang = [
            ['id_cabang' => 1, 'nama_cabang' => 'Palu', 'kota' => 'Palu', 'alamat' => 'Jl. Sudirman No. 12'],
            ['id_cabang' => 2, 'nama_cabang' => 'Donggala', 'kota' => 'Donggala', 'alamat' => 'Jl. Pelabuhan No. 45'],
            ['id_cabang' => 3, 'nama_cabang' => 'Parigi', 'kota' => 'Parigi', 'alamat' => 'Jl. Trans Sulawesi No. 88'],
        ];

        foreach ($datasetCabang as $data) {
            DB::table('arkadialp_dwh.dwh_dim_cabang')->updateOrInsert(
                ['id_cabang' => $data['id_cabang']], 
                [
                    'nama_cabang' => $data['nama_cabang'],
                    'kota'        => $data['kota'],
                    'alamat'      => $data['alamat'],
                    // Kolom timestamps dihapus dari sini agar tidak memicu error
                ]
            );
        }
    }
}