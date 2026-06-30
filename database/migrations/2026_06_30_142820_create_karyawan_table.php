<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            // ID Karyawan sebagai Primary Key (Sesuai dengan orderBy di Controller)
            $table->id('id_karyawan'); 
            
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('jabatan');
            $table->integer('id_cabang')->nullable(); // Boleh kosong jika dia staf Pusat
            
            $table->timestamps(); // Menghasilkan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};