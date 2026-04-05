<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchCropCollectionCultivationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_crop_composition_collection_id',
        'agri_type_id',
        'agri_detail_id',
        'detail_type',
        'unit_count',
        'total_area_hectares',
        'sort_order',
    ];

    protected $casts = [
        'unit_count' => 'decimal:2',
        'total_area_hectares' => 'decimal:2',
    ];

    /**
     * Get the parent branch crop composition collection.
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(
            BranchCropCompositionCollection::class,
            'branch_crop_composition_collection_id'
        );
    }

    public function agriDetails(){
        return $this->belongsTo(AgriDetais::class);
    }

    public function agriType(){
        return $this->belongsTo(AgriType::class);
    }
}
