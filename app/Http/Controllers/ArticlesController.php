<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

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
    public function show(Article $article)
    {
        return view('pages.show', [
            'article' => $article,
        ]);
    }
}
