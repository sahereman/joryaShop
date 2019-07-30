<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryProvince extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'parent_id', 'type', 'name_zh', 'name_en', 'code', 'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [

    ];

    public $timestamps = false;


    public function getNameAttribute($value)
    {
        return $this->attributes['name_en'] . ' - ' . $this->attributes['name_zh'];
    }

    public function country_pluck()
    {
        $country = $this->where('type', 'country')->get();

        return $country->pluck('name', 'id');
    }

    public function province_pluck($template_id = null, $plan_id = null)
    {
        $country_ids = collect();

        if ($template_id && $plan_id) {
            $template = ShipmentTemplate::find($template_id);
            $country_ids = $country_ids->merge($template->free_provinces->pluck('id'));

            foreach ($template->plans as $item) {
                if ($item->id != $plan_id) {
                    $country_ids = $country_ids->merge($item->country_provinces->pluck('id'));
                }
            }

            $country_ids = $country_ids->unique();

            $province = $this->with('parent')->where('type', 'province')->whereNotIn('id', $country_ids)->get();

        } else if ($template_id) {
            $template = ShipmentTemplate::find($template_id);
            $country_ids = $country_ids->merge($template->free_provinces->pluck('id'));

            foreach ($template->plans as $item) {
                $country_ids = $country_ids->merge($item->country_provinces->pluck('id'));
            }
            $country_ids = $country_ids->unique();

            $province = $this->with('parent')->where('type', 'province')->whereNotIn('id', $country_ids)->get();

        } else {
            $province = $this->with('parent')->where('type', 'province')->get();

        }

        $province->transform(function ($item) {
            $item->nameAll = $item->parent->name_en . ' - ' . $item->name_en;
            return $item;
        });

        return $province->pluck('nameAll', 'id');
    }

    /* Eloquent Relationships */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
