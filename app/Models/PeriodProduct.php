<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PeriodProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'started_at',
        'stopped_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at',
        'stopped_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'product_name',
        // 'length',
        // 'status'
    ];

    /* Accessors */
    /*public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }*/

    public function getLengthAttribute()
    {
        return Carbon::make($this->attributes['started_at'])->diffInRealSeconds($this->attributes['stopped_at']);
    }

    public function getStatusAttribute()
    {
        $now = Carbon::now()->getTimestamp();
        $started_at = Carbon::make($this->attributes['started_at'])->getTimestamp();
        $stopped_at = Carbon::make($this->attributes['stopped_at'])->getTimestamp();
        if ($now < $started_at) {
            return '尚未开始';
        } else if ($now > $stopped_at) {
            return '已结束';
        } else {
            return '进行中';
        }
    }

    /* Mutators */
    /*public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }*/

    public function setLengthAttribute($value)
    {
        unset($this->attributes['length']);
    }

    public function setStatusAttribute($value)
    {
        unset($this->attributes['status']);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
