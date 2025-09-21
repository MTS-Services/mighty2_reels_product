<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductFile extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'product_id',
        'file',
        'file_type',
        'is_primary',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [
        //
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'status' => 'integer',
        'file_type' => 'integer',
        'sort_order' => 'integer',
    ];

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // File type constants
    const TYPE_IMAGE = 1;
    const TYPE_REEL = 2;

    /**
     * Relationships
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeImages($query)
    {
        return $query->where('file_type', self::TYPE_IMAGE);
    }

    public function scopeReels($query)
    {
        return $query->where('file_type', self::TYPE_REEL);
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', self::TYPE_REEL);
    }
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
     * Accessors & Mutators
     */
    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/' . $this->file) : null;
    }

    public function getIsImageAttribute()
    {
        return $this->file_type === self::TYPE_IMAGE;
    }

    public function getIsVideoAttribute()
    {
        return $this->file_type === self::TYPE_REEL;
    }

    public function getFileTypeNameAttribute()
    {
        return match($this->file_type) {
            self::TYPE_IMAGE => 'Image',
            self::TYPE_REEL => 'Video',
            default => 'Unknown'
        };
    }

    /**
     * Static methods for file type detection
     */
    public static function getImageMimeTypes()
    {
        return [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ];
    }

    public static function getVideoMimeTypes()
    {
        return [
            'video/mp4',
            'video/avi',
            'video/mov',
            'video/quicktime',
            'video/x-msvideo',
            'video/m4v'
        ];
    }

    public static function determineFileTypeFromMime($mimeType)
    {
        if (in_array($mimeType, self::getImageMimeTypes())) {
            return self::TYPE_IMAGE;
        }
        
        if (in_array($mimeType, self::getVideoMimeTypes())) {
            return self::TYPE_REEL;
        }
        
        return null;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'file_url',
            'is_image', 
            'is_video',
            'file_type_name',
            'status_label',
            'status_color',
        ]);
    }
}