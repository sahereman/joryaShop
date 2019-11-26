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
        'parent_id',
        'type',
        'name_zh',
        'name_en',
        'code',
        'sort'
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
        'full_name'
    ];

    public $timestamps = false;

    // Accessors
    public function getNameAttribute()
    {
        return $this->attributes['name_en'] . ' - ' . $this->attributes['name_zh'];
    }

    public function getFullNameAttribute()
    {
        if ($this->attributes['parent_id'] == 0) {
            $full_name = $this->attributes['name_en'];
        } else {
            $child_country_province = $this;
            // $parent_country_province = $this;
            $full_name = $this->attributes['name_en'];
            while ($child_country_province->parent_id != 0) {
                $parent_country_province = $child_country_province->parent;
                if ($parent_country_province->name_en != $child_country_province->name_en) {
                    $full_name = $parent_country_province->name_en . ' - ' . $full_name;
                }
                $child_country_province = $parent_country_province;
            }
        }
        return $full_name;
    }

    public function country_options()
    {
        $country = $this->where('type', 'country')->get();

        return $country->pluck('name', 'id');
    }

    public function province_options($template_id = null, $plan_id = null)
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
            $item->full_name = $item->parent->name_en . ' - ' . $item->name_en;
            return $item;
        });

        return $province->pluck('full_name', 'id');
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
