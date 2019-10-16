@extends('layouts.app')
@section('title', App::isLocale('zh-CN') ? '莱瑞美业' : 'Lyricalhair')
@section('content')
{{-- banner --}}
    <div class="banner">
        <div class="slick" id="banner">
            {{-- 接口可用，为了页面展示效果注释，正式版本启用 --}}
            @if(isset($banners) && $banners->isNotEmpty())
                @foreach($banners as $banner)
                    <div class="item item-1">
                        <a class="img-box" href="{{$banner->link}}">
                            <img src="{{ $banner->image_url }}" alt="lyricalhair" />
                        </a>
                    </div>
                @endforeach
            @else
                <div class="item item-1">
                    <a class="img-box" href="javascript:void (0);">
                        <img src="{{ asset('defaults/defaults_pc_banner.jpg') }}" alt="lyricalhair" />
                    </a>
                </div>
            @endif
        </div>
    </div>
    {{-- About lyrical--}}
    <div class="about-lyrical">
        <div class="main-content">
            {{-- 关于lyrical的介绍 --}}
            <div class="lyrical-info">
                <div class="lyrical-info-title LaoMN-font">
                    <img src="{{ asset("img/Home/About_lyricalhair.png") }}" alt="lyricalhair">
                </div>
                <div class="lyrical-info-subtitle">
                    <span>Lyricalhair is a hair replacement system manufacturer and global online Retailer. Our first factory was established in 1999.</span>
                </div>
                <div class="lyrical-info-article">
                    <span>We also offer significant savings because we are an internet based business; our overhead costs are much lower and we pass 
                        these additional savings on to you. We offer this new way to get a hair replacement which is both affordable and easy to 
                        order, without ever leaving the comfort of your home. Remember...</span>
                </div>
                <div class="lyrical-info-link">
                    <a href="{{ route('seo_url', ['slug' => 'contact_us']) }}">LEARN MORE</a>
                </div>
            </div>
            {{-- lyrical的资质认证或者活动图片墙,图片只改变路径，不能改变布局，不然达不到设计图想要的效果--}}
            <div class="qualification">
                 {{-- 照片墙左侧 --}}
                 <div class="qualification-left">
                     {{-- 左侧上部 --}}
                     <div class="qualification-left-up">
                         <img class="photo-wall-1" src="{{ asset("img/Home/photo-wall-1.png") }}" alt="lyricalhair">
                         @if($poster = \App\Models\Poster::getPosterBySlug('about_lyrical_hair_up'))
                             <img class="photo-wall-6" src="{{ $poster->image_url }}" alt="lyricalhair">
                         @else
                             <img class="photo-wall-6" src="{{ asset("img/Home/photo-wall-6.png") }}" alt="lyricalhair">
                         @endif
                     </div>
                     {{-- 左侧下部 --}}
                     <div class="qualification-left-down">
                         @if($poster = \App\Models\Poster::getPosterBySlug('about_lyrical_hair_left'))
                             <img class="photo-wall-3" src="{{ $poster->image_url }}" alt="lyricalhair">
                         @else
                             <img class="photo-wall-3" src="{{ asset("img/Home/photo-wall-3.png") }}" alt="lyricalhair">
                         @endif
                         @if($poster = \App\Models\Poster::getPosterBySlug('about_lyrical_hair_down'))
                             <img class="photo-wall-5" src="{{ $poster->image_url }}" alt="lyricalhair">
                         @else
                             <img class="photo-wall-5" src="{{ asset("img/Home/photo-wall-5.png") }}" alt="lyricalhair">
                         @endif
                     </div>
                 </div>
                {{-- 照片墙右侧 --}}
                <div class="qualification-right">
                    @if($poster = \App\Models\Poster::getPosterBySlug('about_lyrical_hair_right'))
                        <img class="photo-wall-4" src="{{ $poster->image_url }}" alt="lyricalhair">
                    @else
                        <img class="photo-wall-4" src="{{ asset("img/Home/photo-wall-4.png") }}" alt="lyricalhair">
                    @endif
                    <img class="photo-wall-2" src="{{ asset("img/Home/photo-wall-2.png") }}" alt="lyricalhair">
                </div>
            </div>
        </div>
    </div>
    {{-- 数字滚动 --}}
    <div class="countup-area">
        <ul class="main-content">
            <li>
                <span class="countup-num"><span class="counter">50</span>+</span>
                <span class="countup-text">IN-HOUSE STAFFS</span>
                <span class="countup-text">AND WORKERS</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">15</span></span>
                <span class="countup-text">MACHINES WORKING AT</span>
                <span class="countup-text">THE SAME TIME</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">3000</span>+</span>
                <span class="countup-text">HAIR SYSTEMS PRODUCED</span>
                <span class="countup-text">AND DELIVERED LAST YEAR</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">30</span>+</span>
                <span class="countup-text">COUNTRIES ACROSS</span>
                <span class="countup-text">THE WORLD</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">72</span>%</span>
                <span class="countup-text">CLIENT</span>
                <span class="countup-text">RETENTION RATE</span>
            </li>
        </ul>
    </div>
    {{-- Product Zone  --}}
    <div class="Product-Zone">
        <div class="main-content">
            <p class="part-title">
                <img src="{{ asset("img/Home/Product_Zone.png") }}" alt="lyricalhair">
            </p>
            {{-- 轮播图 --}}
            <div class="roundabout_box main-content" id="ProductZone">
                <ul>
                    @if($products)
                        @foreach($products as $product)
                            <li>
                                <a href="{{ route('seo_url', $product->slug) }}">
                                    <img class="roundabout_img" src="{{ $product->thumb_url }}" alt="lyricalhair">
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            {{-- Why Lyricalhair && Lyricalhair Blog --}}
            <div class="why-youtube">
                <div class="why-lyrical">
                    <div class="why-title">
                        <span>Why Lyricalhair ?</span>
                    </div>
                    <div class="why-info">
                        <span>Lyricalhair is a hair replacement system manufacturer and global online</span>
                        <span>Retailer. Our first factory was established in 1999.</span>
                        <span>We also offer significant savings because we are an internet based business;</span>
                        <span>our overhead costs are much lower and we pass these additional savings on to you.</span>
                        <span>We offer this new way to get a hair replacement which is both affordable and easy to</span>
                        <span>order, without ever leaving the comfort of your home. Remember...</span>
                    </div>
                    <a href="{{ route('seo_url', ['slug' => 'why_lyricalhair']) }}">LEARN MORE</a>
                    <div class="why-imgs">
                        @if($poster = \App\Models\Poster::getPosterBySlug('why_lyrical_hair_1'))
                            <img src="{{ $poster->image_url }}" alt="lyricalhair">
                        @else
                            <img src="{{ asset("img/Home/why-1.png") }}" alt="lyricalhair" />
                        @endif
                        @if($poster = \App\Models\Poster::getPosterBySlug('why_lyrical_hair_2'))
                            <img src="{{ $poster->image_url }}" alt="lyricalhair">
                        @else
                            <img src="{{ asset("img/Home/why-2.png") }}" alt="lyricalhair" />
                        @endif
                    </div>
                </div>
                <div class="youtube-video">
                    <div class="video-title">
                        <span>Lyricalhair Blog</span>
                    </div>
                    {{-- 油管播放器的页面嵌入，非API式的iframe嵌套 --}}
                    <div class="dis_n" id="youtubeVideoID" data-video-id="M7lc1UVf-VE" >存放youtube视频id的位置</div>
                    <iframe id="player" type="text/html"
                            src="https://www.youtube.com/embed/ziGD7vQOwl8?showinfo=0&rel=0&autoplay=1" 
                            frameborder="0" allow="autoplay" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    {{-- OUR PRICES --}}
    <div class="our-prices">
        <div class="main-content">
            <p class="part-title">
                <img src="{{ asset("img/Home/OUR_PRICES.png") }}" alt="lyricalhair">
            </p>
            <ul>
                <li>
                    <div class="part-img-box">
                        <img src="{{ asset("img/Home/customOrder.png") }}" alt="lyricalhair">
                    </div>
                    <span class="part-img-title">Custom Order</span>
                    <span>We Offer Free Rush Order on Duplicate</span>
                    <span>Super High Definition Hai</span>
                    <span>Half delivery back fee covered</span>
                    {{--<a href="{{ route('seo_url', ['slug' => \App\Models\Product::where(['type' => 'custom', 'on_sale' => 1])->first()->slug]) }}">LEARN MORE</a>--}}
                    <a href="{{ route('seo_url', ['slug' => 'custom-hair-systems']) }}">LEARN MORE</a>
                </li>
                <li>
                    <div class="part-img-box">
                        <img src="{{ asset("img/Home/repairedOrder.png") }}" alt="lyricalhair">
                    </div>
                    <span class="part-img-title">Repaired Order</span>
                    <span>Highest Quality Hair OnMarket </span>
                    <span>Undetectable Hairline</span>
                    <span>Invisible Knots</span>
                    <a href="{{ route('seo_url', ['slug' => 'repair-service']) }}">LEARN MORE</a>
                </li>
                <li>
                    <div class="part-img-box">
                        <img src="{{ asset("img/Home/DuplicatedOrder.png") }}" alt="lyricalhair">
                    </div>
                    <span class="part-img-title">Duplicated Order</span>
                    <span>Top Level Care and Craftsmanship</span>
                    <span>Repairs and Hair Adds</span>
                    <span>4-5 weeks leading time</span>
                    <a href="{{ route('seo_url', ['slug' => 'duplicate-service']) }}">LEARN MORE</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    {{-- 轮播插件 --}}
    <script src="{{ asset('js/slick/slick.min.js') }}"></script>
    <script src="{{ asset('js/3Dlbt/jquery.roundabout.min.js') }}"></script>
    <script src="{{ asset('js/3Dlbt/jquery.easing.js') }}"></script>
    {{-- 数字滚动 --}}
    <script src="{{ asset('js/jqueryCountup/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jqueryCountup/jquery.countup.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            // banner初始化
            if (!$.fn.slick) return;
            // 首页 banner
            $('#banner').slick({
                autoplay: true,
                autoplaySpeed: 4000, //以毫秒为单位的自动播放速度
                centerMode: true, //居中视图   slidesToShow为双数的时候慎用
                centerPadding: '0px', //左右两侧padding值
                arrows: false, //上一下，下一页
                fade: false, //启用淡入淡出
                dots: true, //显示点指示符
                speed: 500, //幻灯片/淡入淡出动画速度
                cssEase: 'ease', //CSS3动画缓和
                slidesToShow: 1, //显示的幻灯片数量
                slidesToScroll: 1, //要滚动的幻灯片数量
                focusOnSelect: true, //启用选定元素的焦点（单击）
                touchThreshold: 300, //滑动切换阈值，即滑动多少像素后切换
                infinite: true, //无限循环
                swipeToSlide: true, //允许用户将幻灯片直接拖动或滑动到幻灯片
                lazyLoad: 'ondemand', //接受'ondemand'或'progressive'<img data-lazy="img/lazyfonz1.png"/>
                variableWidth: false, //幻灯片宽度自适应
                adaptiveHeight: false, //自适应高度
                rows: 1, //将其设置为1以上将初始化网格模式。使用slidesPerRow设置每行应放置多少个幻灯片
                slidesPerRow: 1, //在通过行选项初始化网格模式时，这会设置每个网格行中的幻灯片数量
            });
            // 数字滚动初始化
            $('.counter').countUp();
            // product zone3D轮播图
            $('.roundabout_box ul').roundabout({
                easing: 'easeOutInCirc',
				duration: 1000,
				minScale: 0.6,
				autoplay: false,
				autoplayDuration: 1500,
				minOpacity: 1,
				maxOpacity: 1,
				reflect: false,
				startingChild: 3,
				autoplayInitialDelay: 5000,
				autoplayPauseOnHover: false,
				enableDrag: true,
            });

        });
        $(function () {
            // 油管视频API搭建
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            var player;
            var videoID = $("#youtubeVideoID").attr("data-video-id");

            function onYouTubeIframeAPIReady() {
                player = new YT.Player('player', {
                    height: '330',
                    width: '100%',
                    videoId: videoID,
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            }

            function onPlayerReady(event) {
                event.target.playVideo();
            }

            var done = false;

            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING && !done) {
                    done = true;
                }
            }

            function stopVideo() {
                player.stopVideo();
            }
        });
    </script>
@endsection
