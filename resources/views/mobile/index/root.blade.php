@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Lyricalhair' : '莱瑞美业')
@section('content')
    <div class="main">
        <div class="searchBox">
            <a href="{{route('mobile.search')}}" class="searchCon">
                <img src="{{ asset('static_m/img/Unchecked_search.png') }}"/>
                <input type="text" style="text-align: center;" value="" placeholder="@lang('app.Search for goods for good goods')" readonly="readonly"/>
            </a>
            <a href="javascript:void(0);" data-href="{{ route('mobile.locale.show') }}"
               code="{{ App::isLocale('en') ? '0' : '1' }}" class="LanguageSwitch">
                <img src="{{ App::isLocale('en') ? asset('static_m/img/English.png') : asset('static_m/img/chinese.png') }}"
                     alt="" class="langImg"/>
                <span></span>
            </a>
        </div>
        <!-- Swiper -->
        <div class="swiper-container swiper-containerL">
            <div class="swiper-wrapper">
                @if(isset($banners) && $banners->isNotEmpty())
                    @foreach($banners as $banner)
                        <div class="swiper-slide swiper-slideL">
                            <img src="{{ $banner->image_url }}" class="main-img">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide swiper-slideL">
                        <img src="{{ asset('defaults/default_mobile_banner.png') }}" class="main-img">
                    </div>
                    <div class="swiper-slide swiper-slideL">
                        <img src="{{ asset('defaults/default_mobile_banner.png') }}" class="main-img">
                    </div>
                @endif
            </div>
            <div class="swiper-pagination" id="pagination"></div>
        </div>
        <div class="proBox">
            <div class="new_pro">
                <div class="new_title">
                    <span class="new_name">@lang('app.New Product Starter')</span>
                </div>
                <div class="swiper-container swiper-containers">
                    <div class="swiper-wrapper">
                        @foreach($latest as $product)
                            <div class="swiper-slide swiper-slides"
                                 data-url="{{route('mobile.products.show', ['product' => $product->id])}}">
                                <img class="lazy" data-src="{{ $product->thumb_url }}"/>
                                <div class="new_pro_name">{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}</div>
                                <span class="new_pro_price">
                                    @lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->price_in_usd : $product->price }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @foreach($products as $key => $category_products)
                @if(($key+1) % 2 == 1)
                    <div class="block_trend">
                        <div class="block_title">
                            <span>{{ App::isLocale('en') ? $category_products['category']->name_en : $category_products['category']->name_zh }}</span>
                            <a href="{{ route('mobile.product_categories.index') . '?category=' . $category_products['category']->id }}">@lang('app.More')></a>
                        </div>
                        @if($poster = \App\Models\Poster::getPosterBySlug('mobile_index_floor_' . ($key+1)))
                            <a class="buy_now" href="{{ $poster->link }}">
                                <img data-src="{{ $poster->image_url }}" class="block_theme lazy"/>
                            </a>
                        @else
                            <img data-src="{{ asset('defaults/default_mobile_index_floor_odd.png') }}" class="block_theme lazy"/>
                        @endif
                        <div class="blockBox">
                            @foreach($category_products['products'] as $k => $product)
                                @if($category_products['products']->count() > 3 && $category_products['products']->count() < 6)
                                    @if($k > 2)
                                        @break
                                    @endif
                                @else
                                    @if($k > 5)
                                        @break
                                    @endif
                                @endif
                                <div class="blockItem">
                                    <a href="{{ route('mobile.products.show', ['product' => $product->id]) }}">
                                        <img class="lazy" data-src="{{ $product->thumb_url }}"/>
                                        <div class="block_name">{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}</div>
                                        <span class="block_price">
                                            @lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->price_in_usd : $product->price }}
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="block_trend">
                        <div class="block_title">
                            <span>{{ App::isLocale('en') ? $category_products['category']->name_en : $category_products['category']->name_zh }}</span>
                            <a href="{{ route('mobile.product_categories.index') . '?category=' . $category_products['category']->id }}">@lang('app.More')></a>
                        </div>
                        @if($poster = \App\Models\Poster::getPosterBySlug('mobile_index_floor_' . ($key+1)))
                            <a class="buy_now" href="{{ $poster->link }}">
                                <img data-src="{{ $poster->image_url }}" class="block_theme lazy"/>
                            </a>
                        @else
                            <img data-src="{{ asset('defaults/default_mobile_index_floor_even.png') }}" class="block_theme lazy"/>
                        @endif
                        <div class="blockBox">
                            @foreach($category_products['products'] as $k => $product)
                                @if($category_products['products']->count() > 3 && $category_products['products']->count() < 6)
                                    @if($k > 2)
                                        @break
                                    @endif
                                @else
                                    @if($k > 5)
                                        @break
                                    @endif
                                @endif
                                <div class="blockItem blockItemCus">
                                    <a href="{{ route('mobile.products.show', ['product' => $product->id]) }}">
                                        <img class="lazy" data-src="{{ $product->thumb_url }}"/>
                                        <div class="block_name">{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}</div>
                                        <span class="block_price">
                                            @lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->price_in_usd : $product->price }}
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="pro_rec" data-url="{{ route('mobile.guess_more') }}" code="{{ App::isLocale('en') ? 'en' : 'zh' }}">
                <div class="new_title">
                    <span class="new_name">@lang('app.Featured Products')</span>
                </div>
                <div class="recBox">
                	
                    {{--@foreach($guesses as $k => $guess)
                        <div class="recItem">
                            <a href="{{ route('mobile.products.show', ['product' => $guess->id]) }}">
                                <img class="lazy" data-src="{{ $guess->thumb_url }}"/>
                                <div class="block_name">{{ App::isLocale('en') ? $guess->name_en : $guess->name_zh }}</div>
                                <span class="block_price">@lang('basic.currency.symbol') {{ App::isLocale('en') ? $guess->price_in_usd : $guess->price }}</span>
                            </a>
                        </div>
                    @endforeach--}}
                </div>
            </div>
        </div>
        @include('layouts._footer_mobile')
    </div>
@endsection

@section('scriptsAfterJs')
<script type="text/javascript" src="{{ asset('static_m/js/dropload/dropload.min.js') }}"></script>
    <script type="text/javascript">
        var mySwiper = new Swiper('.swiper-containerL', {
            slidesPerView: 'auto',
            centeredSlides: true,
            watchSlidesProgress: true,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            paginationBulletRender: function (index, className) {
                return '<span class="' + className + '"><i></i></span>';
            },
            onProgress: function (swiper) {
                for (var i = 0; i < swiper.slides.length; i++) {
                    var slide = swiper.slides[i];
                    var progress = slide.progress;
                    scale = 1 - Math.min(Math.abs(progress * 0.2), 1);
                    es = slide.style;
                    es.opacity = 1 - Math.min(Math.abs(progress / 2), 1);
                    es.webkitTransform = es.MsTransform = es.msTransform = es.MozTransform = es.OTransform = es.transform = 'translate3d(0px,0,' + (-Math.abs(progress * 150)) + 'px)';
                }
            },
            onSetTransition: function (swiper, speed) {
                for (var i = 0; i < swiper.slides.length; i++) {
                    es = swiper.slides[i].style;
                    es.webkitTransitionDuration = es.MsTransitionDuration = es.msTransitionDuration = es.MozTransitionDuration = es.OTransitionDuration = es.transitionDuration = speed + 'ms';
                }
            },
        });
        var swiper = new Swiper('.swiper-containers', {
            slidesPerView: 2.7,
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
        //点击第二个轮播中商品进行跳转
        $(".swiper-containers").on("click", '.swiper-slides', function () {
            window.location.href = $(this).attr("data-url");
        });
        //点击切换语言跳转
        $(".LanguageSwitch").on("click", function () {
            window.location.href = $(this).attr("data-href") + "?language_type=" + $(this).attr("code");
        });
        //获取最新商品推荐
            // 页数
            var page = 1;
            $('.pro_rec').dropload({
                scrollArea: window,
                domDown: { // 下方DOM
                    domClass: 'dropload-down',
                    domRefresh: "<div class='dropload-refresh'>↑@lang('product.product_details.Pull up load more')</div>",
                    domLoad: "<div class='dropload-load'><span class='loading'></span>@lang('product.product_details.Loading in')...</div>",
                    domNoData: "<div class='dropload-noData'>@lang('product.product_details.over the end')</div>"
                },
                loadDownFn: function (me) {
                    // 拼接HTML
                    var html = '';
                    var data = {
                        page: page,
                    };
                    $.ajax({
                        type: "get",
                        url: $(".pro_rec").attr("data-url"),
                        data: data,
                        dataType: 'json',
                        success: function (data) {
                            var dataobj = data.data.guesses.data;
                            var html = "";
                            var name, symbol, price;
                            if (dataobj.length > 0) {
                                $.each(dataobj, function (i, n) {
                                    name = ($(".pro_rec").attr("code") == "en") ? n.name_en : n.name_zh;
                                    symbol = ($(".pro_rec").attr("code") == "en") ? "&#36;" : "&#165;";
                                    price = ($(".pro_rec").attr("code") == "en") ? n.price_in_usd : n.price;
                                    html += "<div class='recItem' code='" + n.id + "'>";
                                    html += "<img class='lazy' src='" + n.thumb_url + "' >";
                                    html += "<div class='block_name'>"+ name +"</div>";
                                    html += "<span class='block_price'>"+ symbol + price + "</span>";
                                    html += "</div>";
                                });
                                // 如果没有数据
                            } else {
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                            }
                                $(".pro_rec .recBox").append(html);
                                page++;
                                // 每次数据插入，必须重置
                                me.resetload();
                        },
                        error: function (xhr, type) {
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                }
            });
        //查看商品详情product_info
        $(".pro_rec .recBox").on("click", ".recItem", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/products/" + $(this).attr("code");
        });
    </script>
@endsection
