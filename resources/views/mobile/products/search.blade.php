@extends('layouts.mobile')
@section('title', '商品列表')
@section('content')
    <div class="goodsListBox">
        <div class="goodsListHead">
            <div class="goodsListSearch">
                <img src="{{ asset('static_m/img/icon_backtop.png') }}" onclick="javascript:history.back(-1);"/>
                <div class="goodsListHeadBox">
                    <img src="{{ asset('static_m/img/icon_search3.png') }}" class="searchImg"/>
                    <input type="text" name="" id="ipt" value="{{ $query }}"/>
                </div>
            </div>
            <div class="goodsListFillter">
            	<div class="for_url dis_n" data-url="{{ route('products.search_more') }}"></div>
                <div class="zonghe fillterItem">
                    @lang('product.Comprehensive')
                    <div class="liftingBox">
                        <span class="up">▴</span>
                        <span class="down">▾</span>
                    </div>
                </div>
                <div class="fillterItem">
                    @lang('product.Sales volume')
                    <span></span>
                </div>
                <div class="fillterItem">
                    @lang('product.price')
                    <div class="liftingBox">
                        <span class="up">▴</span>
                        <span class="down">▾</span>
                    </div>
                </div>
                <div class="fillterItem">
                    @lang('product.Popularity')
                    <div class="liftingBox">
                        <span class="up">▴</span>
                        <span class="down">▾</span>
                    </div>
                </div>
                <div class="dropDownBox" name="isPull">
                    <div>
                        @lang('product.Comprehensive sorting')
                    </div>
                    <div>
                        @lang('product.New Products Preferred')
                    </div>
                    <div>
                        @lang('product.Comments from high to low')
                    </div>
                </div>
            </div>
        </div>
        <div class="goodsListMain">
            {{-- TODO ... search_more --}}
            @for($i = 0; $i< 14; $i++)
                <div class="goodsListItem">
                    <img src="{{ asset('static_m/img/blockImg.png') }}" alt=""/>
                    <div class="goodsItemBlock">
                        <div class="goodsBlockName">
                            糖果色片染十足立体感
                        </div>
                        <div class="goodsBlockPrice">
                            ￥129
                        </div>
                    </div>
                </div>
            @endfor

        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".zonghe").on("click", function () {
            if ($(".dropDownBox").attr("name") == "isPull") {
                $(".dropDownBox").attr("name", "pull");
                $(".dropDownBox").slideDown();
                $(".zonghe .liftingBox .up").css("display", "block");
                $(".zonghe .liftingBox .down").css("display", "none");
            } else if ($(".dropDownBox").attr("name") == "pull") {
                $(".dropDownBox").attr("name", "isPull");
                $(".dropDownBox").slideUp();
                $(".zonghe .liftingBox .up").css("display", "none");
                $(".zonghe .liftingBox .down").css("display", "block");
            }
        });
        $(".goodsListFillter .fillterItem").on("click", function () {
            $(".goodsListFillter div").removeClass("goodsFillterActive");
            $(this).addClass("goodsFillterActive");
            $(".dropDownBox").slideUp();
        });
        $(".dropDownBox div").on("click", function () {
            $('.dropDownBox div').removeClass("goodsFillterActive");
            $(this).addClass("goodsFillterActive");
            $(".dropDownBox").slideUp();
        });
        $("#ipt").on("focus", function () {
            window.location.href = "{{route('mobile.search')}}";
        });
        $(".goodsListItem").on('click', function () {
            window.location.href = "{{route('mobile.products.show',60)}}";
        });
        /*获取url参数*/
        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null)
                return decodeURI(r[2]);
            return null;
        }
        //获取商品列表
        function getResults(data, type) {
            $.ajax({
                type: "get",
                url: $(".more_load").attr("data-url"),
                data: data,
                async: type,
                beforeSend: function () {
                    loading_animation = layer.msg("@lang('app.Please wait')", {
                        icon: 16,
                        shade: 0.4,
                        time: false, //取消自动关闭
                    });
                },
                success: function (json) {
                    var dataobj = json.data.products.data;
                    var html = "";
                    var country = $("#dLabel").find("span").html();
                    var name, symbol, price;
                    if (dataobj.length > 0) {
                        $.each(dataobj, function (i, n) {
                            name = (country == "中文") ? n.name_zh : n.name_en;
                            symbol = (country == "中文") ? "&#165;" : "&#36;";
                            price = (country == "中文") ? n.price : n.price_in_usd;
                            html += "<li>" +
                                    "<a href='/products/" + n.id + "'>" +
                                    "<div class='list-img'>" +
                                    "<img src='" + n.thumb_url + "'>" +
                                    "</div>" +
                                    "<div class='list-info'>" +
                                    "<p class='list-info-title' title='" + name + "'>" + name + "</p>" +
                                    "<p>" +
                                    "<span class='new-price'><i>" + symbol + "</i>" + price + "</span>" +
                                    "<span class='old-price'><i>" + symbol + "</i>" + js_number_format(Math.imul(float_multiply_by_100(price), 12) / 1000) + "</span>" +
                                    // "<span class='old-price'><i>" + symbol + "</i>" + js_number_format(Math.ceil(price * 120) / 100) + "</span>" +
                                    // 以下方法实现js的number_format功能虽然简单，但是存在数字四舍五入不准确的问题，结果不可预知：
                                    // "<span class='old-price'><i>" + symbol + "</i>" + (Math.ceil(price * 120) / 100).toFixed(2) + "</span>" +
                                    "</p>" +
                                    "</div>" +
                                    "</a>" +
                                    "</li>";
                        });
                        loading = false;
                    } else {
                        if (json.data.products.current_page == 1) {
                            html = "<li class='empty_tips'>" +
                                    "<p>" +
                                    "<img src='{{ asset('img/warning.png') }}'>" +
                                    "@lang('product.not found')" +
                                    "“<span class='red'>" + getQueryString("query") + "</span>”@lang('product.related products')" +
                                    "</p>" +
                                    "</li>";
                        } else {
                            html = "<li class='ending_empty_tips'>" +
                                    "<p>@lang('product.All content has been loaded')</p>" +
                                    "</li>";
                        }
                        loading = true; // 当返回数组内容为空时阻止滚动条滚动
                    }
                    $(".classified-lists").append(html);
                },
                error: function (e) {
                    console.log(e);
                },
                complete: function () {
                    layer.close(loading_animation);
                }
            });
        }
    </script>
@endsection
