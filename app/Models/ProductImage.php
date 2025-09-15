<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends BaseModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'product_id',
        'file', // Keep 'file' as the main attribute for consistency with ProductReel
        'image', // Also allow 'image' for backward compatibility
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
     * Scope a query to only include active images.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get the full URL of the image.
     * This handles both 'file' and 'image' attributes for flexibility
     */
    public function getUrlAttribute()
    {
        $filePath = $this->file ?? $this->image ?? null;
        
        if (!$filePath) {
            return null;
        }

        // Check if it's already a full URL
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return $filePath;
        }

        // Return Storage URL for local files
        return Storage::url($filePath);
    }

    /**
     * Get the file path (for backward compatibility)
     */
    public function getFilePathAttribute()
    {
        return $this->file ?? $this->image;
    }
}