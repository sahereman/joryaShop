<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryProvince extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'parent_id', 'type', 'name_zh', 'name_en', 'code', 'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'attachments' => 'json'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
    ];

    public $timestamps = false;
}
