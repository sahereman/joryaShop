<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTemplateFreeProvince extends Model
{
    protected $fillable = [
        'shipment_template_id', 'country_province_id'
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
        //
    ];

    public $timestamps = false;
}
