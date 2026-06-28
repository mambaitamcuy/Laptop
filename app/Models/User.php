<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Trik Bypass Kuliah: Pakai email sebagai primary key agar session login mengikat sempurna
    protected $primaryKey = 'email'; 
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Properti yang boleh diisi secara massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'login_method',
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
        'password' => 'hashed',
    ];
}