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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name'
    ];

    /* Accessors */
    public function getNameAttribute()
    {
        return $this->attr->name;
    }

    /* Mutators */
    public function setNameAttribute($value)
    {
        unset($this->attributes['name']);
    }

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
