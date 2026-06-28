<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearDWH extends Command
{
    // Nama command yang akan diketik di CMD
    protected $signature = 'arkadia:clear-dwh';
    protected $description = 'Mengosongkan seluruh data di Data Warehouse (Dashboard kembali ke 0)';

    public function handle()
    {
        $this->info('Memulai pengosongan Data Warehouse...');

        // Matikan Foreign Key agar proses truncate lancar
        DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 0;');
        
        // Hapus bersih data di tabel fakta
        DB::connection('mysql_dwh')->table('dwh_fact_penjualan')->truncate();
        DB::connection('mysql_dwh')->table('dwh_fact_stok')->truncate();
        
        DB::connection('mysql_dwh')->statement('SET FOREIGN_KEY_CHECKS = 1;');

        $this->info('Data Warehouse berhasil dikosongkan! Dashboard sekarang kosong.');
    }
}