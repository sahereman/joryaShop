@extends('layouts.mobile')
@section('title', $article->slug)
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

    {!! App::isLocale('en') ? $article->content_en : $article->content_zh !!}

    @include('layouts._footer_mobile')
@endsection
