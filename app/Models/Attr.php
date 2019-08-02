<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attr extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'has_photo',
        'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_photo' => 'boolean'
    ];

    /* Eloquent Relationships */
    public function values()
    {
        return $this->hasMany(AttrValue::class)->orderByDesc('sort');
    }
}
