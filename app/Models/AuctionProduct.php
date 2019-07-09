<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AuctionProduct extends Model
{
    const AUCTION_STATUS_PREPARING = 'preparing'; // 尚未开始
    const AUCTION_STATUS_BIDDING = 'bidding'; // 拍卖进行中
    const AUCTION_STATUS_SIGNED = 'signed'; // 成交
    const AUCTION_STATUS_ABORTIVE = 'abortive'; // 流拍

    public static $auctionStatusMap = [
        self::AUCTION_STATUS_PREPARING => '尚未开始',
        self::AUCTION_STATUS_BIDDING => '拍卖进行中',
        self::AUCTION_STATUS_SIGNED => '成交',
        self::AUCTION_STATUS_ABORTIVE => '流拍'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'trigger_price',
        'current_price',
        'final_price',
        'step',
        'max_participant_number',
        'max_deal_number',
        'status',
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
        // 'status_name',
        // 'length',
        // 'bids'
    ];

    /* Accessors */
    /*public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }*/

    public function getStatusNameAttribute()
    {
        $auction_status_map = [
            'preparing' => '尚未开始',
            'bidding' => '拍卖进行中',
            'signed' => '成交',
            'abortive' => '流拍'
        ];
        return $auction_status_map[$this->attributes['status']];
    }

    public function getLengthAttribute()
    {
        return Carbon::make($this->attributes['started_at'])->diffInRealSeconds($this->attributes['stopped_at']);
    }

    public function getBidsAttribute()
    {
        return ProductAuctionLog::where('auction_product_id', $this->attributes['id'])->count();
    }

    /* Mutators */
    /*public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }*/

    public function setStatusNameAttribute($value)
    {
        unset($this->attributes['status_name']);
    }

    public function setLengthAttribute($value)
    {
        unset($this->attributes['length']);
    }

    public function setBidsAttribute($value)
    {
        unset($this->attributes['bids']);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function auction_logs()
    {
        return $this->hasMany(ProductAuctionLog::class);
    }
}
