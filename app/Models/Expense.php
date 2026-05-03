<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Expense extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded =[];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function approved(){

        return $this->belongsTo(User::class);
    }

    public function expenseDetails(){

        return $this->hasMany(ExpenseDetails::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('expense_attachments')
            ->useDisk('public');
    }

}
