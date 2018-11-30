@extends('layouts.mobile')
@section('title', '卓雅美业')
@section('content')
    <div class="main">
        <div class="searchBox">
            <a href="{{route('mobile.search')}}" class="searchCon">
                <img src="{{ asset('static_m/img/Unchecked_search.png') }}"/>
                {{-- TODO ... placeholder --}}
                <input type="text" name="" id="" value="" placeholder="搜索商品，供12351款好货" readonly="readonly"/>
            </a>
            <a href="{{ route('mobile.locale.show') }}" class="LanguageSwitch">
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
                        <img src="{{ asset('defaults/defaults_mobile_banner.png') }}" class="main-img">
                    </div>
                    <div class="swiper-slide swiper-slideL">
                        <img src="{{ asset('defaults/defaults_mobile_banner.png') }}" class="main-img">
                    </div>
                @endif
            </div>
            <div class="swiper-pagination" id="pagination"></div>
        </div>
        <div class="proBox">
            <div class="new_pro">
                <div class="new_title">
                    <img src="{{ asset('static_m/img/Title_New.png') }}"/>
                    <span class="new_name">新品首发</span>
                </div>
                <div class="swiper-container swiper-containers">
                    <div class="swiper-wrapper">
                        @foreach($latest as $product)
                            <div class="swiper-slide swiper-slides"
                                 data-url="{{route('mobile.products.show', ['product' => $product->id])}}">
                                <img src="{{ $product->thumb_url }}"/>
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
                @if($key % 2 == 1)
                    <div class="block_trend">
                        <div class="block_title">
                            <span>{{ App::isLocale('en') ? $category_products['category']->name_en : $category_products['category']->name_zh }}</span>
                            <a href="{{ route('product_categories.index', ['category' => $category_products['category']->id]) }}">更多></a>
                        </div>
                        @if($poster = \App\Models\Poster::getPosterBySlug('mobile_index_floor_' . $key))
                            <a class="buy_now" href="{{ $poster->link }}">
                                <img src="{{ $poster->image_url }}" class="block_theme"/>
                            </a>
                        @else
                            <img src="{{ asset('defaults/default_mobile_index_floor_odd.png') }}" class="block_theme"/>
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
                                        <img src="{{ $product->thumb_url }}"/>
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
                            <a href="{{ route('product_categories.index', ['category' => $category_products['category']->id]) }}">更多></a>
                        </div>
                        @if($poster = \App\Models\Poster::getPosterBySlug('mobile_index_floor_' . $key))
                            <a class="buy_now" href="{{ $poster->link }}">
                                <img src="{{ $poster->image_url }}" class="block_theme"/>
                            </a>
                        @else
                            <img src="{{ asset('defaults/default_mobile_index_floor_even.png') }}" class="block_theme"/>
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
                                        <img src="{{ $product->thumb_url }}"/>
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
            <div class="pro_rec ">
                <div class="new_title">
                    <img src="{{ asset('static_m/img/Title_Like.png') }}"/>
                    <span class="new_name">商品推荐</span>
                </div>
                <div class="recBox">
                    @foreach($guesses as $k => $guess)
                        <div class="recItem">
                            <a href="{{ route('products.show', ['product' => $guess->id]) }}">
                                <img src="{{ $guess->thumb_url }}"/>
                                <div class="block_name">{{ App::isLocale('en') ? $guess->name_en : $guess->name_zh }}</div>
                                <span class="block_price">@lang('basic.currency.symbol') {{ App::isLocale('en') ? $guess->price_in_usd : $guess->price }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{--footer子视图--}}
        @include('layouts._footer_mobile')
    </div>


@endsection


@section('scriptsAfterJs')
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

            }
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
        })

    </script>
@endsection
