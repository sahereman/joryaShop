@extends('layouts.mobile')
@section('title', '商品详情')
@section('content')
    <div class="goodsDetailBox">
        <img src="{{ asset('static_m/img/icon_back.png') }}" class="gBack" onclick="javascript:history.back(-1);"/>
        <div class="goodsSwiper swiper-container">
            <div class="swiper-wrapper">
                @foreach($product->photo_urls as $photo_url)
                    <div class="swiper-slide">
                        <img src="{{ $photo_url }}">
                    </div>
                @endforeach
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
        </div>
        <div class="goodsPresent">
            <div class="gName">
                {{ App::isLocale('en') ? $product->name_en : $product->name_zh }}
            </div>
            <div class="gPrice">
                <span>@lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->price_in_usd : $product->price }}</span>
                <s>@lang('basic.currency.symbol') {{ App::isLocale('en') ? bcmul($product->price_in_usd, 1.2, 2) : bcmul($product->price, 1.2, 2) }}</s>
            </div>
            <div class="gStock">
                <span>@lang('product.product_details.freight')
                    : @lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->shipping_fee_in_usd : $product->shipping_fee }}</span>
                <span>@lang('product.product_details.sales'): {{ $product->sales }}</span>
                <span>@lang('product.product_details.stock'): {{ $product->stock }}</span>
            </div>
            <div class="gExplain">
                <div>
                    <img src="{{ asset('static_m/img/icon_Certified.png') }}" alt=""/>
                    <span>@lang('product.product_details.no reason for a refund within seven days')</span>
                </div>
                <div>
                    <img src="{{ asset('static_m/img/icon_Certified.png') }}" alt=""/>
                    <span>@lang('product.product_details.Quick refund in 48 hours')</span>
                </div>
            </div>
        </div>
        <div class="gChoose">
            <div class="gChooseBox">
                <span>@lang('product.product_details.Please select specifications')</span>
                <img src="{{ asset('static_m/img/icon_more.png') }}" alt=""/>
            </div>
        </div>
        <div class="goodsIntroduction">
            <div class="gIntroHead">
                <span class="gIntroHeadActive">商品详情</span>
                <span>商品评价</span>
            </div>
            <div class="gIntroCon">
                <div class="gIntroConDetail">
                    {{ App::isLocale('en') ? $product->content_en : $product->content_zh }}
                </div>
                <div class="gIntroConEvaluate">
                    <div class="gEvaHead">
                        <span class="gEvaHeadActive">全部({{ $comment_count }})</span>
                        <span>有图({{ $photo_comment_count }})</span>
                    </div>
                    {{-- TODO ... products.comment ... --}}
                    @for($i = 0; $i < 2; $i++)
                        <div class="commentDetail">
                            <div class="comUser">
                                <img src="{{ asset('static_m/img/icon_Headportrait3.png') }}" class="userHead"/>
                                <span>谭某某</span>
                                <div class="starBox">
                                    <img src="{{ asset('static_m/img/icon_Starsup.png') }}"/>
                                    <img src="{{ asset('static_m/img/icon_Starsup.png') }}"/>
                                    <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                    <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                    <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                </div>
                            </div>
                            <div class="comSku">
                                <span>尺寸:1.8cm</span>
                                <span>颜色:深棕色</span>
                            </div>
                            <div class="comCon">
                                送货快，包装好，品质好，喜欢的妹子可以下单了~
                            </div>
                            <div class="comPicture">
                                <img src="{{ asset('static_m/img/Advancedcustomization_02.png') }}"/>
                                <img src="{{ asset('static_m/img/Advancedcustomization_02.png') }}"/>
                            </div>
                            <div class="comDate">
                                2018-10-11
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="gFooter">
            <div class="gList">
                <div class="gShare">
                    <img src="{{ asset('static_m/img/icon_share4.png') }}" alt=""/>
                    <span>分享</span>
                </div>
                <div class="backCart">
                    <img src="{{ asset('static_m/img/icon_ShoppingCart5.png') }}" alt=""/>
                    <span>购物车</span>
                </div>
                <div class="gCollect">
                    <img src="{{ asset('static_m/img/icon_Collection4.png') }}" alt=""/>
                    <span>收藏</span>
                </div>
            </div>
            <div class="addCart">加入购物车</div>
            <div class="buy">立即购买</div>
        </div>
        <div class="skuBox">
            <div class="mask"></div>
            <div class="skuCon">
                <div class="skuGoods">
                    <img src="{{ $product->thumb_url }}"/>
                    <div>
                        <label>
                            @lang('basic.currency.symbol')
                            <span>{{ App::isLocale('en') ? $skus[0]->price_in_usd : $skus[0]->price }}</span>
                        </label>
                        <p>
                            @lang('product.product_details.stock'):
                            <span>{{ $skus[0]->stock }}</span>
                        </p>
                        <span>
                            选择:{{ App::isLocale('en') ? $skus[0]->name_en : $skus[0]->name_zh }}
                        </span>
                    </div>
                </div>
                <div class="skuListBox">
                    <div class="skuListHead">分类</div>
                    <div class="skuListMain">
                        @foreach($skus as $sku)
                            <li code_price='{{ App::isLocale('en') ? $sku->price_in_usd : $sku->price }}'>
                                <span>{{ App::isLocale('en') ? $sku->name_en : $sku->name_zh }}</span>
                                <input type="hidden" name="sku_id" value="{{ $sku->id }}">
                            </li>
                        @endforeach
                    </div>
                </div>
                <div class="buyNum">
                    <span>购买数量</span>
                    <div>
                        <span>-</span>
                        <span class="gNum">1</span>
                        <span>+</span>
                    </div>
                </div>
                <div class="btnBox">
                    <button>确定</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        var mySwiper = new Swiper('.swiper-container', {
            loop: true,
            // 如果需要分页器
            pagination: '.swiper-pagination',
            autoplay: 3000,
            stopOnLastSlide: true,

        });
        //商品详情与商品评价切换
        $(".gIntroHead>span").on("click", function () {
            $(this).addClass("gIntroHeadActive").siblings().removeClass("gIntroHeadActive");
            //通过 .index()方法获取元素下标，从0开始，赋值给某个变量
            var _index = $(this).index();
            //让内容框的第 _index 个显示出来，其他的被隐藏
            $(".gIntroCon>div").eq(_index).show().siblings().hide();
        });
        //全部和有图进行切换
        $(".gEvaHead span").on("click", function () {
            $(this).addClass("gEvaHeadActive").siblings().removeClass("gEvaHeadActive");
        });
        $(".skuListMain span").on("click", function () {
            $(this).addClass("skuActive").siblings().removeClass("skuActive");
        });
        $(".btnBox button").on("click", function () {
            $(".skuBox").css("display", "none");
        });
        //点击购物车
        $(".backCart").on("click", function () {
            window.location.href = "{{route('mobile.carts.index')}}";
        });
        //点击收藏
        $(".gCollect").on("click", function () {
            $(".skuBox").css("display", "block");

        });
        //点击加入购物车
        $(".addCart").on("click", function () {
            $(".skuBox").css("display", "block");
        });
        //点击立即购买
        $(".buy").on("click", function () {
            $(".skuBox").css("display", "block");
        });
        //点击选择规格
        $(".gChooseBox").on("click", function () {
            $(".skuBox").css("display", "block");
        });
    </script>
@endsection
