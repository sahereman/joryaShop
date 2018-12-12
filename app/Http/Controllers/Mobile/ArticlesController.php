<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ArticlesController extends Controller
{
    // GET 通用-单页展示
    public function show(Request $request, string $slug)
    {
        $article = Article::getArticleBySlug($slug);
        if ($article) {
            return view('mobile.articles.show', [
                'article' => $article,
            ]);
        } else {
            throw new InvalidRequestException('参数错误，请重试');
        }
    }
}
