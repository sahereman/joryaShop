<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Banner extends Model
{
    protected $fillable = [
        'type',
        'disk',
        'image',
        'sort',
        'link',
    ];

    protected $appends = [
        'image_url',
    ];

    /* Accessors */
    public function getImageUrlAttribute()
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
            return $this->attributes['image'];
        }
        return Storage::disk($this->attributes['disk'])->url($this->attributes['image']);
    }
}
