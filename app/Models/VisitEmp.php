<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitEmp extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function visit() {
        return $this->belongsTo(Visit::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'emp_code', 'name', 'email', 'group','is_active']);
    }
}
