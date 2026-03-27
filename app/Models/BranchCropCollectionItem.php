<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchCropCollectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_crop_composition_collection_id',
        'crop_catalog_category_id',
        'crop_catalog_item_id',
        'cycles_per_year',
        'trees_count',
        'total_area_hectares',
        'sort_order',
    ];

    protected $casts = [
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

    /**
     * Get the crop category linked to the row.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CropCatalogCategory::class, 'crop_catalog_category_id');
    }

    /**
     * Get the crop item linked to the row.
     */
    public function cropItem(): BelongsTo
    {
        return $this->belongsTo(CropCatalogItem::class, 'crop_catalog_item_id');
    }
}
