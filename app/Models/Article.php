<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'content_en',
        'content_zh',
    ];

    public static function getArticleBySlug(string $slug)
    {
        $article = self::where('slug', $slug)->first();
        if ($article) {
            return $article;
        }
        return false;
    }
}
