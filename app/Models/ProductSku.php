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

        // 2019-01-22
        'base_size_en',
        'base_size_zh',
        'hair_colour_en',
        'hair_colour_zh',
        'hair_density_en',
        'hair_density_zh',
        // 2019-01-22

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
        'photo', // 备用字段
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        //
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
        $price_in_usd = ExchangeRate::exchangePrice($this->attributes['price'], 'USD');
        if ($price_in_usd == 0.00) {
            return 0.01;
        }
        return $price_in_usd;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
