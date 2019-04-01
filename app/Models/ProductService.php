<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
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
        $available_options = static::orderBy('sort')->get(['name', 'description'])->mapWithKeys(function ($item) {
            return [$item['description'] => $item['name']];
        })->toArray();
        return $available_options;
    }
}
