<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTemplate extends Model
{
    protected $fillable = [

        'name', 'sub_name', 'description', 'from_country_id', 'min_days', 'max_days'
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


    public function from_country()
    {
        return $this->belongsTo(CountryProvince::class);
    }


    public function plans()
    {
        return $this->hasMany(ShipmentTemplatePlan::class);
    }

    public function free_provinces()
    {
        return $this->belongsToMany(CountryProvince::class, 'shipment_template_free_provinces', 'shipment_template_id');
    }
}
