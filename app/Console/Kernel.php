<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar perintah Artisan yang ingin dijadwalkan.
     */
    protected $commands = [
        \App\Console\Commands\ArkadiaETLPipeline::class,
    ];

    /**
     * Menentukan jadwal eksekusi tugas (Cron Job).
     */
    protected function schedule(Schedule $schedule)
    {
        // Menjalankan command 'arkadia:etl' setiap hari jam 00:00
        $schedule->command('arkadia:etl')->daily();
        
        // Opsional: Jika ingin mengirim log ke file agar Om tahu ETL sudah jalan
        // $schedule->command('arkadia:etl')->daily()->appendOutputTo(storage_path('logs/etl.log'));
    }

    /**
     * Memuat perintah Artisan lainnya.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}