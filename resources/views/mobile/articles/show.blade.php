@extends('layouts.mobile')
@section('title', $article->slug)
@section('content')

    route: mobile/articles/{slug}
    route('mobile.articles.show', ['slug' => $slug]);
    GET articles.show:
    show content by slug ...

    CONTENT:

    {{ \Illuminate\Support\Facades\App::isLocale('en') ? $article->content_en : $article->content_zh }}

@endsection
