<?php

namespace App\Admin\Models;

use App\Models\Product as ProductModel;

class Product extends ProductModel
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        // 'content_en',
        // 'content_zh',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'thumb_url',
        'photo_urls',
        'attr_names',
        'grouped_param_values',
        // 'grouped_param_value_string'
    ];

    /* Eloquent Relationships */
    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }
}
