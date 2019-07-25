<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTemplatePlan extends Model
{
    protected $fillable = [
        'shipment_template_id', 'base_unit', 'base_price', 'join_price'
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


    //    public function getAaaAttribute($value)
    //    {
    //        return [1,21,32];
    ////        $attr_value_options = [];
    ////        /*ProductSkuAttrValue::where('product_sku_id', $this->attributes['id'])->orderByDesc('sort')->each(function (ProductSkuAttrValue $attrValue) use (&$attr_value_options) {
    ////            $attr_value_options[$attrValue->product_attr_id] = $attrValue->toArray();
    ////        });*/
    ////        $this->attr_values()->each(function (ProductSkuAttrValue $attrValue) use (&$attr_value_options) {
    ////            $attr_value_options[$attrValue->product_attr_id] = $attrValue->toArray();
    ////        });
    ////        return $attr_value_options;
    //    }
    //
    //    public function setAaaAttribute($value)
    //    {
    //        unset($this->attributes['aaa']);
    //    }

    public function shipment_template()
    {
        return $this->belongsTo(ShipmentTemplate::class);
    }

    public function country_provinces()
    {
        return $this->belongsToMany(CountryProvince::class, 'shipment_template_plan_provinces', 'plan_id');
    }
}
