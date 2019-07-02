<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class OrderRefund extends Model
{
    const ORDER_REFUND_TYPE_REFUND = 'refund';
    const ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT = 'refund_with_shipment';

    public static $orderRefundTypeMap = [
        self::ORDER_REFUND_TYPE_REFUND => '仅退款',
        self::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT => '退货并退款',
    ];

    const ORDER_REFUND_STATUS_CHECKING = 'checking';
    const ORDER_REFUND_STATUS_SHIPPING = 'shipping';
    const ORDER_REFUND_STATUS_RECEIVING = 'receiving';
    const ORDER_REFUND_STATUS_REFUNDED = 'refunded';
    const ORDER_REFUND_STATUS_DECLINED = 'declined';

    public static $orderRefundStatusMap = [
        self::ORDER_REFUND_STATUS_CHECKING => '待审核',
        self::ORDER_REFUND_STATUS_SHIPPING => '待发货',
        self::ORDER_REFUND_STATUS_RECEIVING => '待收货',
        self::ORDER_REFUND_STATUS_REFUNDED => '已退款',
        self::ORDER_REFUND_STATUS_DECLINED => '已拒绝',
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'refund_sn',
        'order_id',
        'seller_info',
        'type',
        'status',
        'amount', // 备用字段
        'remark_from_user',
        'remark_from_seller',
        'remark_for_shipment_from_user',
        'remark_for_shipment_from_seller',
        'shipment_sn',
        'shipment_company',
        'photos_for_refund',
        'photos_for_shipment',
        'checked_at',
        'shipped_at',
        'refunded_at',
        'to_be_declined_at',
        'declined_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        'amount', // 备用字段
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'seller_info' => 'json',
        'photos_for_refund' => 'json',
        'photos_for_shipment' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'checked_at',
        'shipped_at',
        'refunded_at',
        'to_be_declined_at',
        'declined_at',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'refund_photo_urls',
        'shipment_photo_urls',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 refund_sn 字段为空
            if (!$model->refund_sn) {
                // 调用 generateRefundSn 生成退款订单流水号
                $model->refund_sn = static::generateRefundSn();
                // 如果生成失败，则终止创建退款订单
                if (!$model->refund_sn) {
                    return false;
                }
            }
        });
    }

    public static function generateRefundSn()
    {
        do {
            // Uuid类可以用来生成大概率不重复的字符串
            $refund_sn = Uuid::uuid4()->getHex();
            // 为了避免重复我们在生成之后在数据库中查询看看是否已经存在相同的退款订单流水号
        } while (self::query()->where('refund_sn', $refund_sn)->exists());

        return $refund_sn;
    }

    /* Accessors */
    public function getRefundPhotoUrlsAttribute()
    {
        $refund_photo_urls = [];
        if ($this->attributes['photos_for_refund']) {
            $photos_for_refund = json_decode($this->attributes['photos_for_refund'], true);
            if (count($photos_for_refund) > 0) {
                foreach ($photos_for_refund as $photo_for_refund) {
                    /*if (Str::startsWith($photo_for_refund, ['http://', 'https://'])) {
                        $refund_photo_urls[] = $photo_for_refund;
                    }
                    $refund_photo_urls[] = Storage::disk('public')->url($photo_for_refund);*/
                    $refund_photo_urls[] = generate_image_url($photo_for_refund, 'public');
                }
            }
        }
        return $refund_photo_urls;
    }

    public function getShipmentPhotoUrlsAttribute()
    {
        $shipment_photo_urls = [];
        if ($this->attributes['photos_for_shipment']) {
            $photos_for_shipment = json_decode($this->attributes['photos_for_shipment'], true);
            if (count($photos_for_shipment) > 0) {
                foreach ($photos_for_shipment as $photo_for_shipment) {
                    /*if (Str::startsWith($photo_for_shipment, ['http://', 'https://'])) {
                        $shipment_photo_urls[] = $photo_for_shipment;
                    }
                    $shipment_photo_urls[] = Storage::disk('public')->url($photo_for_shipment);*/
                    $shipment_photo_urls[] = generate_image_url($photo_for_shipment, 'public');
                }
            }
        }
        return $shipment_photo_urls;
    }

    /* Eloquent Relationships */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function getSecondsToDeclineOrderRefund()
    {
        return (integer)(Config::config('time_to_decline_order_refund')) * 3600 * 24;
    }
}
