<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAuctionLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auction_product_id',
        'user_id',
        'bid_price'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        //
    ];

    /* Accessors */

    /* Mutators */

    /* Eloquent Relationships */
    public function auction_product()
    {
        return $this->belongsTo(AuctionProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
