<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use ModelTree, AdminBuilder;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        /*初始化Tree属性*/
        // $this->setTitleColumn('name_zh');
        $this->setTitleColumn('name_en');
        $this->setOrderColumn('sort');
    }

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name_en',
        'name_zh',
        'slug',
        'icon',
        'link',
        'sort',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        // 'icon', // 备用字段
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    public static $pc_cache_key;
    public static $sub_pc_cache_key;
    public static $mobile_cache_key;
    // Cache 生命周期: 24小时
    protected static $cache_expire_in_minutes = 1440;

    // PC Menu
    public static function pcMenus()
    {
        self::$pc_cache_key = config('app.name') . '_pc_menus';
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$pc_cache_key, self::$cache_expire_in_minutes, function () {
            $pc_menus = self::where([
                'slug' => 'pc',
                'parent_id' => 0,
            ])->orderBy('sort')->with('children.children')->get();
            return $pc_menus;
        });
    }

    // Mobile Menu
    public static function mobileMenus()
    {
        self::$mobile_cache_key = config('app.name') . '_mobile_menus';
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$mobile_cache_key, self::$cache_expire_in_minutes, function () {
            return Menu::where('slug', 'mobile')->orderByDesc('sort')->get();
        });
    }

    public static function subPcMenus()
    {
        self::$sub_pc_cache_key = config('app.name') . '_sub_pc_menus';
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$sub_pc_cache_key, self::$cache_expire_in_minutes, function () {
            $pc_menus = self::where([
                'slug' => 'sub_pc',
                'parent_id' => 0,
            ])->orderBy('sort')->get();
            return $pc_menus;
        });
    }

    /* Eloquent Relationships */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('sort');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
