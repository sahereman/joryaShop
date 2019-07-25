<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class User extends Authenticatable
{

    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'avatar', 'email', 'password',
        'real_name', 'gender', 'qq', 'wechat',
        'country_code', 'phone', 'facebook',
        'money', 'point', 'distribution_parent'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    /* Accessors */
    public function getAvatarUrlAttribute()
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->attributes['avatar'], ['http://', 'https://'])) {
            return $this->attributes['avatar'];
        }
        return \Storage::disk('public')->url($this->attributes['avatar']);
    }

    public function getAvailableCouponsAttribute()
    {
        return $this->user_coupons()->where([
            'order_id' => null,
            'used_at' => null
        ])->get()->filter(function (UserCoupon $userCoupon) {
            return $userCoupon->proto_coupon->status == Coupon::COUPON_STATUS_USING;
        });
    }

    /* Mutators */
    public function setAvatarUrlAttribute($value)
    {
        unset($this->attributes['avatar_url']);
    }

    public function setAvailableCouponsAttribute($value)
    {
        unset($this->attributes['available_coupons']);
    }

    /* Eloquent Relationships */
    public function favourites()
    {
        return $this->hasMany(UserFavourite::class);
    }

    public function histories()
    {
        return $this->hasMany(UserHistory::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function auction_logs()
    {
        return $this->hasMany(ProductAuctionLog::class);
    }

    public function user_coupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function money_bills()
    {
        return $this->hasMany(UserMoneyBill::class);
    }

    public function income_user()
    {
        return $this->belongsTo(self::class, 'distribution_parent');
    }
}
