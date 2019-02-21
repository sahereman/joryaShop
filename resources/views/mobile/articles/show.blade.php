@extends('layouts.mobile')
@section('title', $article->slug . ' - ' . \App\Models\Config::config('title'))
@section('description', mb_substr(strip_tags($article->content_zh), 0, 100))
@section('content')

    <h3>DEMO PAGE:</h3>

    route: mobile/articles/{slug}
    <br>
    <br>
    route('mobile.articles.show', ['slug' => $slug]);
    <br>
    <br>
    GET articles.show:
    <br>
    <br>
    show content by slug ...
    <br>
    <br>

    CONTENT:
    <br>
    <br>

    {!! App::isLocale('zh-CN') ? $article->content_zh : $article->content_en !!}

    @include('layouts._footer_mobile')
@endsection
