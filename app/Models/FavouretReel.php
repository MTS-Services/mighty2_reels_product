<?php

namespace App\Models;

use App\Models\BaseModel;

class FavouretReel extends BaseModel
{
    //

    protected $fillable = [
        'sort_order',
         'product_id',
        'user_id',
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

    public function productReel()
    {
        return $this->belongsTo(ProductReel::class, 'product_id');
    }

    /**
     * Relationship: Favourite belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
