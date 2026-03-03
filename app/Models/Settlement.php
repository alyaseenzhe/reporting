<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Settlement extends Model implements HasMedia
{
    use HasFactory;
    use \Spatie\MediaLibrary\InteractsWithMedia;


    protected $guarded = [];


    public function registerMediaCollections(): void
{
    $this->addMediaCollection('employee-files');
    $this->addMediaCollection('hr-files');
    $this->addMediaCollection('accounting-files');
    $this->addMediaCollection('it-files');
    $this->addMediaCollection('finance-files');
}


}


