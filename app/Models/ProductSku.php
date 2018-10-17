<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'product_id',
        'name_en',
        'name_zh',
        'photo',
        'price',
        'stock',
        'sales'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getRealShippingFeeByCurrency($currency = 'CNY')
    {
        if ($currency == 'CNY') {
            return $this->product->shipping_fee;
        } else {
            $exchangeRate = ExchangeRate::where('currency', $currency)->first();
            return $this->product->shipping_fee * $exchangeRate->rate;
        }
    }

    public function getRealPriceByCurrency($currency = 'CNY')
    {
        if ($currency == 'CNY') {
            return $this->attributes['price'];
        } else {
            $exchangeRate = ExchangeRate::where('currency', $currency)->first();
            return $this->attributes['price'] * $exchangeRate->rate;
        }
    }
}
