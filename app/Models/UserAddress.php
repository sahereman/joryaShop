<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'address',
    ];

    protected $hidden = [
        //
    ];

    protected $dates = [
        'last_used_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
