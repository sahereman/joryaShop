<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tests\Models\User;

class ProductCommentSupplement extends Model
{
    const PRODUCT_COMMENT_SUPPLEMENT_FROM_USER = 'user';
    const PRODUCT_COMMENT_SUPPLEMENT_FROM_SELLER = 'seller';

    public static $productCommentSupplementFromMap = [
        self::PRODUCT_COMMENT_SUPPLEMENT_FROM_USER => '来自用户', // 来自用户的追加评论
        self::PRODUCT_COMMENT_SUPPLEMENT_FROM_SELLER => '来自卖家', // 来自卖家的追加评论
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'product_comment_id',
        'user_id',
        'product_id',
        'content',
        'photos',
        'from',
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
        'user',
        'photo_urls',
    ];

    public function getUserAttribute()
    {
        if ($this->attributes['from'] == self::PRODUCT_COMMENT_SUPPLEMENT_FROM_USER && !is_null($this->attributes['user_id'])) {
            return User::find($this->attributes['user_id']);
        }
        return null; // 来自卖家的追加评论.
    }

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

    public function comment()
    {
        return $this->belongsTo(ProductComment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
