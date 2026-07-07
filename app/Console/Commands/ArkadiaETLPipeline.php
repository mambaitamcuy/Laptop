<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArkadiaETLPipeline extends Command
{
    protected $signature = 'arkadia:etl';
    protected $description = 'Menjalankan ETL Pipeline Arkadia via Stored Procedure Bawaan';

    public function handle()
    {
        $this->info('--- Memulai ETL Pipeline Arkadia ---');
        try {
            DB::connection('mysql_dwh')->unprepared("CALL JalankanPipaETL();");
            $this->info('--- Pipa ETL Berhasil Dijalankan! ---');
        } catch (\Exception $e) {
            $this->error('Error ETL: ' . $e->getMessage());
            throw $e; // Melempar ulang exception agar bisa ditangani di controller jika perlu
        }
    }
}