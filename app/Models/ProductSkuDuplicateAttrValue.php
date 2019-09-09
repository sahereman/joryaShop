<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSkuDuplicateAttrValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_sku_id',
        'name',
        'value',
        'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        //
    ];

    /* Accessors */

    /* Mutators */

    /* Eloquent Relationships */
    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }
}
