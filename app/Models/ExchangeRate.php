<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ExchangeRate extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'currency',
        'rate',
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

        self::$cache_key = config('app.name') . '_exchange_rates';
    }

    public static function exchangeRates()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出 country_codes 表中所有的数据，返回的同时做了缓存。
        return Cache::remember(self::$cache_key, self::$cache_expire_in_minutes, function () {
            return self::all();
        });
    }

    public static function exchangePriceByCurrency($price, $currency = 'USD')
    {
        $currencies = self::exchangeRates()->pluck('currency')->toArray();
        $rates = self::exchangeRates()->keyBy('currency')->toArray();
        if(in_array($currency, $currencies)){
            $price = bcmul($price, $rates[$currency]['rate'], 2);
        }
        return $price;
    }

    public static function exchangePrice($price, $from_currency = 'USD', $to_currency = 'CNY')
    {
        $currencies = self::exchangeRates()->pluck('currency')->toArray();
        $rates = self::exchangeRates()->keyBy('currency')->toArray();
        if(in_array($from_currency, $currencies)){
            $price = bcdiv($price, $rates[$from_currency]['rate'], 2);
        }
        return self::exchangePriceByCurrency($price, $to_currency);
    }
}
