<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $connection = 'mysql_oltp';
    protected $table = 'penjualan';
    protected $guarded = [];
}