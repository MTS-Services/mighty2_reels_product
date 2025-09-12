<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    //
    const TYPE_SALE = 0;
    const TYPE_RENT = 1;

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'sort_order',
        'title',
        'description',
        'price',
        'room',
        'city_id',
        'type',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'room' => 'integer',
        'type' => 'integer',
        'status' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the city that owns the product.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get the reels for the product.
     */
    public function reels(): HasMany
    {
        return $this->hasMany(ProductReel::class)->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get the user who created the product.
     */

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include rent products.
     */
    public function scopeRent($query)
    {
        return $query->where('type', self::TYPE_RENT);
    }

    /**
     * Scope a query to only include sale products.
     */
    public function scopeSale($query)
    {
        return $query->where('type', self::TYPE_SALE);
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute()
    {
        return $this->type == self::TYPE_RENT ? 'Rent' : 'Sale';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'Active' : 'Inactive';
    }
}
