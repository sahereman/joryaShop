<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ExchangeRate extends Model
{
    /*汇率基准: 美元(USD) $1.00*/
    const USD = 'USD';
    const AUD = 'AUD';
    const GBP = 'GBP';
    const CAD = 'CAD';
    const EUR = 'EUR';
    const RUB = 'RUB';
    //    const CNY = 'CNY';
    //    const HKD = 'HKD';
    //    const JPY = 'JPY';
    //    const KRW = 'KRW';
    //    const TWD = 'TWD';

    /**
     * Reference: https://www.toptal.com/designers/htmlarrows/currency/
     * Reference: http://www.runoob.com/charsets/ref-utf-currency.html
     */
    public static $symbolMap = [
        self::USD => '&#36;', // '&dollar;'
        self::AUD => '&#36;', // '&dollar;'
        self::GBP => '&#163;', // '&pound;'
        self::CAD => '&#36;', // '&dollar;'
        self::EUR => '&#8364;', // '&euro;'
        self::RUB => '&#8381;' ,
//        self::CNY => '&#165;', // '&yen;'
//        self::HKD => '&#36;', // '&dollar;'
//        self::JPY => '&#20870;', // '&#165;' or '&yen;'
//        self::KRW => '&#8361;', // '&#50896;'
//        self::TWD => '&#36;', // '&dollar;'
    ];

    /*public static $currencyMap = [
        self::USD => '美元 US Dollar . USD',
        self::AUD => '澳元 Australian Dollar . AUD',
        self::CAD => '加元 Canadian Dollar . CAD',
        self::CNY => '人民币 Chinese Yuan Renminbi . CNY',
        self::EUR => '欧元 Euro . EUR',
        self::GBP => '英镑 British Pound . GBP',
        self::HKD => '港元 Hong Kong Dollar . HKD',
        self::JPY => '日元 Japanese Yen . JPY',
        self::KRW => '韩元 South-Korean Won . KRW',
        self::TWD => '台币 Taiwan Dollar . TWD',
    ];*/

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

    public static function exchangePrice($price, string $to_currency = 'CNY', string $from_currency = 'USD')
    {
        if ($price == 0.00)
        {
            return 0.00;
        }
        $currencies = self::exchangeRates()->pluck('currency')->toArray();
        $exchange_rates = self::exchangeRates()->keyBy('currency')->toArray();
        if ($to_currency !== 'USD' && in_array($to_currency, $currencies))
        {
            $price = bcmul($price, $exchange_rates[$to_currency]['rate'], 2);
            $price = ($price == 0.00) ? 0.01 : $price;
        }
        if ($from_currency !== 'USD' && in_array($from_currency, $currencies))
        {
            $price = bcdiv($price, $exchange_rates[$from_currency]['rate'], 2);
            $price = ($price == 0.00) ? 0.01 : $price;
        }
        return $price;
    }
}
