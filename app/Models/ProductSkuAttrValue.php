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
        'abbr',
        'photo',
        'sort'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name',
        'photo_url'
    ];

    /* Accessors */
    public function getNameAttribute()
    {
        return $this->attr->name;
    }

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

    /* Mutators */
    public function setNameAttribute($value)
    {
        unset($this->attributes['name']);
    }

    public function setPhotoUrlAttribute($value)
    {
        unset($this->attributes['photo_url']);
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
