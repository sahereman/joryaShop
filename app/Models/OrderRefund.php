<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    const ORDER_REFUND_STATUS_CHECKING = 'checking';
    const ORDER_REFUND_STATUS_SHIPPING = 'shipping';
    const ORDER_REFUND_STATUS_RECEIVING = 'receiving';
    const ORDER_REFUND_STATUS_REFUNDED = 'refunded';
    const ORDER_REFUND_STATUS_DECLINED = 'declined';

    protected $orderRefundStatusMap = [
        self::ORDER_REFUND_STATUS_CHECKING => '待审核',
        self::ORDER_REFUND_STATUS_SHIPPING => '待发货',
        self::ORDER_REFUND_STATUS_RECEIVING => '待收货',
        self::ORDER_REFUND_STATUS_REFUNDED => '已退款',
        self::ORDER_REFUND_STATUS_DECLINED => '已拒绝',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'seller_info',
        'type',
        'status',
        'remark_by_user',
        'remark_by_seller',
        'remark_by_shipment',
        'shipment_sn',
        'shipment_company',
        'photos_for_refund',
        'photos_for_shipment',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'seller_info' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'checked_at',
        'shipped_at',
        'refunded_at',
        'declined_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_set_for_refund',
        'photo_set_for_shipment',
    ];

    public function getPhotoSetForRefundAttribute()
    {
        $photo_set_for_refund = [];
        if ($this->attributes['photos_for_refund'] != '') {
            $photos_for_refund = explode(',', $this->attributes['photos_for_refund']);
            foreach ($photos_for_refund as $photo_for_refund) {
                $photo_set_for_refund[] = generate_image_url($photo_for_refund);
            }
        }
        return $photo_set_for_refund;
    }

    public function getPhotoSetForShipmentAttribute()
    {
        $photo_set_for_shipment = [];
        if ($this->attributes['photos_for_shipment'] != '') {
            $photos_for_shipment = explode(',', $this->attributes['photos_for_shipment']);
            foreach ($photos_for_shipment as $photo_for_shipment) {
                $photo_set_for_shipment[] = generate_image_url($photo_for_shipment);
            }
        }
        return $photo_set_for_shipment;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function translateStatus($status)
    {
        return $this->orderRefundStatusMap[$status];
    }
}
