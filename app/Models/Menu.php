<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name_en',
        'name_zh',
        'slug',
        'icon',
        'link',
        'sort',
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    public static $pc_cache_key;
    public static $mobile_cache_key;
    // Cache 生命周期: 24小时
    protected static $cache_expire_in_minutes = 1440;

    protected static function boot()
    {
        parent::boot();

        self::$pc_cache_key = config('app.name') . '_pc_menus';
        self::$mobile_cache_key = config('app.name') . '_mobile_menus';
    }

    // PC Menu
    public static function pcMenus(){
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$pc_cache_key, self::$cache_expire_in_minutes, function () {
            return Menu::where('slug', 'pc')->get();
        });
    }

    // Mobile Menu
    public static function mobileMenus(){
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$mobile_cache_key, self::$cache_expire_in_minutes, function () {
            return Menu::where('slug', 'mobile')->get();
        });
    }
}
