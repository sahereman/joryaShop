<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttr extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'name',
        'has_photo',
        'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_photo' => 'boolean'
    ];

    /* Accessors */
    public function getProtoAttrAttribute()
    {
        if (Attr::where(['name' => $this->attributes['name']])->exists()) {
            return Attr::where(['name' => $this->attributes['name']])->first();
        } else {
            return Attr::create([
                'name' => $this->attributes['name'],
                'has_photo' => false
            ]);
        }
    }

    public function getValueOptionsAttribute()
    {
        $attr = Attr::where(['name' => $this->attributes['name']])->first();
        if ($attr) {
            return $attr->values;
        }
        return $this->values();
    }

    /* Mutators */
    public function setProtoAttrAttribute($value)
    {
        unset($this->attributes['proto_attr']);
    }

    public function setValueOptionsAttribute($value)
    {
        unset($this->attributes['value_options']);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function values()
    {
        return $this->hasMany(ProductSkuAttrValue::class)->orderByDesc('sort');
    }
}
