<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchCropCompositionCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_date',
        'branch_name',
        'engineer_id',
        'customer_code',
        'customer_name',
        'farms_count',
        'total_farm_area_hectares',
        'opportunities',
        'challenges',
        'notes',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'total_farm_area_hectares' => 'decimal:2',
    ];

    /**
     * Get the engineer assigned to the collection.
     */
    public function engineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    /**
     * Get the cultivation type rows for the collection.
     */
    public function cultivationTypes(): HasMany
    {
        return $this->hasMany(BranchCropCollectionCultivationType::class)
            ->orderBy('sort_order');
    }

    /**
     * Get the crop composition rows for the collection.
     */
    public function cropItems(): HasMany
    {
        return $this->hasMany(BranchCropCollectionItem::class)
            ->orderBy('sort_order');
    }
}
