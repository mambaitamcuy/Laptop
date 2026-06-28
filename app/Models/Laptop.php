<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    protected $connection = 'mysql_oltp';
    protected $table = 'laptops';
    protected $guarded = [];
}