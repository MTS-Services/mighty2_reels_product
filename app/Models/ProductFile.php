<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductFile extends BaseModel
{
    //

    protected $fillable = [
        'sort_order',
        'product_id',
        'file',
        'file_type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [
        //
    ];

    protected $casts = [
        //
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

     // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Media type constants
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

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            //
        ]);
    }
}
