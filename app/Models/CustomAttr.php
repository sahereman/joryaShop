<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomAttr extends Model
{
    const CUSTOM_ATTR_TYPE_SERVICE = 'SERVICE';
    const CUSTOM_ATTR_TYPE_BASE = 'BASE';
    const CUSTOM_ATTR_TYPE_HAIR = 'HAIR';
    const CUSTOM_ATTR_TYPE_OTHERS = 'OTHERS';

    public static $customAttrTypeMap = [
        self::CUSTOM_ATTR_TYPE_SERVICE => 'SERVICE',
        self::CUSTOM_ATTR_TYPE_BASE => 'BASE',
        self::CUSTOM_ATTR_TYPE_HAIR => 'HAIR',
        self::CUSTOM_ATTR_TYPE_OTHERS => 'OTHERS'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
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
        return $this->hasMany(CustomAttrValue::class)->orderByDesc('sort');
    }
}
