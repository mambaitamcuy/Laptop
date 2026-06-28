<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $connection = 'mysql_oltp';
    protected $table = 'produk';
    protected $guarded = [];
}