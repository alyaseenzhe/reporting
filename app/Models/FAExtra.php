<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAExtra extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'FAExtra';

    public function collectedReverse() {
        return $this->hasMany(FAExtra::class, 'VField9');
    }
}
