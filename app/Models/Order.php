<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    /*
     * To enable soft deletes for a model,
     * use the Illuminate\Database\Eloquent\SoftDeletes trait on the model,
     * and add the deleted_at column to your $dates property.
     */
    use SoftDeletes;

    const ORDER_STATUS_PAYING = 'paying';
    const ORDER_STATUS_CLOSED = 'closed';
    const ORDER_STATUS_SHIPPING = 'shipping';
    const ORDER_STATUS_RECEIVING = 'receiving';
    const ORDER_STATUS_REFUNDING = 'refunding';
    const ORDER_STATUS_COMPLETED = 'completed';
    const ORDER_STATUS_UNCOMMENTED = 'uncommented';

    public static $orderStatusMap = [
        self::ORDER_STATUS_PAYING => '待付款',
        self::ORDER_STATUS_CLOSED => '交易关闭',
        self::ORDER_STATUS_SHIPPING => '待发货',
        self::ORDER_STATUS_RECEIVING => '待收货',
        self::ORDER_STATUS_REFUNDING => '售后',
        self::ORDER_STATUS_COMPLETED => '已完成',
        self::ORDER_STATUS_UNCOMMENTED => '待评价',
    ];

    const PAYMENT_METHOD_ALIPAY = 'alipay';
    const PAYMENT_METHOD_WECHAT = 'wechat';
    const PAYMENT_METHOD_PAYPAL = 'paypal';

    public static $paymentMethodMap = [
        self::PAYMENT_METHOD_ALIPAY => '支付宝',
        self::PAYMENT_METHOD_WECHAT => '微信',
        self::PAYMENT_METHOD_PAYPAL => 'PayPal',
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'order_sn',
        'user_id',
        'user_info',
        'status',
        'currency',
        'payment_method',
        'payment_sn',
        'shipment_company',
        'shipment_sn',
        'snapshot',
        'total_shipping_fee',
        'total_amount',
        'remark',
        'paid_at',
        'closed_at',
        'to_be_closed_at',
        'shipped_at',
        'completed_at',
        'to_be_completed_at',
        'commented_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'user_info' => 'json',
        'snapshot' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'paid_at',
        'to_be_closed_at',
        'closed_at',
        'shipped_at',
        'to_be_completed_at',
        'completed_at',
        'commented_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 order_sn 字段为空
            if (!$model->order_sn) {
                // 调用 generateOrderSn 生成订单流水号
                $model->order_sn = static::generateOrderSn();
                // 如果生成失败，则终止创建订单
                if (!$model->order_sn) {
                    return false;
                }
            }
        });
    }

    //  生成订单流水号
    public static function generateOrderSn()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $order_sn = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('order_sn', $order_sn)->exists()) {
                return $order_sn;
            }
        }
        Log::error('generating order sn failed');
        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function refund()
    {
        return $this->hasOne(OrderRefund::class);
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function translateStatus($status)
    {
        return self::$orderStatusMap[$status];
    }

    public function translatePaymentMethod($paymentMethod)
    {
        return self::$paymentMethodMap[$paymentMethod];
    }

    public static function getSecondsToCloseOrder()
    {
        return (integer)(Config::config('time_to_close_order')) * 60;
    }

    public static function getSecondsToCompleteOrder()
    {
        return (integer)(Config::config('time_to_complete_order')) * 3600 * 24;
    }
}
