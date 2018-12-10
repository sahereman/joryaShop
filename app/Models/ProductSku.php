<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'photo', // 备用字段
        'price',
        'stock',
        'sales',
    ];


    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        // 'is_index' => 'boolean',
        // 'on_sale' => 'boolean',
        // 'photos' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'price_in_usd',
    ];

    public function getPriceInUsdAttribute()
    {
        return ExchangeRate::exchangePrice($this->attributes['price'], 'USD');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
