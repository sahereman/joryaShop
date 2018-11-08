<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'product_category_id', 'name_en', 'name_zh', 'description_en', 'description_zh',
        'content_en', 'content_zh', 'thumb', 'photos', 'shipping_fee', 'stock', 'sales',
        'index', 'heat', 'price', 'is_index', 'on_sale'
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
        'is_index' => 'boolean',
        'on_sale' => 'boolean',
        'photos' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'thumb_url',
        'photo_set',
    ];

    public function getThumbUrlAttribute()
    {
        // 如果 thumb 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->attributes['thumb'], ['http://', 'https://'])) {
            return $this->attributes['thumb'];
        }
        return Storage::disk('public')->url($this->attributes['thumb']);
    }

    public function getPhotoSetAttribute()
    {
        $photoSet = [];
        $photos = json_decode($this->attributes['photos'], true);
        if (count($photos) > 0) {
            foreach ($photos as $photo) {
                if (Str::startsWith($photo, ['http://', 'https://'])) {
                    $photoSet[] = $photo;
                }
                $photoSet[] = Storage::disk('public')->url($photo);
            }
        }
        return $photoSet;
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }
}
