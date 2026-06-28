<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| Penjadwalan Integrasi & Pembersihan (W5)
|--------------------------------------------------------------------------
| Perintah ini akan menjalankan pipa ETL ArkadiaLP setiap tengah malam.
| Sistem akan otomatis membersihkan data DWH lama (Truncate) 
| dan mengintegrasikan seluruh data OLTP terbaru secara rapi.
| withoutOverlapping() mencegah proses bertabrakan jika loading lambat.
*/
Schedule::command('arkadia:etl --all')->dailyAt('00:00')->withoutOverlapping();