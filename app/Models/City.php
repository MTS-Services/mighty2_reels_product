<?php

namespace App\Models;

use App\Models\BaseModel;

class City extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'name',
        'status',

        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

   public function product()
    {
        return $this->hasMany(Product::class, 'city_id', 'id');
    }

     /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
}
