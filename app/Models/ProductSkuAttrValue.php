<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSkuAttrValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_sku_id',
        'product_attr_id',
        'value',
        'sort'
    ];

    /* Eloquent Relationships */
    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    public function attr()
    {
        return $this->belongsTo(ProductAttr::class, 'product_attr_id');
    }
}
