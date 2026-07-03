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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan'); 
            $table->unsignedBigInteger('id_user');
            $table->string('nomor_invoice')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->string('status_pembayaran')->default('PENDING');
            $table->date('tanggal'); 
            
            // 🌟 TAMBAHKAN BARIS INI:
            $table->string('metode_pembayaran'); 
            
            $table->string('snap_token')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};