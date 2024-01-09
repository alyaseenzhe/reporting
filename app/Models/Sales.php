<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';

    public function product() {
        return $this->belongsTo(Products::class, 'ProductCode');
    }
}
