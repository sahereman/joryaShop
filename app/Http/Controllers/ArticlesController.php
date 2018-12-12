<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ArticlesController extends Controller
{
    /*public function root()
    {
        return view('pages.root');
    }*/

    // GET 错误提示页示例
    public function error()
    {
        return view('pages.error', ['msg' => '操作失败']);
    }

    // GET 成功提示页示例
    public function success()
    {
        return view('pages.success', ['msg' => '操作成功']);
    }

    // GET 通用-单页展示
    public function show(Request $request, string $slug)
    {
        $article = Article::getArticleBySlug($slug);
        if ($article) {
            return view('articles.show', [
                'article' => $article,
            ]);
        } else {
            throw new InvalidRequestException('参数错误，请重试');
        }
    }
}
