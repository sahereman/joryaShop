@extends('layouts.mobile')
@section('title', (App::isLocale('en') ? 'Search Results' : '搜索结果') . ' - ' . \App\Models\Config::config('title'))
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
                <div class="zonghe fillterItem goodsFillterActive" code='index'>
                    @lang('product.Comprehensive')
                </div>
                <div class="fillterItem" code="sales">
                    @lang('product.Sales volume')
                    <span></span>
                </div>
                <div class="fillterItem price" code="price_desc">
                    @lang('product.price')
                    <div class="liftingBox">
                        <span class="up" code='price_asc'><i code='price_asc' class="w-icon-arrow arrow-up"></i></span>
                        <span class="down" code='price_desc'><i code='price_desc' class="w-icon-arrow arrow-down"></i></span>
                    </div>
                </div>
                <div class="fillterItem" code="heat">
                    @lang('product.Popularity')
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
        <div class="goodsListMain" code="{{ App::isLocale('en') ? 'en' : 'zh' }}">
            <p class="no_results dis_n">@lang('product.not found')@lang('product.related products')</p>
            <div class="lists"></div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript" src="{{ asset('static_m/js/dropload/dropload.min.js') }}"></script>
    <script type="text/javascript">
        //页面单独JS写这里
        $(".goodsListFillter .fillterItem").on("click", function () {
            $(".goodsListFillter div").removeClass("goodsFillterActive");
            $(this).addClass("goodsFillterActive");
            if ($(this).hasClass("price")) {
                if ($(this).attr('code') == 'price_desc') {
                    $(this).attr('code', 'price_asc');
                    $(this).find('.up').css('opacity', '1');
                    $(this).find('.down').css('opacity', '0');
                } else {
                    $(this).attr('code', 'price_desc');
                    $(this).find('.up').css('opacity', '0');
                    $(this).find('.down').css('opacity', '1');
                }
            } else {
                $(this).parents('.goodsListFillter').find('.up').css('opacity', '1');
                $(this).parents('.goodsListFillter').find('.down').css('opacity', '1');
            }
            $(".dropload-down").remove();
            $(".lists").children().remove();
            getResults();
        });
        $("#ipt").on("focus", function () {
            window.location.href = "{{route('mobile.search')}}"+"?search_con="+$("#ipt").val();
        });
        $(".goodsListMain").on('click', '.goodsListItem', function () {
            window.location.href = "{{  config('app.url') }}" + "/mobile/products/" + $(this).attr('code');
        });
        window.onload = function () {
            getResults();
        };
        /*获取url参数*/
        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null)
                return decodeURI(r[2]);
            return null;
        }
        //获取商品列表
        function getResults() {
            // 页数
            var page = 1;
            $('.goodsListMain').dropload({
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
                    var sort = $(".goodsListFillter").find(".goodsFillterActive").attr("code");
                    var data = {
                        query: getQueryString("query"),
                        sort: sort,
                        page: page,
                    };
                    $.ajax({
                        type: "get",
                        url: $(".for_url").attr("data-url"),
                        data: data,
                        dataType: 'json',
                        success: function (data) {
                            var dataobj = data.data.products.data;
                            var html = "";
                            var name, symbol, price;
                            if (dataobj.length > 0) {
                                $.each(dataobj, function (i, n) {
                                    name = ($(".goodsListMain").attr("code") == "en") ? n.name_en : n.name_zh;
                                    // symbol = ($(".goodsListMain").attr("code") == "en") ? "&#36;" : "&#165;";
                                    // price = ($(".goodsListMain").attr("code") == "en") ? n.price_in_usd : n.price;
                                    symbol = global_symbol;
                                    price = get_current_price(n.price);
                                    html += "<div class='goodsListItem' code='" + n.id + "'>";
                                    html += "<img class='lazy' src='" + n.thumb_url + "' >";
                                    html += "<div class='goodsItemBlock'>";
                                    html += "<div class='goodsBlockName'>" + name + "</div>";
                                    html += "<div class='goodsBlockPrice'>" + symbol + price + "</div>";
                                    html += "</div>";
                                    html += "</div>";
                                });
                                // 如果没有数据
                            } else {
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                                if (page == 1) {
                                    $(".no_results").removeClass("dis_n");
                                    $(".dropload-down").remove();
                                }
                            }
                            $(".goodsListMain .lists").append(html);
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
        }
    </script>
@endsection
