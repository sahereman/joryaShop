<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomAttrValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'custom_attr_id',
        'value',
        'delta_price',
        'photo',
        'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        //
    ];

    /* Accessors */
    public function getAttrNameAttribute()
    {
        $attr = CustomAttr::find($this->attributes['custom_attr_id']);
        return $attr->name;
    }

    public function getAttrTypeAttribute()
    {
        $attr = CustomAttr::find($this->attributes['custom_attr_id']);
        return $attr->type;
    }

    /* Mutators */
    public function setAttrNameAttribute($value)
    {
        unset($this->attributes['attr_name']);
    }

    public function setAttrTypeAttribute($value)
    {
        unset($this->attributes['attr_type']);
    }

    /* Eloquent Relationships */
    public function attr()
    {
        return $this->belongsTo(CustomAttr::class, 'custom_attr_id');
    }
}
