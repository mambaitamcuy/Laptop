<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DwhFactPenjualan extends Model
{
    // Ini kuncinya agar model ini tahu dia harus mencari data ke database DWH
    protected $connection = 'mysql_dwh';
    protected $table = 'dwh_fact_penjualan';
    protected $guarded = [];
}