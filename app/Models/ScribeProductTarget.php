<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScribeProductTarget extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'ProductsTarget';
}
