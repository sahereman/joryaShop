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
        'value',
        'sort'
    ];

    /* Eloquent Relationships */
    public function param()
    {
        return $this->belongsTo(Param::class);
    }
}
