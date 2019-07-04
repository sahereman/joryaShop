<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name_en'
            ]
        ];
    }


    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'product_category_id',
        'name_en',
        'name_zh',
        'description_en',
        'description_zh',
        'content_en',
        'content_zh',

        /* 2019-04-09 for SEO */
        'seo_titles',
        'seo_keywords',
        'seo_description',
        /* 2019-04-09 for SEO */

        'location',
        'service',

        'thumb',
        'photos',

        'shipping_fee',
        'stock',
        'sales',
        'index',
        'heat',
        'price',
        'is_index',
        'on_sale',
    ];


    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        'content_en',
        'content_zh',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'is_index' => 'boolean',
        'on_sale' => 'boolean',
        'photos' => 'json'
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
        'thumb_url',
        'photo_urls',
        // 'attr_names',
        // 'grouped_param_values',
        // 'grouped_param_value_string'
    ];

    /* Accessors */
    public function getThumbUrlAttribute()
    {
        if ($this->attributes['thumb']) {
            // 如果 thumb 字段本身就已经是完整的 url 就直接返回
            /*if (Str::startsWith($this->attributes['thumb'], ['http://', 'https://'])) {
                return $this->attributes['thumb'];
            }
            return Storage::disk('public')->url($this->attributes['thumb']);*/
            return generate_image_url($this->attributes['thumb'], 'public');
        }
        return '';
    }

    public function getPhotoUrlsAttribute()
    {
        $photo_urls = [];
        if ($this->attributes['photos']) {
            $photos = json_decode($this->attributes['photos'], true);
            if (count($photos) > 0) {
                foreach ($photos as $photo) {
                    /*if (Str::startsWith($photo, ['http://', 'https://'])) {
                        $photo_urls[] = $photo;
                    }
                    $photo_urls[] = Storage::disk('public')->url($photo);*/
                    $photo_urls[] = generate_image_url($photo, 'public');
                }
            }
        }
        return $photo_urls;
    }

    public function getAttrNamesAttribute()
    {
        $attr_options = [];
        $this->attrs()->get(['name'])->each(function ($attr) use (&$attr_options) {
            $attr_options[$attr->name] = $attr->name;
        });

        return $attr_options;
    }

    public function getParamNamesAttribute()
    {
        return $this->params()->distinct()->get(['name'])->pluck('name')->toArray();
    }

    public function getGroupedParamValuesAttribute()
    {
        $grouped_param_values = [];
        $this->params()->get(['name', 'value'])->each(function (ProductParam $param) use (&$grouped_param_values) {
            $grouped_param_values[$param->name][$param->value] = $param->value;
        });
        return $grouped_param_values;
    }

    public function getGroupedParamValueStringAttribute()
    {
        $grouped_param_value_string = [];
        $this->params()->get(['name', 'value'])->each(function (ProductParam $param) use (&$grouped_param_value_string) {
            if (isset($grouped_param_value_string[$param->name])) {
                $grouped_param_value_string[$param->name] .= ' . ' . $param->value;
            } else {
                $grouped_param_value_string[$param->name] = $param->value;
            }
        });
        return $grouped_param_value_string;
    }

    /* Mutators */
    public function setThumbUrlAttribute($value)
    {
        unset($this->attributes['thumb_url']);
    }

    public function setPhotoUrlsAttribute($value)
    {
        unset($this->attributes['photo_urls']);
    }

    public function setAttrNamesAttribute($value)
    {
        unset($this->attributes['attr_names']);
    }

    public function setGroupedParamValuesAttribute($value)
    {
        unset($this->attributes['grouped_param_values']);
    }

    public function setGroupedParamValueStringAttribute($value)
    {
        unset($this->attributes['grouped_param_value_string']);
    }

    /* Eloquent Relationships */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function attrs()
    {
        return $this->hasMany(ProductAttr::class)->orderByDesc('sort');
    }

    public function params()
    {
        return $this->hasMany(ProductParam::class)->orderByDesc('sort');
    }
}
