<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded =[];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function added(){

        return $this->belongsTo(User::class);
    }

    public function approved(){

        return $this->belongsTo(User::class);
    }
}
