<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParamValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'param_id',
        'value',
        'sort'
    ];

    /* Eloquent Relationships */
    public function param()
    {
        return $this->belongsTo(Param::class);
    }

    public function product_params()
    {
        return $this->hasMany(ProductParam::class);
    }
}
