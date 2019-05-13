@extends('layouts.app')
@section('keywords', $article->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $article->seo_description ? : \App\Models\Config::config('description'))
@section('title', $article->seo_title ? : $article->slug . ' - ' . \App\Models\Config::config('title'))
@section('content')

    <div class="common_articles products-search-level">
        <div class="m-wrapper">
            <iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto">
                {!! App::isLocale('zh-CN') ? $article->content_zh : $article->content_en !!}
            </iframe>
        </div>
    </div>

@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
//  $('#cmsCon').contents().find('body').html('展示内容');
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