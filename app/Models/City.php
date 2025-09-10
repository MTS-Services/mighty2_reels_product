<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends BaseModel
{
     const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name',
        'slug',
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
     * Get the products for the city.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the user who created the city.
     */

    /**
     * Scope a query to only include active cities.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'Active' : 'Inactive';
    }
}
