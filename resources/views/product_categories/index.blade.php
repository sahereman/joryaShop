@extends('layouts.app')
@section('keywords', $category->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $category->seo_description ? : \App\Models\Config::config('description'))
@section('title', $category->seo_title ? : (App::isLocale('zh-CN') ? $category->name_zh : $category->name_en) . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="productCate my_orders">
        <!--商品分类导图-->
        <div class="swiper-container Taxonomy" id="Taxonomy">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    @if(!empty($category->banner))
                        <img class="lazy" data-src="{{ $category->banner_url }}">
                    @else
                        <div class="text_intru">
                            <p>{{ App::isLocale('zh-CN') ? $category->name_zh : $category->name_en }}</p>
                            <p>{{ App::isLocale('zh-CN') ? $category->description_zh : $category->description_en }}</p>
                        </div>
                        <img class="lazy" data-src="{{ asset('defaults/defaults_pc_category_banner.png') }}">
                    @endif
                </div>
            </div>
        </div>
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    {!! $crumbs !!}
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
                            <a href="{{ route('product_categories.index', ['category' => $child->id]) }}"><span>{{ App::isLocale('zh-CN') ? $child->name_zh : $child->name_en }}</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!--商品分类展示-->
            @foreach($children as $child)
                <div class="classified-display">
                    <div class="classified-title">
                        <h3 title="{{ App::isLocale('zh-CN') ? $child->name_zh : $child->name_en }}">
                            {{ App::isLocale('zh-CN') ? $child->name_zh : $child->name_en }}
                        </h3>
                        <p title="{{ App::isLocale('zh-CN') ? strip_tags($child->description_zh) : strip_tags($child->description_en) }}">
                            {!! App::isLocale('zh-CN') ? $child->description_zh : $child->description_en !!}
                        </p>
                    </div>
                    <div class="classified-products">
                        <ul class="classified-lists">
                            @foreach($products[$child->id] as $product)
                                <li>
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                        <div class="list-img">
                                            <img class="lazy" data-src="{{ $product->thumb_url }}">
                                        </div>
                                        <div class="list-info">
                                            <p title="{{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}" class="list-info-title">
                                                {{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}
                                            </p>
                                            <p>
                                                {{--<span class="old-price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? bcmul($product->price_in_usd, 1.2, 2) : bcmul($product->price, 1.2, 2) }}</span>--}}
                                                {{--<span class="new-price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $product->price_in_usd : $product->price }}</span>--}}
                                                <span class="old-price"><i>{{ get_global_symbol() }} </i>{{ bcmul(get_current_price($product->price), 1.2, 2) }}</span>
                                                <span class="new-price"><i>{{ get_global_symbol() }} </i>{{ get_current_price($product->price) }}</span>
                                            </p>
                                        </div>
                                    </a>
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
