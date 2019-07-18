<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'payment_amount',
        'payment_method',
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

    /* Accessors */

    /* Mutators */

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
