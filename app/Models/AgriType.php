<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriType extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function agriDetails(){

        return $this->hasMany(AgriDetais::class);
    }

    public function branchCropCompositionCollections()
    {
        return $this->belongsToMany(
            BranchCropCompositionCollection::class,
            'branch_crop_collection_cultivation_types',
            'agri_type_id',
            'branch_crop_composition_collection_id'
        );
    }
}
