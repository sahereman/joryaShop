<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DiscountProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'number',
        'price'
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
        //
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'product_name'
    ];

    /* Accessors */
    /*public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }*/

    public function getDiscountAttribute()
    {
        $product_price = $this->product->price;
        $discount_percentage = bcsub(100, bcdiv(bcmul($this->attributes['price'], 100), $product_price));
        return $discount_percentage;
    }

    /* Mutators */
    /*public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }*/

    public function setDiscountAttribute($value)
    {
        unset($this->attributes['discount']);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
