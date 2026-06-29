<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    // 1. Paksa Laravel membaca tabel 'penjualan' (bukan 'penjualans')
    protected $table = 'penjualan';

    // 2. Set kolom Primary Key bawaan tabel Om
    protected $primaryKey = 'id_penjualan';

    // 3. Matikan created_at & updated_at bawaan Laravel karena tabel Om menggunakan kolom 'tanggal'
    public $timestamps = false;

    // 4. Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'invoice',
        'id_cabang',
        'id_user',
        'metode_pembayaran',
        'total',
        'tanggal'
    ];

    // 5. Casting jenis data agar otomatis menjadi object / format yang sesuai
    protected $casts = [
        'tanggal' => 'datetime', // Otomatis bisa pakai fungsi Carbon di Blade (ex: ->format('d-m-Y'))
        'total' => 'decimal:2'
    ];

    /**
     * RELASI: Hubungan ke tabel 'cabang' (Belongs To)
     * Artinya: Setiap 1 transaksi penjualan dicatat di 1 cabang tertentu
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    /**
     * RELASI: Hubungan ke tabel 'users' (Belongs To)
     * Artinya: Setiap 1 transaksi penjualan diproses oleh 1 staf/karyawan
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * RELASI: Hubungan ke tabel 'detail_penjualan' (Has Many)
     * Artinya: Setiap 1 transaksi penjualan bisa memiliki banyak item laptop yang dibeli
     */
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }
}