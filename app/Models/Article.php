<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'content_en',
        'content_zh',
    ];

    public static function getContentBySlug(string $slug, string $locale = 'zh_CN')
    {
        $article = self::where('slug', $slug)->first();
        if ($article) {
            if ($locale == 'zh_CN') {
                return $article->content_zh;
            } else {
                return $article->content_en;
            }
        }
        return false;
    }
}
