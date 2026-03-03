<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Request extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;


    protected $guarded = [];


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = auth()->id();
            $model->created_by = auth()->id();
            $model->status = 1;
            $model->current_step = 'hr';
        });
    }

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function creater(){

        return $this->belongsTo(User::class , 'created_by');
    }

    public function settlements(){
        return $this->belongsToMany(Settlement::class)->withPivot('is_done');

    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('employee-files')
        >useDisk('public');
        $this->addMediaCollection('hr-files')
        >useDisk('public');
        $this->addMediaCollection('accounting-files');
        $this->addMediaCollection('it-files');
        $this->addMediaCollection('finance-files');
    }


}
