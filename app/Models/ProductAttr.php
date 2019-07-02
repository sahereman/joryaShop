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
        'sort'
    ];

    /* Accessors */
    public function getProtoAttrAttribute()
    {
        return Attr::where(['name' => $this->attributes['name']])->first();
    }

    public function getValueOptionsAttribute()
    {
        $proto_attr = $this->getProtoAttrAttribute();
        if ($proto_attr) {
            return $proto_attr->values;
        }
        return $this->values();
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
