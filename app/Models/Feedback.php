<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'email',
        'phone',
        'content',
        'type',
        'is_check',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'is_check' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        //
    ];

    /* Accessors */
    /**
     * Get the user's id.
     * @param integer|null $value
     * @return integer|string
     */
    public function getUserIdAttribute($value)
    {
        return $value ?: 'Anonymous';
    }

    /* Eloquent Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
