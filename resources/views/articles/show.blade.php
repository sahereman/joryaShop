@extends('layouts.app')
@section('title', $article->slug)
@section('content')

    {{--<h3>DEMO PAGE:</h3>

    route: articles/{slug}
    <br>
    <br>
    route('articles.show', ['slug' => $slug]);
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
    <br>--}}
    <div class="common_articles products-search-level">
    	<div class="m-wrapper">
    		{!! App::isLocale('en') ? $article->content_en : $article->content_zh !!}
    	</div>
    </div>

@endsection
