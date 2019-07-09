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

        'photo',
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
        // 'product_name',
        // 'attr_value_string',
        // 'attr_value_options'
    ];

    /* Accessors */
    public function getPhotoUrlAttribute()
    {
        if ($this->attributes['photo']) {
            // 如果 photo 字段本身就已经是完整的 url 就直接返回
            /*if (Str::startsWith($this->attributes['photo'], ['http://', 'https://'])) {
                return $this->attributes['photo'];
            }
            return Storage::disk('public')->url($this->attributes['photo']);*/
            return generate_image_url($this->attributes['photo'], 'public');
        }
        return '';
    }

    /*public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }*/

    public function getAttrValueStringAttribute()
    {
        $attr_value_string = '';
        /*ProductSkuAttrValue::where('product_sku_id', $this->attributes['id'])->with('attr')->orderByDesc('sort')->each(function (ProductSkuAttrValue $attrValue) use (&$attr_value_string) {
            $attr_value_string .= $attrValue->attr->name . ' (' . $attrValue->value . ') ; ';
        });*/
        $this->attr_values()->with('attr')->each(function (ProductSkuAttrValue $attrValue) use (&$attr_value_string) {
            $attr_value_string .= $attrValue->attr->name . ' (' . $attrValue->value . ') ; ';
        });
        return substr($attr_value_string, 0, -3);
    }

    public function getAttrValueOptionsAttribute()
    {
        $attr_value_options = [];
        /*ProductSkuAttrValue::where('product_sku_id', $this->attributes['id'])->orderByDesc('sort')->each(function (ProductSkuAttrValue $attrValue) use (&$attr_value_options) {
            $attr_value_options[$attrValue->product_attr_id] = $attrValue->toArray();
        });*/
        $this->attr_values()->each(function (ProductSkuAttrValue $attrValue) use (&$attr_value_options) {
            $attr_value_options[$attrValue->product_attr_id] = $attrValue->toArray();
        });
        return $attr_value_options;
    }

    /* Mutators */
    public function setPhotoUrlAttribute($value)
    {
        unset($this->attributes['photo_url']);
    }

    /*public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }*/

    public function setAttrValueStringAttribute($value)
    {
        unset($this->attributes['attr_value_string']);
    }

    public function setAttrValueOptionsAttribute($value)
    {
        unset($this->attributes['attr_value_options']);
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
