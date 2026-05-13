<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchCropCompositionCollection extends Model
{
    use HasFactory;

    protected $guarded = [];
//    protected $fillable = [
//        'type',
//        'lead_id',
//        'collection_date',
//        'user_id',
//        'created_by',
//        'updated_by',
//        'branch_id',
//        'branch_name',
//        'engineer_id',
//        'engineer_name',
//        'customer_code',
//        'customer_name',
//        'farms_count',
//        'total_farm_area_hectares',
//        'opportunities',
//        'challenges',
//        'notes',
//    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // Lead

            parent::boot();

            static::creating(function ($model) {

                $lastId = static::max('id') + 1;

                $model->code = 'L' . $lastId;
            });
//            }
        });
    }
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
     * Get the user who created the collection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userUpdate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function userCreate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
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
