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

        // 2019-05-14
        // 'attributes',
        // 2019-05-14

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
        // 'photo', // 备用字段
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        // 2019-05-14
        // 'attributes' => 'json',
        // 2019-05-14
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
        // 'price_in_usd',
        'parameters_zh',
        'parameters_en',
        'photo_url',
    ];

    /*public function getPriceInUsdAttribute()
    {
        $price_in_usd = ExchangeRate::exchangePrice($this->attributes['price'], 'USD');
        if ($price_in_usd == 0.00) {
            return 0.01;
        }
        return $price_in_usd;
    }*/

    public function getParametersZhAttribute()
    {
        $product = $this->product;
        $parameters_zh = '';
        $parameters_zh .= $product->is_base_size_optional ? $this->attributes['base_size_zh'] : '';
        $parameters_zh .= $product->is_hair_colour_optional ? $this->attributes['hair_colour_zh'] : '';
        $parameters_zh .= $product->is_hair_density_optional ? $this->attributes['hair_density_zh'] : '';
        return $parameters_zh;
    }

    public function getParametersEnAttribute()
    {
        $product = $this->product;
        $parameters_en = '';
        $parameters_en .= $product->is_base_size_optional ? $this->attributes['base_size_en'] : '';
        $parameters_en .= $product->is_hair_colour_optional ? ' - ' . $this->attributes['hair_colour_en'] : '';
        $parameters_en .= $product->is_hair_density_optional ? ' - ' . $this->attributes['hair_density_en'] : '';
        return $parameters_en;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->attributes['photo']) {
            // 如果 photo 字段本身就已经是完整的 url 就直接返回
            if (Str::startsWith($this->attributes['photo'], ['http://', 'https://'])) {
                return $this->attributes['photo'];
            }
            return Storage::disk('public')->url($this->attributes['photo']);
        }
        return '';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
