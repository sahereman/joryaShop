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
        'photo_urls',
        'title'
    ];

    /* Accessors */
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

    public function getTitleAttribute()
    {
        $content = strip_tags($this->attributes['content']);
        // 是否含有中文字符
        // Chinese regular expression pattern in UTF-8 for PHP: \x4e00-\x9fa5
        // Chinese regular expression pattern in UTF-8 for JavaScript: \u4e00-\u9fa5
        // Chinese regular expression pattern for PHP: $pattern = chr(0xa1) . "-" . chr(0xff);
        $pattern = chr(0xa1) . "-" . chr(0xff);
        // if (preg_match('/[\x4e00-\x9fa5]+/', $content)) {
        // if (preg_match('/[\x4e00-\x9fa5]+/u', $content)) {
        if (preg_match("/[{$pattern}]+/", $content)) {
            if (mb_strlen($content) <= 10) {
                return $content;
            } else {
                return mb_substr($content, 0, 10) . ' ...';
            }
        } else {
            if (mb_strlen($content) < 50) {
                return $content;
            } else {
                $content = mb_substr($content, 0, 50);
                $position = strrpos($content, ' ');
                if ($position) {
                    return mb_substr($content, 0, $position) . ' ...';
                }
                return $content . ' ...';
            }
        }
    }

    /* Mutators */
    public function setPhotoUrlsAttribute($value)
    {
        unset($this->attributes['photo_urls']);
    }

    public function setTitleAttribute($value)
    {
        unset($this->attributes['title']);
    }

    /* Eloquent Relationships */
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
