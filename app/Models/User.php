<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Menyelaraskan primary key dengan database
    protected $primaryKey = 'id_user'; 
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Properti yang boleh diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'login_method',
        'role',
        'id_role',
        'id_cabang',
    ];

    /**
     * Properti yang harus disembunyikan
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast atribut data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Meng-hash password otomatis jika diinput lewat seeder/eloquent
    ];
}