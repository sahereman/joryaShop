<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttrValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attr_id',
        'value',
        'abbr',
        'photo',
        'sort'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url'
    ];

    /* Accessors */
    public function getPhotoUrlAttribute()
    {
        if ($this->attributes['photo']) {
            // 如果 photo 字段本身就已经是完整的 url 就直接返回
            /*if (Str::startsWith($this->attributes['photo'], ['http://', 'https://'])) {
                return $this->attributes['photo'];
            }
            return Storage::disk('public')->url($this->attributes['photo']);*/
            return generate_image_url($this->attributes['photo'], 'public');
        }
        return '';
    }

    /* Mutators */
    public function setPhotoUrlAttribute($value)
    {
        unset($this->attributes['photo_url']);
    }

    /* Eloquent Relationships */
    public function attr()
    {
        return $this->belongsTo(Attr::class);
    }
}
