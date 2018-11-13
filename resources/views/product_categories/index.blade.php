@extends('layouts.app')
@section('title', '商品分类')
@section('content')
    @include('common.error')
    <div class="productCate my_orders">
        <!--商品分类导图-->
        <div class="swiper-container Taxonomy" id="Taxonomy">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <a>
                        <img src="{{ asset('img/banner-2.png') }}">
                    </a>
                </div>
            </div>
        </div>
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="#">商品分类</a>
                </p>
            </div>
            <div class="classification-level">
                <p class="level_title">分类：</p>
                <ul>
                    <li class="active">
                        <a href="#"><span>全部</span></a>
                    </li>
                    @foreach($children as $child)
                        <li>
                            <a href="{{ route('product_categories.index', ['category' => $child->id]) }}"><span>{{ $child->name_zh }}</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!--商品分类展示-->
            @foreach($children as $child)
                <div class="classified-display">
                    <div class="classified-title">
                        <h3>{{ $child->name_zh }}</h3>
                        <p>{{ $child->description_zh }}</p>
                    </div>
                    <div class="classified-products">
                        <ul class="classified-lists">
                            @foreach($products[$child->id] as $product)
                                <li>
                                    <div class="list-img">
                                        <img src="{{ $product->thumb_url }}">
                                    </div>
                                    <div class="list-info">
                                        <p class="list-info-title">{{ $product->name_zh }}</p>
                                        <p>
                                            <span class="old-price"><i>&yen; </i>{{ bcadd($product->price, random_int(100, 300), 2) }}</span>
                                            <span class="new-price"><i>&yen; </i>{{ $product->price }}</span>
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">

    </script>
@endsection
