<?php

namespace App\Models;

use App\Admin\Models\ProductSku;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_sku_id',
        'price',
        'number',
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /* Eloquent Relationships */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id'); // use App\Admin\Models\ProductSku;
    }
}
