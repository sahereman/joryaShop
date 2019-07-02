<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'sort',
    ];

    public static function availableOptions()
    {
        $available_options = static::get(['name', 'description'])->mapWithKeys(function ($item) {
            return [$item['description'] => $item['description']];
        })->toArray();
        return $available_options;
    }
}
