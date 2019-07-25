<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCoupon extends Model
{
    /*
     * To enable soft deletes for a model,
     * use the Illuminate\Database\Eloquent\SoftDeletes trait on the model,
     * and add the deleted_at column to your $dates property.
     */
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'got_at',
        'used_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'got_at',
        'used_at',
        'deleted_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'user_name',
        'coupon_name',
        'order_sn',
        'is_used'
    ];

    /* Accessors */
    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function getCouponNameAttribute()
    {
        return $this->proto_coupon->name;
    }

    public function getOrderSnAttribute()
    {
        if ($this->order) {
            return $this->order->order_sn;
        } else {
            return '';
        }
    }

    public function getIsUsedAttribute()
    {
        if ($this->attributes['order_id'] && $this->attributes['used_at']) {
            return true;
        }
        return false;
    }

    /* Mutators */
    public function setUserNameAttribute($value)
    {
        unset($this->attributes['user_name']);
    }

    public function setCouponNameAttribute($value)
    {
        unset($this->attributes['coupon_name']);
    }

    public function setOrderSnAttribute($value)
    {
        unset($this->attributes['order_sn']);
    }

    public function setIsUsedAttribute($value)
    {
        unset($this->attributes['is_used']);
    }

    /* Eloquent Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proto_coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
