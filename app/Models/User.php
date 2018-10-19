<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'avatar', 'email', 'password',
        'real_name', 'gender', 'qq', 'wechat',
        'country_code', 'phone', 'facebook',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    // $this->user()->avatar_url
    public function getAvatarUrlAttribute()
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->attributes['avatar'], ['http://', 'https://'])) {
            return $this->attributes['avatar'];
        }
        return \Storage::disk('public')->url($this->attributes['avatar']);
    }

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
}
