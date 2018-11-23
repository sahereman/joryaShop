<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShipmentCompany extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'sort',
    ];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    public static $cache_key;

    protected static $cache_expire_in_minutes = 1440;

    protected static function boot()
    {
        parent::boot();

        self::$cache_key = config('app.name') . '_shipment_companies';
    }

    /**
     * 获取所有物流公司 (缓存)
     * @return mixed
     */
    public static function shipmentCompanies()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 country_codes 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$cache_key, self::$cache_expire_in_minutes, function () {
            return self::all();
        });
    }

    /**
     * 通过快递公司code 获得快递公司name
     */
    public static function codeTransformName($code)
    {
        $shipmentCompanies = self::shipmentCompanies()->pluck('name', 'code');
        return isset($shipmentCompanies[$code]) ? $shipmentCompanies[$code] : $code;
    }
}
