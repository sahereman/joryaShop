@extends('layouts.app')
@section('title', '卓雅美业')

@section('content')
    <div class="home-page">
        <div class="swiper-container banner" id="banner">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <a>
                        <img src="{{ asset('img/banner/banner_1.png') }}">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a>
                        <img src="{{ asset('img/banner/banner_1.png') }}">
                    </a>
                </div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <!--新品首发-->
        <div class="new_product product-part">
            <div class="m-wrapper">
                <h3>新品首发</h3>
                @if(isset($posters) && $posters->isNotEmpty() && $posters->count() >= 3)
                    <div class="new_product_left pull-left">
                        <div class="product_left_top">
                            <img src="{{ $posters[0]->image_url }}">
                        </div>
                        <div class="product_left_bottom">
                            <img src="{{ $posters[1]->image_url }}">
                        </div>
                    </div>
                    <div class="new_product_right pull-left">
                        <img src="{{ $posters[2]->image_url }}">
                    </div>
                @else
                    <div class="new_product_left pull-left">
                        <div class="product_left_top">
                            <img src="{{ asset('img/new_pro_1.png') }}">
                            <div>
                                <h2>糖果色片染</h2>
                                <p>修颜减龄，风格前卫</p>
                                <a class="info_more" href="{{ route('root') }}">查看更多</a>
                            </div>
                        </div>
                        <div class="product_left_bottom">
                            <img src="{{ asset('img/new_pro_3.png') }}">
                            <div>
                                <h2>欧式BOBO紫灰</h2>
                                <p>修颜减龄，风格前卫</p>
                                <a class="info_more" href="{{ route('root') }}">查看更多</a>
                            </div>
                        </div>
                    </div>
                    <div class="new_product_right pull-left">
                        <img src="{{ asset('img/new_pro_2.png') }}">
                        <div>
                            <h2>时尚渐变色 风格前卫</h2>
                            <a class="info_more" href="{{ route('root') }}">查看更多</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!--时尚趋势-->
        @foreach($products as $category_products)
            <div class="fashion_trend product-part">
                <div class="m-wrapper">
                    <div class="part_title">
                        <h3>{{ $category_products['category']->name_zh }}</h3>
                        <ul class="pull-right">
                            @foreach($category_products['children'] as $key => $child)
                                @if($key > 2)
                                    @break
                                @endif
                                <li>
                                    <a href="{{ route('product_categories.index', ['category' => $child->id]) }}">{{ $child->name_zh }}</a>
                                    <span>/</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="content">
                        <ul>
                            @foreach($category_products['products'] as $product)
                                <li>
                                    <a href="{{ url('products/' . $product->id) }}">
                                        <img src="{{ $product->thumb }}">
                                        <h5>{{ $product->name_zh }}</h5>
                                        <span>{{ $product->description_zh }}</span>
                                        <p class="product_price">&yen; {{ $product->price }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
                    <!--猜你喜欢-->
            <div class="guess_like product-part">
                <div class="m-wrapper">
                    <h3>猜你喜欢</h3>
                    <ul class="guess_lists">
                        @foreach($guesses as $guess)
                            <li>
                                <div class="guess_list_img">
                                    <div class="guess_list_tips">
                                        <img src="{{ asset('img/guess_tips.png') }}">
                                    </div>
                                    <img src="{{ $guess->thumb }}">
                                </div>
                                <h5>{{ $guess->name_zh }}</h5>
                                <p class="guess_price">
                                    <span class="new_price">&yen; {{ $guess->price }}</span>
                                    <span class="old_price">&yen; {{ bcadd($guess->price, random_int(300, 500), 2) }}</span>
                                </p>
                                <a class="buy_now_guess" href="{{ url('products/' . $guess->id) }}">立即购买</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            var swiper = new Swiper('.swiper-container', {
                centeredSlides: true,
                loop: true,
                speed: 1500,
                // effect : 'cube',
                fadeEffect: {
                    crossFade: true,
                },
                autoplay: {
                    delay: 3000,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
@endsection
