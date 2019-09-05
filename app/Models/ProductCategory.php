<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use ModelTree, AdminBuilder;

    use Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name_en'
            ]
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        /*初始化Tree属性*/
        // $this->setTitleColumn('name_zh');
        $this->setTitleColumn('name_en');
        $this->setOrderColumn('sort');
    }

    private $collection_products;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'slug',
        'parent_id',
        // 'banner', // 备用字段
        'name_zh',
        'name_en',
        'description_en',
        'description_zh',
        'content_en',
        'content_zh',

        /* 2019-04-09 for SEO */
        'seo_titles',
        'seo_keywords',
        'seo_description',
        /* 2019-04-09 for SEO */

        'is_index',
        'sort',
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
        'is_index' => 'boolean',
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
        'banner_url',
    ];

    public static $cache_key;
    // Cache 生命周期: 24小时
    protected static $cache_expire_in_minutes = 1440;

    public static function categories()
    {
        self::$cache_key = config('app.name') . '_categories';
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 categories 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$cache_key, self::$cache_expire_in_minutes, function () {
            $categories = self::where([
                'parent_id' => 0,
            ])->orderBy('sort')->with('children')->get();
            $categories->each(function (ProductCategory $category) {
                $category->children = $category->children()->orderBy('sort')->get();
            });
            return $categories;
        });
    }

    /* Accessors */
    public function getBannerUrlAttribute()
    {
        if ($this->attributes['banner']) {
            // 如果 banner 字段本身就已经是完整的 url 就直接返回
            if (Str::startsWith($this->attributes['banner'], ['http://', 'https://'])) {
                return $this->attributes['banner'];
            }
            return Storage::disk('public')->url($this->attributes['banner']);
        }
        return '';
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

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * 获取包含所有子集分类下商品集合(包含自身分类下商品)
     * @param string $sort
     * @return mixed
     */
    public function all_products($sort = 'index')
    {
        $this->collection_products = $this->products()->where('on_sale', true)->get();

        $children = $this->children;
        $this->findChildrenProduct($children);

        return $this->collection_products->sortByDesc($sort);
    }

    private function findChildrenProduct($children)
    {
        $children->map(function ($item) {
            $cl = $item->children;
            if ($cl->isNotEmpty()) {
                $this->findChildrenProduct($cl);
            }
            $this->collection_products = $this->collection_products->merge($item->products()->where('on_sale', true)->get());
        });
    }
}
