<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PeriodProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'started_at',
        'stopped_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at',
        'stopped_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'length'
    ];

    /* Accessors */
    public function getLengthAttribute()
    {
        return Carbon::make($this->attributes['started_at'])->diffInRealSeconds($this->attributes['stopped_at']);
    }

    /* Mutators */
    public function setLengthAttribute($value)
    {
        unset($this->attributes['length']);
    }

    /* Eloquent Relationships */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
