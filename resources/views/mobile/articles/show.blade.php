@extends('layouts.mobile')
@section('keywords', $article->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $article->seo_description ? : \App\Models\Config::config('description'))
@section('title', $article->seo_title ? : $article->slug . ' - ' . \App\Models\Config::config('title'))
@section('content')

    <div class="common_articles products-search-level">
        <div class="m-wrapper">
            {!! App::isLocale('zh-CN') ? $article->content_zh : $article->content_en !!}
        </div>
    </div>

    @include('layouts._footer_mobile')
@endsection
