@extends('layouts.app')
@section('title', App::isLocale('en') ? $category->name_en : $category->name_zh)
@section('content')
    <div class="productCate my_orders">
        <!--商品分类导图-->
        <div class="swiper-container Taxonomy" id="Taxonomy">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                	<p>{{ App::isLocale('en') ? $category->name_en : $category->name_zh }}</p>
                    <img class="lazy" data-src="{{ asset('defaults/defaults_pc_category_banner.jpg') }}">
                </div>
            </div>
        </div>
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('product.Categories')</a>
                </p>
            </div>
            <div class="classification-level">
                <p class="level_title">@lang('product.product_details.classification')：</p>
                <ul>
                    <li class="active">
                        <a href="javascript:void(0);"><span>@lang('product.All')</span></a>
                    </li>
                    @foreach($children as $child)
                        <li>
                            <a href="{{ route('product_categories.index', ['category' => $child->id]) }}"><span>{{ App::isLocale('en') ? $child->name_en : $child->name_zh }}</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!--商品分类展示-->
            @foreach($children as $child)
                <div class="classified-display">
                    <div class="classified-title">
                        <h3 title="{{ App::isLocale('en') ? $child->name_en : $child->name_zh }}">{{ App::isLocale('en') ? $child->name_en : $child->name_zh }}</h3>
                        <p title="{{ App::isLocale('en') ? strip_tags($child->description_en) : strip_tags($child->description_zh) }}">
                            {!! App::isLocale('en') ? $child->description_en : $child->description_zh !!}
                        </p>
                    </div>
                    <div class="classified-products">
                        <ul class="classified-lists">
                            @foreach($products[$child->id] as $product)
                                <li>
                                    <div class="list-img">
                                        <img class="lazy" data-src="{{ $product->thumb_url }}">
                                    </div>
                                    <div class="list-info">
                                        <p title="{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}" class="list-info-title">{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}</p>
                                        <p>
                                            <span class="old-price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? bcmul($product->price_in_usd, 1.2, 2) : bcmul($product->price, 1.2, 2) }}</span>
                                            <span class="new-price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $product->price_in_usd : $product->price }}</span>
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
