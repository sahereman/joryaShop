<?php

namespace App\Admin\Models;

use App\Models\PeriodProduct as PeriodProductModel;

class PeriodProduct extends PeriodProductModel
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'product_name',
        // 'length',
        'period',
        'status'
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
