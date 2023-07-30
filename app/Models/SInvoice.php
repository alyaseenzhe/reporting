<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SInvoice extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'sinvoice';
}
