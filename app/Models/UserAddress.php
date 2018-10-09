<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'address',
        'is_default',
    ];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}
