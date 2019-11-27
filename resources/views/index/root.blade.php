@extends('layouts.app')
@section('title', App::isLocale('zh-CN') ? '莱瑞美业' : 'Lyricalhair')
@section('content')
    {{-- banner --}}
    <div class="banner">
        {{-- pc --}}
        <div class="slick" id="banner">
            @if(isset($pc_banners) && $pc_banners->isNotEmpty())
                @foreach($pc_banners as $pc_banner)
                    <div class="item item-1 slick-pc">
                        <a class="img-box" href="{{ $pc_banner->link }}">
                            <img class="banner-imgpc" src="{{ $pc_banner->image_url }}" alt="lyricalhair"/>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="item item-1">
                    <a class="img-box" href="javascript:void (0);">
                        <img class="banner-imgpc" src="{{ asset('defaults/defaults_pc_banner.jpg') }}" alt="lyricalhair"/>
                    </a>
                </div>
            @endif
        </div>
        {{-- 移动 --}}
        <div class="slick" id="bannerMobile">
            @if(isset($mobile_banners) && $mobile_banners->isNotEmpty())
                @foreach($mobile_banners as $mobile_banner)
                    <div class="item item-1 slick-mobile">
                        <a class="img-box" href="{{ $mobile_banner->link }}">
                            <img class="banner-imgmobile" src="{{ $mobile_banner->image_url }}" alt="lyricalhair">
                        </a>
                    </div>
                @endforeach
            @else
                <div class="item item-1">
                    <a class="img-box" href="javascript:void (0);">
                        <img class="banner-imgmobile" src="{{ asset('defaults/defaults_pc_banner.jpg') }}" alt="lyricalhair"/>
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
                    {{-- <span>Lyricalhair is a hair replacement system manufacturer and global online Retailer. Our first factory was established in 1999.</span> --}}
                    <span>We would like to express our deep appreciation for our loyal customers and to our new visitors.It is our hope that this outline of our history, services and mission, will demonstrate to you the great responsibility we feel towards all those who try our products.</span>
                </div>
                <div class="lyrical-info-article">
                    {{-- <span>We also offer significant savings because we are an internet based business; our overhead costs are much lower and we pass 
                        these additional savings on to you. We offer this new way to get a hair replacement which is both affordable and easy to 
                        order, without ever leaving the comfort of your home. Remember...</span> --}}
                    <span>LyricalHair has been providing dependable hair replacement system manufacturing services to our customers since 1999...</span>
                </div>
                <div class="lyrical-info-link">
                    <a href="{{ route('about_us') }}">LEARN MORE</a>
                </div>
            </div>
            <div class="qualification">
                <div class="youtube-video">
                    <div class="video-title">
                        <span>Lyricalhair Video introduction</span>
                    </div>
                    {{-- 油管播放器的页面嵌入，非API式的iframe嵌套 --}}
                    <div class="dis_n" id="youtubeVideoID" data-video-id="PblMmV3O74U">存放youtube视频id的位置</div>
                    <iframe id="player" type="text/html"
                            src="https://www.youtube.com/embed/PblMmV3O74U?showinfo=0&rel=0&autoplay=1"
                            frameborder="0" allow="autoplay" allowfullscreen>
                    </iframe>
                </div>
                {{-- 照片墙左侧 --}}
                {{-- <div class="qualification-left"> --}}
                    {{-- 左侧上部 --}}
                    {{-- <div class="qualification-left-up">
                        <img class="photo-wall-1" src="{{ asset("img/Home/photo-wall-1.png") }}" alt="lyricalhair">
                        @if($poster = \App\Models\Poster::getPosterBySlug('about_lyricalhair_up'))
                            <img class="photo-wall-6" src="{{ $poster->image_url }}" alt="lyricalhair">
                        @else
                            <img class="photo-wall-6" src="{{ asset("img/Home/photo-wall-6.png") }}" alt="lyricalhair">
                        @endif
                    </div> --}}
                    {{-- 左侧下部 --}}
                    {{-- <div class="qualification-left-down">
                        @if($poster = \App\Models\Poster::getPosterBySlug('about_lyricalhair_left'))
                            <img class="photo-wall-3" src="{{ $poster->image_url }}" alt="lyricalhair">
                        @else
                            <img class="photo-wall-3" src="{{ asset("img/Home/photo-wall-3.png") }}" alt="lyricalhair">
                        @endif
                        @if($poster = \App\Models\Poster::getPosterBySlug('about_lyricalhair_down'))
                            <img class="photo-wall-5" src="{{ $poster->image_url }}" alt="lyricalhair">
                        @else
                            <img class="photo-wall-5" src="{{ asset("img/Home/photo-wall-5.png") }}" alt="lyricalhair">
                        @endif
                    </div> --}}
                {{-- </div> --}}
                {{-- 照片墙右侧 --}}
                {{-- <div class="qualification-right">
                    @if($poster = \App\Models\Poster::getPosterBySlug('about_lyricalhair_right'))
                        <img class="photo-wall-4" src="{{ $poster->image_url }}" alt="lyricalhair">
                    @else
                        <img class="photo-wall-4" src="{{ asset("img/Home/photo-wall-4.png") }}" alt="lyricalhair">
                    @endif
                    <img class="photo-wall-2" src="{{ asset("img/Home/photo-wall-2.png") }}" alt="lyricalhair">
                </div> --}}
            </div>
        </div>
    </div>
    {{-- 数字滚动 --}}
    <div class="countup-area">
        <ul class="main-content">
            <li>
                <span class="countup-num"><span class="counter">{{ \App\Models\Config::config('in_house_staffs_and_workers') }}</span>+</span>
                <span class="countup-text">IN-HOUSE STAFFS</span>
                <span class="countup-text">AND WORKERS</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">{{ \App\Models\Config::config('machines_working_at_the_same_time') }}</span>+</span>
                <span class="countup-text">MACHINES WORKING AT</span>
                <span class="countup-text">THE SAME TIME</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">{{ \App\Models\Config::config('hair_systems_produced_and_delivered_last_year') }}</span>+</span>
                <span class="countup-text">HAIR SYSTEMS PRODUCED</span>
                <span class="countup-text">AND DELIVERED LAST YEAR</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">{{ \App\Models\Config::config('countries_across_the_world') }}</span>+</span>
                <span class="countup-text">COUNTRIES ACROSS</span>
                <span class="countup-text">THE WORLD</span>
            </li>
            <li>
                <span class="countup-num"><span class="counter">{{ \App\Models\Config::config('client_retention_rate') }}</span>%</span>
                <span class="countup-text">CLIENT</span>
                <span class="countup-text">RETENTION RATE</span>
            </li>
        </ul>
    </div>
    {{-- 男士，女士，配件导航 --}}
    <div class="classify-type main-content">
        <div class="classify-type-men classify-type-part">
            {{-- 对应男士分类跳转 --}}
            @if($poster = \App\Models\Poster::getPosterBySlug('mens_wig'))
                <a href="{{ $poster->link }}">
                    {{-- 展示的图片 --}}
                    <img src="{{ $poster->image_url }}" alt="lyricalhair">
                    {{-- 底部遮罩层 --}}
                    <div class="classify-type-mask">
                        <span>{{ $poster->description }}</span>
                    </div>
                </a>
            @else
                <a href="javascript:void(0);">
                    {{-- 展示的图片 --}}
                    <img src="{{ asset("img/Home/classify_men.png") }}" alt="lyricalhair">
                    {{-- 底部遮罩层 --}}
                    <div class="classify-type-mask">
                        <span>Men's Hair Systems</span>
                    </div>
                </a>
            @endif
        </div>
        <div class="classify-type-lady classify-type-part">
            {{-- 对应女士分类的跳转 --}}
            @if($poster = \App\Models\Poster::getPosterBySlug('ladies_wig'))
                <a href="{{ $poster->link }}">
                    {{-- 展示的图片 --}}
                    <img src="{{ $poster->image_url }}" alt="lyricalhair">
                    {{-- 底部遮罩层 --}}
                    <div class="classify-type-mask">
                        <span>{{ $poster->description }}</span>
                    </div>
                </a>
            @else
                <a href="javascript:void(0);">
                    {{-- 展示的图片 --}}
                    <img src="{{ asset("img/Home/classify_women.png") }}" alt="lyricalhair">
                    {{-- 底部遮罩层 --}}
                    <div class="classify-type-mask">
                        <span>Women's Wigs and Hairpieces</span>
                    </div>
                </a>
            @endif
        </div>
        <div class="classify-type-wig classify-type-part">
            {{-- 对应配件分类的跳转 --}}
            @if($poster = \App\Models\Poster::getPosterBySlug('wig_accessories'))
                <a href="{{ $poster->link }}">
                    {{-- 展示的图片 --}}
                    <img src="{{ $poster->image_url }}" alt="lyricalhair">
                    {{-- 底部遮罩层 --}}
                    <div class="classify-type-mask">
                        <span>{{ $poster->description }}</span>
                    </div>
                </a>
            @else
                <a href="javascript:void(0);">
                    {{-- 展示的图片 --}}
                    <img src="{{ asset("img/Home/classify_wig.png") }}" alt="lyricalhair">
                    {{-- 底部遮罩层 --}}
                    <div class="classify-type-mask">
                        <span>Wig Accessories</span>
                    </div>
                </a>
            @endif
        </div>
    </div>
    {{-- Product Zone  --}}
    <div class="Product-Zone">
        <div class="">
            <p class="part-title">
                <img src="{{ asset("img/Home/Product_Zone.png") }}" alt="lyricalhair">
            </p>
            @if($products)                    
                {{-- 分类块 --}}
                <div class="Product-Zone-classify main-content">
                    <div class="classify-box">
                        @foreach($products as $key => $product_set)
                            <div class="Zone-classify-item {{ $key == 0 ? 'active' : '' }}">
                                <a href="javascript:void(0)" data-id="Product-Zone-{{ $key }}">{{ $product_set['category']->name_en }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                    {{-- 轮播图 --}}
                <div class="main-content Product-Zone-swiper">
                    @foreach($products as $key => $product_set)
                        <div class="swiper-container ProductZone Zone-classify-imgs {{ $key == 0 ? 'active' : '' }}" id="Product-Zone-{{ $key }}">
                            <div class="swiper-wrapper">
                                @foreach($product_set['products'] as $product)
                                    <div class="swiper-slide">
                                        <div class="slide-img Zone-classify-img">
                                            <a href="{{ route('seo_url', $product->slug) }}">
                                                <img src="{{ $product->thumb_url }}" alt="lyricalhair">
                                            </a>
                                        </div>
                                        <div class="slide-title">
                                            <a href="{{ route('seo_url', $product->slug) }}">
                                                <p title="{{ $product->name_en }}">{{ mb_strlen($product->name_en) <= 100 ? $product->name_en : substr($product->name_en, 0, 97) . ' ... ' }}</p>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- <div class="swiper-pagination ProductZone-pagination"></div> --}}
                        </div>
                        <div class="swiper-button-prev swiper-button-white ProductZone-prev"></div>
                        <div class="swiper-button-next swiper-button-white ProductZone-next"></div>
                    @endforeach
                </div>
            @endif
            {{-- 文档预览 --}}
            <div class="download-Catalog main-content">
                <img src="{{ asset("img/Home/download.png") }}" alt="lyricalhair">
                <a href="https://www.lyricalhair.com/storage/Download-Our-Catalog.pdf" class="download-btn" target="_blank">
                    <img class="normal_icon" src="{{ asset("img/Home/download-btn.png") }}" alt="lyricalhair">
                    <img class="active_icon" src="{{ asset("img/Home/download_white.png") }}" alt="lyricalhair">
                    <span>Download Our Catalog</span>
                </a>
            </div>
            {{-- Why Lyricalhair && Lyricalhair Blog --}}
            <div class="why-youtube">
                <div class="why-lyrical">
                    {{-- 第三版 --}}
                    <div class="why-lyrical-top main-content">
                        <div class="top-text">
                            {{-- <p class="why-lyrical-title">WHY CHOOSE LYRICALHAIR</p> --}}
                            <p class="part-title why-lyrical-title">
                                <img class="why-lyrical-img" src="{{ asset("img/Home/WHYCHOOSELYRICALHAIR.png") }}" alt="lyricalhair">
                            </p>
                            <a class="why-lyrical-link" href="{{ route('why_lyricalhair') }}">More details >></a>
                            <div class="why-lyrical-top-info">
                                <p>Lyricalhair operates globally through our online platforms to provide hair replacement systems at the best price available. Before we established this online mall, we mainly carried out B2B transactions with wholesalers and distributors but with the development of cross-border e-commerce, more and more individuals will bypass intermediaries and go directly to shopping online. To cater to these customers, we gradually immersed into the platform marketing of eBay, Amazon, and ALI Express using different brand names. So far good results have been achieved on these platforms with eBay and Amazon being two of our major channels having the largest number of reach on today's digitally-inclined market. For more and more customers to better enjoy our LyricalHairbrand service, we now build our own online mall.</p>
                            </div>
                        </div>
                        <div class="Certificate-anchor">
                            {{-- 证书的轮播 --}}
                            <div class="Certificate-left">
                                <div class="swiper-container Certificate" id="Certificate">
                                    <div class="swiper-wrapper">
                                        {{-- 循环的内容 --}}
                                        @if($poster = \App\Models\Poster::getPosterBySlug('why_lyricalhair'))
                                            @foreach($poster->photo_urls as $photo_url)
                                                <div class="swiper-slide">
                                                    <img src="{{ $photo_url }}" alt="lyricalhair">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="swiper-slide">
                                                <img src="{{ asset("img/Home/photo-wall-3.png") }}" alt="lyricalhair">
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="{{ asset("img/Home/photo-wall-4.png") }}" alt="lyricalhair">
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="{{ asset("img/Home/photo-wall-5.png") }}" alt="lyricalhair">
                                            </div>
                                            <div class="swiper-slide">
                                                <img src="{{ asset("img/Home/photo-wall-6.png") }}" alt="lyricalhair">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="swiper-button-prev certificate-prev"></div>
                                    <div class="swiper-button-next certificate-next"></div>
                                    <div class="swiper-pagination certificate-pagination"></div>
                                </div>
                            </div>
                            {{-- 锚点跳转 --}}
                            <div class="anchor-right">
                                <a href="{{ route('why_lyricalhair') }}#part2Item1">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon1.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Exquisite Manufacturing Expertise</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item2">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon2.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Guaranteed High Quality Products</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item3">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon3.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Affordable Factory Prices</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item4">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon4.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Customer-centric Services</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item5">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon5.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Ready To Wear</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item6">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon6.png") }}" alt="lyricalhair">
                                    </div>    
                                    <span>Easy To Order Online</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item7">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon7.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Safe Online Payment</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item8">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon8.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Fast Worldwide Shipping</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item9">
                                    <div class="icon-shell">
                                      <img src="{{ asset("img/Home/why_icon9.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Frequent Order Status Updates</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item10">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon10.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>Professional Customer Service</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item11">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon11.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>30-Day Money-Back Guarantee</span>
                                </a>
                                <a href="{{ route('why_lyricalhair') }}#part2Item12">
                                    <div class="icon-shell">
                                        <img src="{{ asset("img/Home/why_icon12.png") }}" alt="lyricalhair">
                                    </div>
                                    <span>We Pay It Forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 评论展示 --}}
            <div class="clients-say">
                <div class="clients-content main-content">
                    <p class="part-title">
                        <img src="{{ asset("img/Home/what-say.png") }}" alt="lyricalhair">
                    </p>
                    <p class="clients-say-sub">With over 20 years of experience in the hair industry, we have already served number of clients around the world. Most of them became repeat customers and some even turned into our loyal partners. It is our greatest satisfaction to be able to build such strong connections with the community we cater to and thus we are forever grateful to be considered as a trusted supplier of hair replacement systems and be highly rated by the Better Business Bureau (BBB). For this we assure you that we will continue on delivering the highest quality products available to you and will keep helping you all to Be Comfortably Beautiful.</p>
                    <p class="clients-say-sub">Look on what else our customers have to say down below:</p>
                    {{-- 评论轮播 --}}
                    <div class="clientsSay-swiper">
                        <div class="swiper-container clientsSay" id="clientsSay">
                            <div class="swiper-wrapper clientsSay-wrapper">
                                {{-- 循环的内容 --}}
                                <?php $_ii=1; while ($_ii++ < 14): ?>
                                    <div class="swiper-slide">
                                        <img src="{{ asset("img/review/$_ii.png") }}" alt="lyricalhair">
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev clientsSay-prev"></div>
                        <div class="swiper-button-next clientsSay-next"></div>
                    </div>
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
                    <span>Unique hair replacement system</span>
                    <span>Features specifically created just for You </span>
                    <span>High Quality Craftsmanship</span>
                    <span>Wear it like it is Your Own hair</span>
                    {{--<a href="{{ route('seo_url', ['slug' => \App\Models\Product::where(['type' => 'custom', 'on_sale' => 1])->first()->slug]) }}">LEARN MORE</a>--}}
                    <a href="{{ route('seo_url', ['slug' => 'custom-hair-systems']) }}">LEARN MORE</a>
                </li>
                <li>
                    <div class="part-img-box">
                        <img src="{{ asset("img/Home/repairedOrder.png") }}" alt="lyricalhair">
                    </div>
                    <span class="part-img-title">Repaired Order</span>
                    <span>Got a damaged Hair Replacement System?</span>
                    <span>We are here to the rescue!</span>
                    <span>Base System Repair, Hair Additions, and more</span>
                    <span>Get it back like it's Brand New</span>
                    <a href="{{ route('seo_url', ['slug' => 'repair-service']) }}">LEARN MORE</a>
                </li>
                <li>
                    <div class="part-img-box">
                        <img src="{{ asset("img/Home/DuplicatedOrder.png") }}" alt="lyricalhair">
                    </div>
                    <span class="part-img-title">Duplicated Order</span>
                    <span>Now you can have two or more of your</span>
                    <span>Favorite Hair Replacement Systems</span>
                    <span>Identitical from the Base to the Tips of your Hair</span>
                    <span>Free Rush Order and Half Delivery Back Fee</span>
                    <a href="{{ route('seo_url', ['slug' => 'duplicate-service']) }}">LEARN MORE</a>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    {{-- 轮播插件 --}}
    <script src="{{ asset('js/swiper/js/swiper.min.js') }}"></script>
    <script src="{{ asset('js/slick/slick.min.js') }}"></script>
    {{-- <script src="{{ asset('js/3Dlbt/jquery.roundabout.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/3Dlbt/jquery.easing.js') }}"></script> --}}
    {{-- 数字滚动 --}}
    <script src="{{ asset('js/jqueryCountup/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jqueryCountup/jquery.countup.min.js') }}"></script>
    {{-- 图片弹窗 --}}
    <script src="{{ asset('js/lord/jquery.colorbox.min.js') }}"></script>
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
            $('#bannerMobile').slick({
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
            if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)) {
            } else {
                $('.counter').countUp();
            }
            // product zone3D轮播图
            // $('.roundabout_box ul').roundabout({
            //     easing: 'easeOutInCirc',
            //     duration: 1000,
            //     minScale: 0.6,
            //     autoplay: false,
            //     autoplayDuration: 1500,
            //     minOpacity: 1,
            //     maxOpacity: 1,
            //     reflect: false,
            //     startingChild: 3,
            //     autoplayInitialDelay: 5000,
            //     autoplayPauseOnHover: false,
            //     enableDrag: true,
            // });
            // 商品中心轮播
            function initSwiper (DomId) {
                var productZoneswiper = new Swiper(DomId, {
                    // autoplay: true,
                    loop: false,
                    slidesPerView: 4,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: '.ProductZone-next',
                        prevEl: '.ProductZone-prev',
                    },
                    breakpoints: { 
                        //当宽度小于等于320
                        320: {
                        slidesPerView: 1,
                        spaceBetween: 10
                        },
                    //当宽度小于等于480
                        480: { 
                        slidesPerView: 1,
                        spaceBetween: 20
                        },
                        //当宽度小于等于640
                        640: {
                        slidesPerView: 1,
                        spaceBetween: 30
                        }
                    }
                });
            }
            initSwiper("#Product-Zone-0");
            // 证书
            var Certificateswiper = new Swiper('#Certificate', {
                // slidesPerView: 1,
                // // spaceBetween: 30,
                loop: true,
                navigation: {
                    nextEl: '.certificate-next',
                    prevEl: '.certificate-prev',
                },
                pagination: {
                    el: '.certificate-pagination',
                },
            });
            // 评论展示轮播
            var clientsSayswiper = new Swiper("#clientsSay", {
                // autoplay: true,
                loop: true,
                slidesPerView: 2,
                spaceBetween: 30,
                navigation: {
                    nextEl: '.clientsSay-next',
                    prevEl: '.clientsSay-prev',
                },
                breakpoints: { 
                    //当宽度小于等于320
                    320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                    },
                //当宽度小于等于480
                    480: { 
                    slidesPerView: 1,
                    spaceBetween: 20
                    },
                    //当宽度小于等于640
                    640: {
                    slidesPerView: 1,
                    spaceBetween: 30
                    }
                }
            });
            // 图片弹窗
            //Init lightbox  图片弹窗
            $(".zoomColorBoxs").colorbox({
                rel: 'zoomColorBoxs',
                opacity: 0.5,
                speed: 300,
                current: '{current} / {total}',
                previous: '',
                next: '',
                close: '',  //No comma here
                maxWidth: '95%',
                maxHeight: '95%'
            });
            // why lyrical 轮播
            var mySwiper = new Swiper('#whyImgBanner', {
                slidesPerView: 2,
                spaceBetween: 30,
                // 如果需要前进后退按钮
                navigation: {
                    nextEl: '.why-button-next',
                    prevEl: '.why-button-prev',
                }
            });
            // 分类中心点击切换不同的图片
            $(".classify-box").on("click",".Zone-classify-item",function(){
                var clickDom = $(this);
                if(!clickDom.hasClass("active")){
                    $(".classify-box").find(".Zone-classify-item").removeClass("active");
                    clickDom.addClass("active");
                    $(".Zone-classify-imgs").removeClass("active");
                    var active_id = "#" + clickDom.find("a").attr("data-id");
                    $(active_id).addClass("active");
                    initSwiper(active_id);
                }
            })
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
