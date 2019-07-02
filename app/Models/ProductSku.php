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
        'name_en', // 备用字段
        'name_zh', // 备用字段

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
        'photo_url',
        'attr_value_composition'
    ];

    /* Accessors */
    /*public function getParametersZhAttribute()
    {
        $product = $this->product;
        $parameters_zh = '';
        $parameters_zh .= $product->is_base_size_optional ? $this->attributes['base_size_zh'] : '';
        $parameters_zh .= $product->is_hair_colour_optional ? $this->attributes['hair_colour_zh'] : '';
        $parameters_zh .= $product->is_hair_density_optional ? $this->attributes['hair_density_zh'] : '';
        return $parameters_zh;
    }*/

    /*public function getParametersEnAttribute()
    {
        $product = $this->product;
        $parameters_en = '';
        $parameters_en .= $product->is_base_size_optional ? $this->attributes['base_size_en'] : '';
        $parameters_en .= $product->is_hair_colour_optional ? ' - ' . $this->attributes['hair_colour_en'] : '';
        $parameters_en .= $product->is_hair_density_optional ? ' - ' . $this->attributes['hair_density_en'] : '';
        return $parameters_en;
    }*/

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

    public function getAttrValueCompositionAttribute()
    {
        $attr_values = $this->attr_values()->pluck('value')->toArray();
        return implode(' - ', $attr_values);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attr_values()
    {
        return $this->hasMany(ProductSkuAttrValue::class)->orderByDesc('sort');
    }
}
