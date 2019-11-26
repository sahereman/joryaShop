<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShipmentTemplate extends Model
{
    protected $fillable = [
        'name',
        'sub_name',
        'description',
        'from_country_id',
        'min_days',
        'max_days'
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

    /*计算运费*/
    public function calc_unit_shipping_fee($unit, $to_province)
    {
        if (!$this->exists) {
            throw new \Exception('Template Not Find');
        }

        $fee = 0;

        $en_free_province = $this->free_provinces->where('name_en', $to_province);
        $zh_free_province = $this->free_provinces->where('name_zh', $to_province);

        if ($en_free_province->isNotEmpty() || $zh_free_province->isNotEmpty()) {
            return $fee;
        }

        foreach ($this->plans as $plan) {
            $en_plan_province = $plan->country_provinces->where('name_en', $to_province);
            $zh_plan_province = $plan->country_provinces->where('name_zh', $to_province);

            if ($en_plan_province->isNotEmpty() || $zh_plan_province->isNotEmpty()) {
                if ($unit <= $plan->base_unit) {
                    $fee = $plan->base_price;
                } else {
                    $num = $unit - $plan->base_unit;
                    $fee = bcadd($plan->base_price, bcmul($num, $plan->join_price, 2), 2);
                }
                break;
            }
        }

        // return get_global_symbol(). get_current_price($fee);
        return get_current_price($fee);
    }

    /* Accessors */
    public function getFullNameAttribute()
    {
        return $this->attributes['name'] . ' - ' . $this->attributes['sub_name'];
    }

    /* Mutators */
    public function setFullNameAttribute($value)
    {
        unset($this->attributes['full_name']);
    }

    /* Eloquent Relationships */
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
