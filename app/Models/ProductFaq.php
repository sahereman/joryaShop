<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFaq extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'question',
        'answer',
        'sort'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'product_name'
    ];

    /* Accessors */
    public function getProductNameAttribute()
    {
        return Product::find($this->attributes['product_id'])->name_en;
    }

    /* Mutators */
    public function setProductNameAttribute($value)
    {
        unset($this->attributes['product_name']);
    }

    /*Eloquent Relationships*/
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
