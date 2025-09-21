<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
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
     * Get all files for the product (unified approach)
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id')->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get only image files for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id')
                   ->where('status', self::STATUS_ACTIVE)
                   ->where('file_type', ProductFile::TYPE_IMAGE);
    }

    /**
     * Get only video/reel files for the product.
     */
    public function reels(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id')
                   ->where('status', self::STATUS_ACTIVE)
                   ->where('file_type', ProductFile::TYPE_REEL);
    }

    /**
     * Alias for reels (for compatibility)
     */
    public function videos(): HasMany
    {
        return $this->reels();
    }

    /**
     * Get active files with specific type
     */
    public function activeFiles()
    {
        return $this->files()->active();
    }

    /**
     * Get active images
     */
    public function activeImages()
    {
        return $this->images()->active();
    }

    /**
     * Get active videos/reels
     */
    public function activeVideos()
    {
        return $this->reels()->active();
    }

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
        return $this->type == self::TYPE_RENT ? 'For Rent' : 'For Sale';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'Active' : 'Inactive';
    }

    /**
     * Get the status color class.
     */
    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'badge-success' : 'badge-error';
    }

    /**
     * Get the primary image for the product
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() 
               ?? $this->images()->first();
    }

    /**
     * Get the primary video for the product
     */
    public function getPrimaryVideoAttribute()
    {
        return $this->reels()->where('is_primary', true)->first() 
               ?? $this->reels()->first();
    }

    /**
     * Check if product has images
     */
    public function hasImages()
    {
        return $this->images()->exists();
    }

    /**
     * Check if product has videos
     */
    public function hasVideos()
    {
        return $this->reels()->exists();
    }

    /**
     * Get total files count
     */
    public function getTotalFilesCount()
    {
        return $this->files()->count();
    }

    /**
     * Get images count
     */
    public function getImagesCount()
    {
        return $this->images()->count();
    }

    /**
     * Get videos count
     */
    public function getVideosCount()
    {
        return $this->reels()->count();
    }

    /**
     * Favorite relationships
     */
    public function favouriteReels()
    {
        return $this->hasMany(FavouretReel::class, 'product_id');
    }

    /**
     * Relationship: Product is favorited by many users
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favouret_reels', 'product_id', 'user_id')
            ->withTimestamps()
            ->withPivot(['created_by', 'updated_by', 'deleted_by']);
    }

    /**
     * Get favorites count
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favouriteReels()->count();
    }

    /**
     * Check if product is favorited by a specific user
     */
    public function isFavoritedBy($userId)
    {
        return $this->favouriteReels()
            ->where('user_id', $userId)
            ->exists();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
            'status_color',
            'type_label',
            'primary_image',
            'primary_video',
            'favorites_count'
        ]);
    }
}