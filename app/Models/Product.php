<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
                'source' => 'slug_title'
            ]
        ];
    }

    const PRODUCT_TYPE_COMMON = 'common'; // 普通
    const PRODUCT_TYPE_PERIOD = 'period'; // 限时
    const PRODUCT_TYPE_AUCTION = 'auction'; // 拍卖
    const PRODUCT_TYPE_CUSTOM = 'custom'; // 定制
    const PRODUCT_TYPE_DUPLICATE = 'duplicate'; // 复制
    const PRODUCT_TYPE_REPAIR = 'repair'; // 修复
    const PRODUCT_TYPE_POINT = 'point'; // 积分
    // const PRODUCT_TYPE_DISCOUNT = 'discount'; // 优惠|备用

    public static $productTypeMap = [
        self::PRODUCT_TYPE_COMMON => '普通',
        self::PRODUCT_TYPE_PERIOD => '限时',
        self::PRODUCT_TYPE_AUCTION => '拍卖',
        self::PRODUCT_TYPE_CUSTOM => '定制',
        self::PRODUCT_TYPE_DUPLICATE => '复制',
        self::PRODUCT_TYPE_REPAIR => '修复',
        self::PRODUCT_TYPE_POINT => '积分',
        // self::PRODUCT_TYPE_DISCOUNT => '优惠', // 备用
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'slug',
        'slug_title',
        'product_category_id',
        'type',
        'name_en',
        'name_zh',
        'sub_name_en',
        'sub_name_zh',
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

        // 'shipping_fee',
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
        'comment_count',
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
            $grouped_param_values[str_replace(' ', '___', $param->name)][$param->value] = $param->value;
        });
        return $grouped_param_values;
    }

    public function getGroupedParamValueStringAttribute()
    {
        $grouped_param_value_string = [];
        $this->params()->get(['name', 'value'])->each(function (ProductParam $param) use (&$grouped_param_value_string) {
            if (isset($grouped_param_value_string[$param->name])) {
                $grouped_param_value_string[$param->name] .= ', ' . $param->value;
            } else {
                $grouped_param_value_string[$param->name] = $param->value;
            }
        });
        return $grouped_param_value_string;
    }

    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }

    /**
     * 获取当前地区可用的运费模板 集合
     * 获取商品运费步骤 : 1.调用此函数获取运费模板集合  2.调用模板集合下的calc_unit_shipping_fee方法,获取具体运费
     * @param $to_province
     * @return \Illuminate\Support\Collection|null
     * @throws \Exception
     */
    public function get_allow_shipment_templates($to_province)
    {
        if (!$this->exists) {
            throw new \Exception('Product Not Find');
        }

        $temps = $this->shipment_templates()->with(['free_provinces', 'plans', 'plans.country_provinces'])->get();

        if ($temps->isEmpty()) {
            return null;
        }

        $allow_temps = collect();
        foreach ($temps as $temp) {
            $en_free_province = $temp->free_provinces->where('name_en', $to_province);
            $zh_free_province = $temp->free_provinces->where('name_zh', $to_province);

            if ($en_free_province->isNotEmpty() || $zh_free_province->isNotEmpty()) {
                $allow_temps->prepend($temp);
            }

            foreach ($temp->plans as $plan) {
                $en_plan_province = $plan->country_provinces->where('name_en', $to_province);
                $zh_plan_province = $plan->country_provinces->where('name_zh', $to_province);

                if ($en_plan_province->isNotEmpty() || $zh_plan_province->isNotEmpty()) {
                    $allow_temps->push($temp);
                }
            }
        };

        if ($allow_temps->isEmpty()) {
            return null;
        }

        return $allow_temps;
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

    public function setCommentCountAttribute($value)
    {
        unset($this->attributes['comment_count']);
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

    public function period()
    {
        return $this->hasOne(PeriodProduct::class);
    }

    public function auction()
    {
        return $this->hasOne(AuctionProduct::class);
    }

    public function discounts()
    {
        return $this->hasMany(DiscountProduct::class);
    }

    public function shipment_templates()
    {
        return $this->belongsToMany(ShipmentTemplate::class, 'product_shipment_templates');
    }

    public function faqs()
    {
        return $this->hasMany(ProductFaq::class)->orderByDesc('sort');
    }
}
