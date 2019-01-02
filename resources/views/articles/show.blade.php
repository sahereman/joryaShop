@extends('layouts.app')
@section('title', $article->slug . ' - ' . \App\Models\Config::config('title'))
@section('description', mb_substr(strip_tags($article->content_zh), 0, 100))
@section('content')

    <div class="common_articles products-search-level">
        <div class="m-wrapper">
            {!! App::isLocale('en') ? $article->content_en : $article->content_zh !!}
        </div>
    </div>

@endsection
