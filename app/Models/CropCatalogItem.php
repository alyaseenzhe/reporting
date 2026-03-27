<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropCatalogItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_catalog_category_id',
        'name',
        'sort_order',
    ];

    /**
     * Get the category that owns the crop item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CropCatalogCategory::class, 'crop_catalog_category_id');
    }
}
