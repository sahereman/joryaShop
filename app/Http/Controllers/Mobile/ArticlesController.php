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
        if (App::isLocale('en')) {
            $content = Article::getContentBySlug($slug, 'en');
        } else {
            $content = Article::getContentBySlug($slug, 'zh-CN');
        }
        if ($content) {
            return view('mobile.common.article', [
                'content' => $content,
            ]);
        } else {
            throw new InvalidRequestException('参数错误，请重试');
        }
    }
}
