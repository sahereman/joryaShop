<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort',
        'is_sorted_by'
    ];

    /* Eloquent Relationships */
    public function values()
    {
        return $this->hasMany(ParamValue::class)->orderByDesc('sort');
    }
}
