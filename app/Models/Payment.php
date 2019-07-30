<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    /*
     * To enable soft deletes for a model,
     * use the Illuminate\Database\Eloquent\SoftDeletes trait on the model,
     * and add the deleted_at column to your $dates property.
     */
    use SoftDeletes;

    const PAYMENT_METHOD_ALIPAY = 'alipay';
    const PAYMENT_METHOD_WECHAT = 'wechat';
    const PAYMENT_METHOD_PAYPAL = 'paypal';

    public static $paymentMethodMap = [
        self::PAYMENT_METHOD_ALIPAY => '支付宝',
        self::PAYMENT_METHOD_WECHAT => '微信',
        self::PAYMENT_METHOD_PAYPAL => 'PayPal'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sn',
        'user_id',
        'currency',
        'amount',
        'rate',
        'method',
        'payment_sn',
        'paid_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 sn 字段为空
            if (!$model->sn) {
                // 调用 generateSn 生成支付序列号
                $model->sn = static::generateSn();
                // 如果生成失败，则终止创建订单
                if (!$model->sn) {
                    return false;
                }
            }
        });
    }

    //  生成支付序列号
    public static function generateSn()
    {
        // 支付序列号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $sn = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('sn', $sn)->exists()) {
                return $sn;
            }
        }
        Log::error('Generating Sn Failed');
        return false;
    }

    /* Accessors */
    public function getPaymentAmountAttribute()
    {
        return bcmul($this->attributes['amount'], $this->attributes['rate'], 2);
    }

    /* Mutators */
    public function setPaymentAmountAttribute($value)
    {
        unset($this->attributes['payment_amount']);
    }

    /* Eloquent Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
