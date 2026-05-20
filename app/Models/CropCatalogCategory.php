<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CropCatalogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'has_trees',
    ];

    /**
     * Get the items that belong to the category.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CropCatalogItem::class)->orderBy('sort_order');
    }

    public function branchCropCompositionCollections()
    {
        return $this->belongsToMany(
            BranchCropCompositionCollection::class,
            'branch_crop_collection_items',
            'crop_catalog_category_id',
            'branch_crop_composition_collection_id'
        );
    }
}
