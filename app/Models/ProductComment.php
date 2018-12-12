<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'order_item_id',
        'product_id',
        'composite_index',
        'description_index',
        'shipment_index',
        'content',
        'photos',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'photos' => 'json',
    ];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'photo_urls',
    ];

    public function getPhotoUrlsAttribute()
    {
        $photo_urls = [];
        $photos = json_decode($this->attributes['photos'], true);
        if (count($photos) > 0) {
            foreach ($photos as $photo) {
                /*if (Str::startsWith($photo, ['http://', 'https://'])) {
                    $photo_urls[] = $photo;
                }
                $photo_urls[] = Storage::disk('public')->url($photo);*/
                $photo_urls[] = generate_image_url($photo);
            }
        }
        return $photo_urls;
    }

    public function supplements()
    {
        return $this->hasMany(ProductCommentSupplement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
