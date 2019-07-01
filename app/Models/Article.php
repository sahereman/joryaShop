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

        /* 2019-04-09 for SEO */
        'seo_titles',
        'seo_keywords',
        'seo_description',
        /* 2019-04-09 for SEO */
    ];

    public static function getArticleBySlug(string $slug)
    {
        $article = self::where('slug', $slug)->first();
        if ($article) {
            return $article;
        }
        return false;
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }
}
