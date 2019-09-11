@extends('layouts.app')
@section('title', App::isLocale('zh-CN') ? '莱瑞美业' : 'Lyricalhair')
@section('content')
    <div class="home-page">
        {{-- banner图 --}}
        <div class="swiper-container banner" id="banner">
            <div class="swiper-wrapper">
                @if(isset($banners) && $banners->isNotEmpty())
                    @foreach($banners as $banner)
                        <div class="swiper-slide">
                            <img src="{{ $banner->image_url }}">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide">
                        <a>
                            <img src="{{ asset('defaults/defaults_pc_banner.png') }}">
                        </a>
                    </div>
                @endif
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        {{--lyrical简介--}}
        <div class="lyrical-intro-box">
            <h1 class="lyrical-intro-title" data-scroll-reveal>LYRICALHAIR</h1>
            <div class="lyrical-intro m-wrapper" data-scroll-reveal>
                <div class="lyrical-intro-video" data-scroll-reveal>
                    {{-- 油管播放器的页面嵌入，非API式的iframe嵌套 --}}
                    {{--<div class="dis_n" id="youtubeVideoID" data-video-id="M7lc1UVf-VE" >存放youtube视频id的位置</div>--}}
                    <div id="player">
                        <img src="{{ asset("img/Home/video_img.png") }}">
                    </div>
                    {{--<iframe id="player" type="text/html" width="517" height="330"--}}
                    {{--src="http://www.youtube.com/embed/ziGD7vQOwl8?showinfo=0&rel=0&autoplay=1"--}}
                    {{--frameborder="0" allow="autoplay" allowfullscreen/>--}}
                </div>
                <div class="lyrical-intro-text" data-scroll-reveal>
                    {{--lyrical文字简介--}}
                    <div class="iframe_content">
                        <p>
                            Lyricalhair is a hair replacement system manufacturer and global online retailer.
                            Our first factory was established in 1999.
                        </p>
                        <p>
                            We also offer significant savings because we are an internet based business;
                            our overhead costs are much lower and we pass these additional savings on to you.
                            We offer this new way to get a hair replacement which is both affordable and easy to order,
                            without ever leaving the comfort of your home.
                            Remember, you can always get <span>INDIVIDUALLY CUSTOMISED</span> hair replacement systems.
                            Base material, base material color, size, hair color, hair length, density, hair texture,
                        </p>
                    </div>
                    {{--<iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto"></iframe>--}}
                </div>
            </div>
        </div>
        {{--no-idea--}}
        <div class="noIdea" data-scroll-reveal>
            <img src="{{ asset('img/Home/home_part.png') }}" alt="Lyricalhair.com">
            <div class="content-area" data-scroll-reveal>
                <p>Have no idea which hair system to choose?</p>
                <a href="">Follow Our Simple Guide</a>
            </div>
        </div>
        {{--hair System--}}
        <div class="hair-system-box container">
            <div class="hair-system-img" data-scroll-reveal>
                <a href="">
                    <img src="{{ asset("img/hairSystem.png") }}" alt="Lyricalhair.com">
                </a>
            </div>
            <div class="hair-system-classify" data-scroll-reveal>
                <div class="hair-system-classify-title"><span>Product Zone</span></div>
                @if($categories)
                    <div class="hair-system-classify-kinds">
                        @if(count($categories) <= 3)
                            @foreach($categories as $category)
                                <div class="classify-kinds-item">
                                    <a href="{{ route('seo_url', $category->slug) }}">{{ $category->name_en }}</a>
                                </div>
                            @endforeach
                        @else
                            @foreach($categories as $key => $category)
                                @if($key < 2)
                                    <div class="classify-kinds-item">
                                        <a href="{{ route('seo_url', $category->slug) }}">{{ $category->name_en }}</a>
                                    </div>
                                @elseif($key == 2)
                                    <div class="classify-kinds-item">
                                        <a href="{{ route('seo_url', $category->slug) }}">{{ $category->name_en }}</a>
                                    </div>
                                    {{--超过3个就显示更多--}}
                                    <div class="classify-kinds-item more-kinds-item">
                                        <a href="javascript:void(0)" class="more-kinds iconfont">&#xe617;</a>
                                    </div>
                                    {{--多于三个的存放位置--}}
                                    <div class="more-classify-kinds-box">
                                        <ul class="more-classify-kinds">
                                            @else
                                                <li title="{{ $category->name_en }}">
                                                    <a href="{{ route('seo_url', $category->slug) }}">
                                                        {{ $category->name_en }}
                                                    </a>
                                                </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                        @endif
                    </div>
                @endif
                {{--移动端显示所有的分类选项,把上面的分类不分组直接显示在下面的列表里面--}}
                <div class="classify-kinds-mobile">
                    <div class="classify-kinds-mobile-btn">
                        <span class="iconfont">&#xe604;</span>
                    </div>
                    <div class="classify-kinds-mobile-lists">
                        <ul class="classify-kinds-mobile-list">
                            @foreach($categories as $category)
                                <li title="{{ $category->name_en }}">
                                    <a href="{{ route('seo_url', $category->slug) }}">
                                        {{ $category->name_en }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="hair-system-slider" data-scroll-reveal>
                <div class="swiper-container" id="productZone">
                    <div class="swiper-wrapper">
                        @if($products)
                            @foreach($products as $product)
                                <div class="swiper-slide">
                                    <div class="slide-img">
                                        <a href="{{ route('seo_url', $product->slug) }}">
                                          <img src="{{ $product->thumb_url }}" alt="{{ $product->name_en }}">
                                        </a>
                                    </div>
                                    <div class="slide-title">
                                        <a href="{{ route('seo_url', $product->slug) }}">
                                          <p title="{{ $product->name_en }}">{{ mb_strlen($product->name_en) <= 20 ? $product->name_en : substr($product->name_en, 0, 17) . ' ... ' }}</p>
                                        </a>
                                    </div>
                                    <div class="slide-price">
                                        <p class="old-price"><span>{{ get_global_symbol() }} {{ bcmul(get_current_price($product->price), 1.2, 2) }}</span></p>
                                        <p class="special-price"><span>Special Price </span><span>{{ get_global_symbol() }} {{ get_current_price($product->price) }}</span></p>
                                    </div>
                                    <div class="slide-operation">
                                        <a href="{{ route('seo_url', $product->slug) }}">
                                            View all
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        {{--Preferential activities--}}
        <div class="preferential-activities container dis_ni">
            <h1 class="lyrical-intro-title" data-scroll-reveal>PREFERENTIAL ACTIVITIES</h1>
            <div class="preferential-images" data-scroll-reveal>
                <div class="preferential-images-left" data-scroll-reveal>
                    <div class="images-left-box preferential-img">
                        <a href="https://lyricalhair.com/hair-weaves.html">
                            <img class="lazy" data-src="https://www.lyricalhair.com/storage/posters/d6071a5cd87b861890915a7cf515b627.jpg" alt="lyricalhair.com">
                        </a>
                    </div>
                    <h2><a href="https://lyricalhair.com/hair-weaves.html">WOMEN’S HAIR SYSTEM <span>&#62;</span></a></h2>
                </div>
                <div class="preferential-images-right" data-scroll-reveal>
                    <div data-scroll-reveal>
                        <div class="images-right-box preferential-img">
                            <a href="https://lyricalhair.com/stock-hair-systems.html">
                                <img class="lazy" data-src="https://www.lyricalhair.com/storage/posters/29092330f6d5bb4a3388e160cb5eb3d6.jpg" alt="lyricalhair.com">
                            </a>
                        </div>
                        <h2><a href="https://lyricalhair.com/stock-hair-systems.html">MEN’S HAIR SYSTEM <span>&#62;</span></a></h2>
                    </div>
                    <div data-scroll-reveal>
                        <div class="images-right-box preferential-img">
                            <a href="https://lyricalhair.com/adhesives-tapes-removers.html">
                                <img class="lazy" data-src="https://www.lyricalhair.com/storage/posters/c76f01d99da8b8421e47a84cfa7ba69d.jpg" alt="lyricalhair.com">
                            </a>
                        </div>
                        <h2><a href="https://lyricalhair.com/adhesives-tapes-removers.html">HAIR SYSTEM ACCESSORIES <span>&#62;</span></a></h2>
                    </div>
                </div>
            </div>
        </div>
        {{--publicity--}}
        <div class="publicity dis_ni" data-scroll-reveal>
            <a href="">
                <img src="{{ asset("img/publicity.png") }}" alt="lyricalhair.com">
            </a>
        </div>
        {{--our team--}}
        <div class="Team-part">
            <div class="team-part-title container" data-scroll-reveal>
                <h3>OUR TEAM</h3>
            </div>
            <div class="team-part-intro container" data-scroll-reveal>
                <p>No matter where you are, who you are and what you are passionate about </p>
                <p>we want to be able to provide you with custom products  that help you Express Yourself...to help you express who you really are!</p>
            </div>
            <div class="team-part-img" data-scroll-reveal>
                <img class="lazy" data-src="{{ asset("img/ourTeam.png") }}" alt="Lyricalhair.com">
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    {{--页面滚动动画--}}
    <script src="{{ asset('js/scrollReveal/scrollReveal.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            // 页面滚动动画初始化
            var config = {
                after: '0.5s',
                enter: 'bottom',
                move: '50px',
                over: '1s',
                easing: 'ease-in-out',
                viewportFactor: 0.33,
                reset: false,
                init: true
            };
            if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
                window.scrollReveal = new scrollReveal(config);
            }
            // banner图初始化
            if($(".swiper-container .swiper-slide").length != 1){
                var swiper = new Swiper('#banner', {
                    centeredSlides: true,
                    loop: true,
                    speed: 1500,
                    // fadeEffect: {
                    //     crossFade: true,
                    // },
                    autoplay: {
                        delay: 4000,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
               });
            }
            //    iframe高度自适应
            // function reinitIframe(domID){
            //     var iframe_Description = document.getElementById(domID);
            //     iframe_Description.height = 0; //只有先设置原来的iframe高度为0，之前的iframe高度才不会对现在的设置有影响
            //     var bHeight = iframe_Description.contentWindow.document.body.scrollHeight;
            //     var dHeight = iframe_Description.contentWindow.document.documentElement.scrollHeight;
            //     var height = Math.max(bHeight, dHeight);
            //     iframe_Description.height = bHeight;
            // }
            // var IFRAME = $('.iframe_content'),
            //     CMSCON = $('#cmsCon'),
            //     iframe_content = IFRAME.html();
            // IFRAME.html("");
            // CMSCON.contents().find('body').html(iframe_content);
            // CMSCON.contents().find('body').css({"color":"#8f8f8f","margin":0,"line-height":"150%","box-sizing":"border-box"});
            // reinitIframe("cmsCon");
        //    点击更多
            $(".more-kinds-item").on("click",function () {
                var clickDom = $(this);
                if (clickDom.hasClass("active")) {
                    clickDom.removeClass("active");
                    $(".more-classify-kinds-box").slideUp();
                }else {
                    clickDom.addClass("active");
                    $(".more-classify-kinds-box").slideDown();
                }
            });
        //    移动端点击显示更多
            $(".classify-kinds-mobile-btn").on("touchend",function () {
                var clickDom = $(this);
                if (clickDom.hasClass("active")) {
                    clickDom.removeClass("active");
                    $(".classify-kinds-mobile-lists").slideUp();
                }else {
                    clickDom.addClass("active");
                    $(".classify-kinds-mobile-lists").slideDown();
                }
            })
        //    商品分类轮播图初始化
            var swiper = new Swiper('#productZone', {
                loop: true,
                slidesPerView: 3,
                spaceBetween: 30,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    //当宽度小于等于320
                    // 1440: {
                    //     slidesPerView: 3,
                    //     spaceBetween: 30
                    // },
                    //当宽度小于等于480
                    767: {
                        slidesPerView: 1,
                        spaceBetween: 10
                    }
                }
            });
        });
        // 油管视频API搭建
        // var tag = document.createElement('script');
        // tag.src = "https://www.youtube.com/iframe_api";
        // var firstScriptTag = document.getElementsByTagName('script')[0];
        // firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        // var player;
        // var videoID =  $("#youtubeVideoID").attr("data-video-id");
        // function onYouTubeIframeAPIReady() {
        //     player = new YT.Player('player', {
        //         height: '330',
        //         width: '100%',
        //         videoId: videoID,
        //         events: {
        //             'onReady': onPlayerReady,
        //             'onStateChange': onPlayerStateChange
        //         }
        //     });
        // }
        // function onPlayerReady(event) {
        //     event.target.playVideo();
        // }
        // var done = false;
        // function onPlayerStateChange(event) {
        //     if (event.data == YT.PlayerState.PLAYING && !done) {
        //         done = true;
        //     }
        // }
        // function stopVideo() {
        //     player.stopVideo();
        // }
    </script>
@endsection
