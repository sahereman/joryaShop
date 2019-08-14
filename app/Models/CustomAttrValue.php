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

    /* Mutators */

    /* Eloquent Relationships */
    public function attr()
    {
        return $this->belongsTo(CustomAttr::class);
    }
}
