<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        // 'title', // 备用字段
        'name',
        // 'surname', // 备用字段
        // 'country_code', // 弃用
        'phone',
        'country',
        'province',
        'city',
        'address',
        // 'backup_address', // 备用字段
        'zip',
        // 'email', // 备用字段
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'last_used_at',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'full_address',
    ];

    public function getFullAddressAttribute()
    {
        // full_address in foreign style
        // return "{$this->attributes['address']}, {$this->attributes['city']}, {$this->attributes['province']}, {$this->attributes['country']}";
        // full_address in chinese style
        // return "{$this->attributes['country']} {$this->attributes['province']} {$this->attributes['city']} {$this->attributes['address']}";
        $full_address = $this->attributes['zip'] ? ' (Zip Code: ' . $this->attributes['zip'] . ')' : '';
        $full_address = $this->attributes['address'] . $full_address;
        $full_address = ($this->attributes['city'] ? $this->attributes['city'] . ', ' : '') . $full_address;
        $full_address = ($this->attributes['province'] ? $this->attributes['province'] . ', ' : '') . $full_address;
        $full_address = ($this->attributes['country'] ? $this->attributes['country'] . ', ' : '') . $full_address;
        return $full_address;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
