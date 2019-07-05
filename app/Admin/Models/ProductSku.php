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
        'attr_value_string',
        'attr_value_options'
    ];

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
