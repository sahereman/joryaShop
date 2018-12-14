<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tests\Models\User;

class ProductCommentSupplement extends Model
{
    const FROM_USER = 'user';
    const FROM_SELLER = 'seller';

    public static $fromMap = [
        self::FROM_USER => '来自用户', // 来自用户的追加评论
        self::FROM_SELLER => '来自卖家', // 来自卖家的追加评论
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'product_comment_id',
        'user_id',
        'content',
        'photos',
        'from',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'photos' => 'json',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'deleted_at',
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
        if ($this->attributes['from'] == self::FROM_USER && !is_null($this->attributes['user_id'])) {
            return User::find($this->attributes['user_id']);
        }
        return null; // 来自卖家的追加评论.
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

    public function comment()
    {
        return $this->belongsTo(ProductComment::class);
    }
}
