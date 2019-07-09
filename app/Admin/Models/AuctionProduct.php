<?php

namespace App\Admin\Models;

use App\Models\AuctionProduct as AuctionProductModel;

class AuctionProduct extends AuctionProductModel
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'product_name',
        'status_name',
        // 'length',
        'period',
        'bids'
    ];

    /* Accessors */
    public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }

    public function getPeriodAttribute()
    {
        return "{$this->attributes['started_at']}  至  {$this->attributes['stopped_at']}";
    }

    /* Mutators */
    public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }

    public function setPeriodAttribute($value)
    {
        unset($this->attributes['period']);
    }
}
