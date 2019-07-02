<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_sku_id',
        'number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'favourite',
    ];

    /* Accessors */
    public function getFavouriteAttribute()
    {
        $favourite = UserFavourite::where('user_id', $this->attributes['user_id'])->where('product_id', $this->sku->product_id)->first();
        return $favourite;
    }

    /* Eloquent Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }
}
