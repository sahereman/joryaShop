<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Poster extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'disk',
        // 'image',
        'photos',
        'link',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'photos' => 'json',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
        'photo_urls',
    ];

    /* Accessors */
    public function getImageUrlAttribute()
    {
        $photo_urls = $this->getPhotoUrlsAttribute();
        if (count($photo_urls) > 0) {
            return $photo_urls[0];
        }
        return '';
    }

    public function getPhotoUrlsAttribute()
    {
        $photo_urls = [];
        if ($this->attributes['photos']) {
            $photos = json_decode($this->attributes['photos'], true);
            if (count($photos) > 0) {
                foreach ($photos as $photo) {
                    /*if (Str::startsWith($photo, ['http://', 'https://'])) {
                        $photo_urls[] = $photo;
                    }
                    $photo_urls[] = Storage::disk('public')->url($photo);*/
                    $photo_urls[] = generate_image_url($photo, 'public');
                }
            }
        }
        return $photo_urls;
    }

    /* Mutators */
    public function setImageUrlAttribute($value)
    {
        unset($this->attributes['image_url']);
    }

    public function setPhotoUrlsAttribute($value)
    {
        unset($this->attributes['photo_urls']);
    }

    /* Public Static Functions */
    public static function getPosterBySlug(string $slug)
    {
        $poster = self::where('slug', $slug)->first();
        if ($poster) {
            return $poster;
        }
        return false;
    }
}
