<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductParam extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'name',
        'value',
        'sort'
    ];

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
