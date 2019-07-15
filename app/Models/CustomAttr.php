<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomAttr extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_required',
        'sort'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_required' => 'boolean'
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
    public function values()
    {
        return $this->hasMany(CustomAttrValue::class);
    }
}
