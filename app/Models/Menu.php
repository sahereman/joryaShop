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
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        'icon', // 备用字段
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
    public static function pcMenus()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$pc_cache_key, self::$cache_expire_in_minutes, function () {
            $pc_menus = self::where('slug', 'pc')->orderBy('sort')->get();
            foreach ($pc_menus as $key => &$pc_menu) {
                if (preg_match('/product_categories\/(\d+)$/', $pc_menu['link'], $matches)) {
                    $category = ProductCategory::find($matches[1]);
                    if ($category) {
                        $children = $category->children;
                        if ($children) {
                            foreach ($children as $i => &$child) {
                                $children[$i] = [
                                    'name_en' => $child->name_en,
                                    'name_zh' => $child->name_zh,
                                    'icon' => '',
                                    'slug' => 'pc',
                                    'link' => route('product_categories.index', ['category' => $child->id]),
                                    'sort' => $pc_menu->sort,
                                ];
                            }
                            $pc_menus[$key] = [
                                'parent' => $pc_menu,
                                'children' => $children,
                            ];
                        }
                    }
                }
            }
            return $pc_menus;
        });
    }

    // Mobile Menu
    public static function mobileMenus()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 menus 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$mobile_cache_key, self::$cache_expire_in_minutes, function () {
            return Menu::where('slug', 'mobile')->orderBy('sort')->get();
        });
    }
}
