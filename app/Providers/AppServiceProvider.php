<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // 💡 WAJIB ADA: Ini baris yang mengimpor Class Gate asli Laravel
use App\Models\User; // 💡 WAJIB ADA: Ini untuk mendeteksi model User Om

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // PENTING: Pastikan huruf "G" di "Gate" menggunakan huruf KAPITAL

        // Aturan 1: Manajemen karyawan hanya boleh diakses Owner (super admin) & Manager (admin)
        Gate::define('access-management', function (User $user) {
            return in_array($user->role, ['super admin', 'admin']);
        });

        // Aturan 2: Jalankan Pipa ETL HANYA boleh dieksekusi oleh Owner (super admin)
        Gate::define('run-etl-procedural', function (User $user) {
            return $user->role === 'super admin';
        });
    }
}