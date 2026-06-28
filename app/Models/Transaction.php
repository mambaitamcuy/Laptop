<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mysql_oltp';
    protected $table = 'transactions';
    protected $guarded = [];
}