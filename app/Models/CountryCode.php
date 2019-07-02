<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CountryCode extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'country_name',
        // 'country_name_zh',
        'country_iso', // 备用字段
        'country_code',
        'sort',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        'country_iso', // 备用字段
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    public static $cache_key;

    protected static $cache_expire_in_minutes = 1440;//24小时

    protected static function boot()
    {
        parent::boot();

        self::$cache_key = config('app.name') . '_country_codes';
    }

    public static function countryCodes()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 country_codes 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$cache_key, self::$cache_expire_in_minutes, function () {
            // return self::all();
            return self::orderByDesc('sort')->get();
        });
    }
}
