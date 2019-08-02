<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttrValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attr_id',
        'value',
        'sort'
    ];

    /* Eloquent Relationships */
    public function attr()
    {
        return $this->belongsTo(Attr::class);
    }
}
