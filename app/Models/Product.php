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
        'attr_names',
        'grouped_params'
    ];

    /* Accessors */
    public function getThumbUrlAttribute()
    {
        if ($this->attributes['thumb']) {
            // 如果 thumb 字段本身就已经是完整的 url 就直接返回
            if (Str::startsWith($this->attributes['thumb'], ['http://', 'https://'])) {
                return $this->attributes['thumb'];
            }
            return Storage::disk('public')->url($this->attributes['thumb']);
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
        $this->attrs->each(function ($attr) use (&$attr_options) {
            $attr_options[$attr->name] = $attr->name;
        });

        return $attr_options;
    }

    public function getParamNamesAttribute()
    {
        return $this->params->unique('name')->pluck('name')->toArray();
    }

    public function getGroupedParamsAttribute()
    {
        $grouped_params = [];
        $this->params()->each(function (ProductParam $param) use (&$grouped_params) {
            $grouped_params[$param->name][$param->value] = $param->value;
        });
        return $grouped_params;
    }

    public function getGroupedParamValuesAttribute()
    {
        $grouped_param_values = [];
        $this->getGroupedParamsAttribute()->each(function ($params, $name) use (&$grouped_param_values) {
            foreach ($params as $param) {
                if (isset($grouped_param_values[$name])) {
                    $grouped_param_values[$name] .= ' . ' . $param['value'];
                } else {
                    $grouped_param_values[$name] = $param['value'];
                }
            }
        });
        return $grouped_param_values;
    }

    /* Mutators */
    public function setAttrNamesAttribute($value)
    {
        unset($this->attributes['attr_names']);
    }

    public function setGroupedParamsAttribute()
    {
        unset($this->attributes['grouped_params']);
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
