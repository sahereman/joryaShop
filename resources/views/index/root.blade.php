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
                <a href="user_favourites"><h1>test</h1></a>
                <div class="new_product_left pull-left">
                    @foreach($products['latest'] as $latest_product)
                        <div class="product_left_top">
                            <img src="{{ $latest_product->thumb }}">
                            <div>
                                <h2>{{ $latest_product->name_zh }}</h2>
                                <p>{{ $latest_product->description_zh }}</p>
                                <a class="info_more" href="{{ url('products/' . $latest_product->id) }}">查看更多</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--时尚趋势-->
            @foreach($products['category'] as $category_products)
                <div class="fashion_trend product-part">
                    <div class="m-wrapper">
                        <div class="part_title">
                            <h3>{{ $category_products['category']->name_zh }}</h3>
                            <ul class="pull-right">
                                <li>
                                    <a href="{{ route('root') }}">直发</a>
                                    <span>/</span>
                                </li>
                                <li>
                                    <a href="{{ route('root') }}">卷发</a>
                                    <span>/</span>
                                </li>
                                <li>
                                    <a href="{{ route('root') }}">头套</a>
                                    <span>/</span>
                                </li>
                                <li>
                                    <a href="{{ route('root') }}">刘海</a>
                                    <span>/</span>
                                </li>
                                <li>
                                    <a href="{{ route('root') }}">发块</a>
                                    <span>/</span>
                                </li>
                                <li>
                                    <a href="{{ route('root') }}">佩件</a>
                                    <span>/</span>
                                </li>
                            </ul>
                        </div>
                        <div class="content">
                            <ul>
                                @foreach($category_products['products'] as $category_product)
                                    <li>
                                        <a href="{{ url('products/' . $category_product->id) }}">
                                            <img src="{{ $category_product->thumb }}">
                                            <h5>{{ $category_product->name_zh }}</h5>
                                            <span>{{ $category_product->description_zh }}</span>
                                            <p class="product_price">{{ $category_product->price }}</p>
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
                            @foreach($products['guess'] as $guess_product)
                                <li>
                                    <div class="guess_list_img">
                                        <div class="guess_list_tips">
                                            <img src="{{ asset('img/guess_tips.png') }}">
                                        </div>
                                        <img src="{{ asset('img/guess_2.png') }}">
                                    </div>
                                    <h5>时尚渐变色</h5>
                                    <p class="guess_price">
                                        <span class="new_price">255.00</span>
                                        <span class="old_price">588.00</span>
                                    </p>
                                    <a class="buy_now_guess" href="{{ route('root') }}">立即购买</a>
                                </li>
                            @endforeach
                        </ul>
                        <!--服务承诺-->
                        <div class="service_commitment">
                            <ul>
                                <li>
                                    <img src="{{ asset('img/thumb.png') }}">
                                    <p>365正品承诺</p>
                                    <p>Genuine purchasing</p>
                                </li>
                                <li>
                                    <img src="{{ asset('img/thumb.png') }}">
                                    <p>7天无理由退款</p>
                                    <p>7 days no reason to refund</p>
                                </li>
                                <li>
                                    <img src="{{ asset('img/thumb.png') }}">
                                    <p>满88元免邮费</p>
                                    <p>Free postage </p>
                                </li>
                                <li>
                                    <img src="{{ asset('img/thumb.png') }}">
                                    <p>金牌服务</p>
                                    <p>Great service</p>
                                </li>
                            </ul>
                        </div>
                    </div>
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
