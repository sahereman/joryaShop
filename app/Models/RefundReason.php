<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RefundReason extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'reason_en',
        'reason_zh',
        'sort',
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    public static $cache_key;
    // Cache 生命周期: 24小时
    protected static $cache_expire_in_minutes = 1440;

    protected static function boot()
    {
        parent::boot();

        self::$cache_key = config('app.name') . '_refund_reasons';
    }

    public static function refundReasons()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 country_codes 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$cache_key, self::$cache_expire_in_minutes, function () {
            // return self::all();
            return self::orderByDesc('sort')->get();
        });
    }
}
