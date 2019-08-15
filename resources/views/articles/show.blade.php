@extends('layouts.app')
@section('keywords', $article->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $article->seo_description ? : \App\Models\Config::config('description'))
@section('title', $article->seo_title ? : $article->slug . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="common_articles products-search-level">
        <div class="m-wrapper container">
            <div class="left-nav">
                <div class="block block-layered-nav">
                    <div class="block-content">
                        @if($article_categories && $article_categories->isNotEmpty())
                            <div class="categories-lists-items categories-menu">
                                @foreach($article_categories as $article_category)
                                    <div class="categories-lists-item">
                                        <div class="lists-item-title">
                                            <a href="javascript:void(0)"><span>{{ $article_category->name_en }}</span></a>
                                        </div>
                                        @if($articles = $article_category->articles)
                                            <ul class="categories-lists-item-ul">
                                                @foreach($articles as $article)
                                                    <li>
                                                        <a href="{{ route('seo_url', ['slug' => $article->slug]) }}">
                                                            <span>{{ $article->name }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="right-content">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>/</span>
                    <a href="javascript:void(0)">{{ $article->category->name_en }}</a>
                    <span>/</span>
                    <a href="javascript:void(0)">{{ $article->name }}</a>
                </p>
                <div class="right-article">
                    <div class="iframe_content dis_ni">
                        {!! App::isLocale('zh-CN') ? $article->content_zh : $article->content_en !!}
                    </div>
                    <iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        var iframe_content = $('.iframe_content').html();
        $('.iframe_content').html("");
        $('#cmsCon').contents().find('body').html(iframe_content);
        autoHeight();  //动态调整高度
        var count = 0;
        var autoSet = window.setInterval('autoHeight()',500);
        function autoHeight(){
            var mainheight;
            count++;
            if(count == 1){
                mainheight = $('.cmsCon').contents().find("body").height()+50;
            }else{
                mainheight = $('.cmsCon').contents().find("body").height()+24;
            }
            $('.cmsCon').height(mainheight);
            if(count == 5){
                window.clearInterval(autoSet);
            }
        }
    </script>
@endsection
