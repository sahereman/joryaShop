<?php

namespace App\Admin\Models;

use App\Models\ProductSku as ProductSkuModel;

class ProductSku extends ProductSkuModel
{
    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'photo_url',
        'product_name',
        'price',
        'attr_value_string',
        'attr_value_options',
        'custom_attr_value_string'
    ];

    /* Accessors */
    public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }

    /* Mutators */
    public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
