<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mysql')->table('users', function (Blueprint $table) {
            // Menambahkan role: pusat, cabang, atau karyawan
            $table->enum('role', ['pusat', 'cabang', 'karyawan'])->default('karyawan')->after('password');
            // Menambahkan foreign key id_cabang yang merujuk ke tabel cabang di OLTP
            $table->unsignedInteger('id_cabang')->nullable()->after('role');
            
            // Definisikan foreign key (pastikan tabel cabang sudah ada di OLTP)
            $table->foreign('id_cabang')->references('id_cabang')->on('cabang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->table('users', function (Blueprint $table) {
            $table->dropForeign(['id_cabang']);
            $table->dropColumn(['role', 'id_cabang']);
        });
    }
};