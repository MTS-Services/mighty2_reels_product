<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends BaseModel
{
    //
const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'product_id',
        'file',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the image.
     */
    /**
     * Scope a query to only include active images.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get the full URL of the image.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file);
    }
}
