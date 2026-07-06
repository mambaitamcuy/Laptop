<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArkadiaETLPipeline extends Command
{
    /**
     * Nama dan signature dari console command.
     * Anda bisa menjalankannya via terminal: php artisan arkadia:etl
     */
    protected $signature = 'arkadia:etl';

    /**
     * Deskripsi dari console command.
     */
    protected $description = 'Menjalankan ETL Pipeline Arkadia (Sinkronisasi OLTP ke DWH via Stored Procedure)';

    /**
     * Eksekusi console command.
     */
    public function handle()
    {
        $this->info('==================================================');
        $this->info('       MEMULAI ETL PIPELINE ARKADIA (SP)          ');
        $this->info('==================================================');
        
        $this->comment('Memanggil Stored Procedure JalankanPipaETL pada database DWH...');

        try {
            // Memicu stored procedure yang ada di database arkadialp_dwh
            // Menggunakan unprepared statement agar eksekusi prosedur berjalan lancar tanpa return binding
            DB::connection('dwh')->unprepared("CALL JalankanPipaETL()");

            $this->info('--------------------------------------------------');
            $this->info(' SAKSES! ETL Pipeline Berhasil Dijalankan.        ');
            $this->info(' Data fakta dan dimensi di DWH sudah sinkron.     ');
            $this->info('==================================================');
            
        } catch (\Exception $e) {
            $this->error('==================================================');
            $this->error(' GAGAL! Terjadi error saat menjalankan ETL:       ');
            $this->error(' ' . $e->getMessage());
            $this->error('==================================================');
            
            $this->comment('Tips Perbaikan:');
            $this->comment('1. Pastikan koneksi bernama "dwh" sudah terdaftar di config/database.php');
            $this->comment('2. Pastikan settingan DB_DATABASE_DWH di .env sudah mengarah ke database DWH Anda.');
            $this->comment('3. Pastikan user database Anda memiliki hak akses untuk memanggil Procedure.');
        }
    }
}