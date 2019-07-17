<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    const COUPON_TYPE_DISCOUNT = 'discount';
    const COUPON_TYPE_REDUCTION = 'reduction';

    public static $couponTypeMap = [
        self::COUPON_TYPE_DISCOUNT => '折扣',
        self::COUPON_TYPE_REDUCTION => '满减'
    ];

    const COUPON_SCENARIO_PAGE = 'page';
    const COUPON_SCENARIO_ADMIN = 'admin';
    const COUPON_SCENARIO_REGISTER = 'register';

    public static $couponScenarioMap = [
        self::COUPON_SCENARIO_PAGE => '用户页面领取',
        self::COUPON_SCENARIO_ADMIN => '系统后台发放',
        self::COUPON_SCENARIO_REGISTER => '新用户注册'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'discount',
        'reduction',
        'threshold',
        'number',
        'allowance',
        'supported_product_types',
        'scenario',
        'sort',
        'started_at',
        'stopped_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'supported_product_types' => 'json'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at',
        'stopped_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'supported_product_type_string',
        // 'is_limited',
        'period',
        'status'
    ];

    /* Accessors */
    public function getSupportedProductTypeStringAttribute()
    {
        $supported_product_type_string = '';
        $supported_product_types = json_decode($this->attributes['supported_product_types'], true);
        foreach ($supported_product_types as $supported_product_type) {
            $supported_product_type_string .= Product::$productTypeMap[$supported_product_type] . ' | ';
        }
        return substr($supported_product_type_string, 0, -3);
    }

    public function getIsLimitedAttribute()
    {
        return !is_null($this->attributes['number']);
    }

    public function getPeriodAttribute()
    {
        return "{$this->attributes['started_at']}  至  {$this->attributes['stopped_at']}";
    }

    public function getStatusAttribute()
    {
        $now = Carbon::now()->getTimestamp();
        $started_at = Carbon::make($this->attributes['started_at'])->getTimestamp();
        $stopped_at = Carbon::make($this->attributes['stopped_at'])->getTimestamp();
        if ($now < $started_at) {
            return '尚未启用';
        } else if ($now > $stopped_at) {
            return '已过期';
        } else {
            return '已启用';
        }
    }

    /* Mutators */
    public function setSupportedProductTypeStringAttribute($value)
    {
        unset($this->attributes['supported_product_type_string']);
    }

    public function setIsLimitedAttribute($value)
    {
        unset($this->attributes['number']);
    }

    public function setPeriodAttribute($value)
    {
        unset($this->attributes['period']);
    }

    public function setStatusAttribute($value)
    {
        unset($this->attributes['status']);
    }

    /* Eloquent Relationships */
    public function users()
    {
        return $this->hasMany(UserCoupon::class);
    }
}
