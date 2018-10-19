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

    protected $orderStatusMap = [
        self::ORDER_STATUS_PAYING => '待付款',
        self::ORDER_STATUS_CLOSED => '已取消',
        self::ORDER_STATUS_SHIPPING => '待发货',
        self::ORDER_STATUS_RECEIVING => '待收货',
        self::ORDER_STATUS_REFUNDING => '售后',
        self::ORDER_STATUS_COMPLETED => '已完成',
        self::ORDER_STATUS_UNCOMMENTED => '待评价',
    ];

    const PAYMENT_METHOD_ALIPAY = 'alipay';
    const PAYMENT_METHOD_WECHAT = 'wechat';
    const PAYMENT_METHOD_PAYPAL = 'paypal';

    protected $paymentMethodMap = [
        self::PAYMENT_METHOD_ALIPAY => '支付宝',
        self::PAYMENT_METHOD_WECHAT => '微信',
        self::PAYMENT_METHOD_PAYPAL => 'PAYPAL',
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'order_sn',
        'currency',
        'payment_method',
        'payment_sn',
        'shipment_company',
        'shipment_sn',
        'total_shipping_fee',
        'total_amount',
        'remark',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'user_info' => 'array',
        'snapshot' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'paid_at',
        'closed_at',
        'shipped_at',
        'completed_at',
        'commented_at',
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
            $orderSn = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('order_sn', $orderSn)->exists()) {
                return $orderSn;
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

    public function translateStatus($status)
    {
        return $this->orderStatusMap[$status];
    }

    public function translatePaymentMethod($paymentMethod)
    {
        return $this->paymentMethodMap[$paymentMethod];
    }
}
