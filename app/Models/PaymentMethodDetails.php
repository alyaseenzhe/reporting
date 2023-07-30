<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodDetails extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'PaymentMethodDetails';
}
