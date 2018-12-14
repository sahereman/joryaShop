<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Poster extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'disk',
        'image',
        'link',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->attributes['image']) {
            // 如果 image 字段本身就已经是完整的 url 就直接返回
            /*if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
                return $this->attributes['image'];
            }
            return Storage::disk($this->attributes['disk'])->url($this->attributes['image']);*/
            if ($this->attributes['disk']) {
                return generate_image_url($this->attributes['image'], $this->attributes['disk']);
            }
            return generate_image_url($this->attributes['image'], 'public');
        }
        return '';
    }

    public static function getPosterBySlug(string $slug)
    {
        $poster = self::where('slug', $slug)->first();
        if ($poster) {
            return $poster;
        }
        return false;
    }
}
