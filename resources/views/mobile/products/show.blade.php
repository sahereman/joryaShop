@extends('layouts.mobile')
@section('keywords', $product->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $product->seo_description ? : \App\Models\Config::config('description'))
@section('title', $product->seo_title ? : (App::isLocale('zh-CN') ? $product->name_zh : $product->name_en) . ' - ' . \App\Models\Config::config('title'))
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
                {{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}
            </div>
            <div class="gPrice">
                {{--<span>@lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->price_in_usd : $product->price }}</span>
                <s>@lang('basic.currency.symbol') {{ App::isLocale('en') ? bcmul($product->price_in_usd, 1.2, 2) : bcmul($product->price, 1.2, 2) }}</s>--}}
                <span>{{ get_global_symbol() }} {{ get_current_price($product->price) }}</span>
                <s>{{ get_global_symbol() }} {{ bcmul(get_current_price($product->price), 1.2, 2) }}</s>
            </div>
            <div class="gStock">
                <span>@lang('product.product_details.freight')
                    {{--: @lang('basic.currency.symbol') {{ App::isLocale('en') ? $product->shipping_fee_in_usd : $product->shipping_fee }}</span>--}}
                    : {{ get_global_symbol() }} {{ get_current_price($product->shipping_fee) }}</span>
                <span>@lang('product.product_details.sales'): {{ $product->sales }}</span>
                <span>@lang('product.product_details.stock'): {{ $product->stock }}</span>
            </div>
            @if(App::isLocale('zh-CN'))
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
            @endif
        </div>
        <div class="gChoose">
            <div class="gChooseBox">
                <span>@lang('product.product_details.Please select specifications')</span>
                <img src="{{ asset('static_m/img/icon_more.png') }}" alt=""/>
            </div>
        </div>
        <div class="goodsIntroduction">
            <div class="gIntroHead">
                <span class="gIntroHeadActive">@lang('product.product_details.Commodity details')</span>
                <span class="shopping_eva"
                      data-url="{{ route('products.comment', ['product' => $product->id]) }}">@lang('product.product_details.Commodity feedback')</span>
            </div>
            <div class="gIntroCon">
                <div class="gIntroConDetail">
                    {!! App::isLocale('zh-CN') ? $product->content_zh : $product->content_en !!}
                </div>
                <div class="gIntroConEvaluate" code="{{ App::isLocale('zh-CN') ? 'zh' : 'en' }}"
                     data-url="{{ config('app.url') }}">
                    {{--<div class="gEvaHead">
                        <span class="gEvaHeadActive">全部({{ $comment_count }})</span>
                        <span>有图({{ $photo_comment_count }})</span>
                    </div>--}}
                            <!--暂无评价-->
                    <div class="no_eva dis_n">
                        <p>@lang('product.product_details.No evaluation information yet')</p>
                    </div>
                    <div class="lists"></div>
                </div>
            </div>
        </div>
        <div class="gFooter">
            <div class="gList">
                <input type="text" value="{{config('app.url')}}" class="dis_n" id="forShare">
                <div class="gShare" data-clipboard-action="copy" data-clipboard-target="#forShare">
                    <img src="{{ asset('static_m/img/icon_share4.png') }}" alt=""/>
                    <span>@lang('product.product_details.customer')</span>
                </div>
                <div class="backCart">
                    <img src="{{ asset('static_m/img/icon_ShoppingCart5.png') }}" alt=""/>
                    <span>@lang('app.Shopping Cart')</span>
                </div>
                @guest
                <div class="gCollect for_show_login" data-url="{{ route('mobile.login.show') }}">
                    <img src="{{ asset('static_m/img/icon_Collection4.png') }}" alt="" class="no_collection"/>
                    <img src="{{ asset('static_m/img/icon_Collection3.png') }}" alt="" class="had_collection dis_n"/>
                    <span>@lang('product.product_details.Collection')</span>
                </div>
                @else
                    <div class="gCollect {{ $favourite ? 'active' : '' }}" code="{{ $product->id }}"
                         data-url="{{ route('user_favourites.store') }}"
                         data-url_2="{{ $favourite ? route('user_favourites.destroy', ['favourite' => $favourite->id]) : '' }}">
                        <img src="{{ asset('static_m/img/icon_Collection4.png') }}" alt=""
                             class="no_collection {{ $favourite ? 'dis_n' : '' }}"/>
                        <img src="{{ asset('static_m/img/icon_Collection3.png') }}" alt=""
                             class="had_collection {{ $favourite ? '' : 'dis_n' }}"/>
                        @if($favourite)
                            <span>@lang('product.product_details.Favourites')</span>
                        @else
                            <span>@lang('product.product_details.Collection')</span>
                        @endif
                    </div>
                    @endguest
            </div>
            @guest
            <div class="addCart for_show_login"
                 data-url="{{ route('mobile.login.show') }}">@lang('app.Add to Shopping Cart')</div>
            <div class="buy for_show_login"
                 data-url="{{ route('mobile.login.show') }}">@lang('product.product_details.Buy now')</div>
            @else
                <div class="addCart" data-url="{{ route('carts.store') }}">@lang('app.Add to Shopping Cart')</div>
                <div class="buy"
                     data-url="{{ route('mobile.orders.pre_payment') }}">@lang('product.product_details.Buy now')</div>
                @endguest
        </div>
        <div class="skuBox">
            <div class="mask"></div>
            <div class="skuCon">
                <div class="skuGoods">
                    <img src="{{ $product->thumb_url }}"/>
                    <div>
                        <label>
                            {{--@lang('basic.currency.symbol')
                            <span id="sku_price_in_usd" class="pro_price">{{ App::isLocale('en') ? $skus[0]->price_in_usd : $skus[0]->price }}</span>--}}
                            {{ get_global_symbol() }}
                            <span id="sku_price_in_usd"
                                  class="pro_price">{{ get_current_price($skus[0]->price) }}</span>
                        </label>
                        <p>
                            @lang('product.product_details.stock'):
                            <span id="sku_stock">{{ $skus[0]->stock }}</span>
                        </p>
                        {{--<span class="pro_name">
                            @lang('product.product_details.Choose')
                            :{{ App::isLocale('en') ? $skus[0]->name_en : $skus[0]->name_zh }}
                        </span>--}}
                    </div>
                </div>
                <div class="skuListBox"
                     data-url="{{ route('products.get_sku_parameters', ['product' => $product->id]) }}">
                    @if(count($parameters) > 0)
                        @foreach($parameters as $key => $specifications)
                            <div class="skuListHead">
                                <span class="dynamic_name">{{ $key }}</span>
                                <select name="{{ $key }}">
                                    @if(count($specifications) > 0)
                                        @foreach($specifications as $index => $specification)
                                            <option value="{{ $specification }}">{{$specification}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endforeach
                    @endif
                    @if(count($skus) > 0)
                        <div class="forSkusHidde dis_n">
                            @foreach($skus as $specifications)
                                <input type="text" value="{{ $specifications }}"/>
                            @endforeach
                        </div>
                    @endif
                    {{--<div class="skuListHead kindofsize">
                        <span>@lang('product.product_details.base_size')</span>
                        <select name="base_size" title='{{ __('product.product_details.base_size') }}'>
                            @if(count($parameters['{{ __('product.product_details.base_size') }}']) > 0)
                                @foreach($parameters['{{ __('product.product_details.base_size') }}'] as $base_size)
                                    <option value="{{ $base_size }}">{{ $base_size }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="skuListHead kindofcolour">
                        <span>@lang('product.product_details.hair_colour')</span>
                        <select name="hair_colour" title='{{ __('product.product_details.hair_colour') }}'>
                            @if(count($parameters['{{ __('product.product_details.hair_colour') }}']) > 0)
                                @foreach($parameters['{{ __('product.product_details.hair_colour') }}'] as $hair_colour)
                                    <option value="{{ $hair_colour }}">{{ $hair_colour }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="skuListHead kindofdensity">
                        <span>@lang('product.product_details.hair_density')</span>
                        <select name="hair_density" title='{{ __('product.product_details.hair_density') }}'>
                            @if(count($parameters['{{ __('product.product_details.hair_density') }}']) > 0)
                                @foreach($parameters['{{ __('product.product_details.hair_density') }}'] as $hair_density)
                                    <option value="{{ $hair_density }}">{{ $hair_density }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>--}}

                </div>
                <div class="buyNum">
                    <span>@lang('product.product_details.Quantity purchased')</span>
                    <div>
                        <span class="Operation_btn">-</span>
                        <span class="gNum">1</span>
                        <span class="Operation_btn">+</span>
                    </div>
                </div>
                <div class="btnBox">
                    <button class="make_sure_todo">@lang('app.determine')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript" src="{{ asset('static_m/js/clipboard/clipboard.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static_m/js/dropload/dropload.min.js') }}"></script>
    <script type="text/javascript">
        // 页面单独JS写这里
        var mySwiper = new Swiper('.swiper-container', {
            loop: true,
            // 如果需要分页器
            pagination: '.swiper-pagination',
            autoplay: 3000,
            stopOnLastSlide: true,
        });
        var which_click = 0; // 通过判断which_click的值来确定是什么功能,0:选择规格,1:添加收藏，2：加入购物车，3：立即购买
        // var clickDom, sku_id, sku_stock, sku_price_in_usd;
        var clickDom, sku_id, sku_stock, sku_price;
        // 点击透明阴影关闭弹窗
        $(".mask").on("click", function () {
            $(this).parents(".skuBox").css("display", "none");
        });
        // 为减少和添加商品数量的按钮绑定事件回调
        $('.buyNum .Operation_btn').on('click', function (evt) {
            var count = 1;
            if ($(this).text() == '-') {
                count = parseInt($(this).next().html());
                if (count > 1) {
                    count -= 1;
                    $(this).next().html(count);
                } else {
                    layer.open({
                        content: "@lang('order.The number of goods is at least 1')",
                        skin: 'msg',
                        time: 2, // 2秒后自动关闭
                    });
                }
            } else {
                // if ($(".skuListMain").find("li").hasClass('active') != true) {
                // layer.open({
                // content: "@lang('product.product_details.Please select specifications')",
                // skin: 'msg',
                // time: 2, // 2秒后自动关闭
                // });
                // } else {
                count = parseInt($(this).prev().html());
                if (parseInt(count) < sku_stock) {
                    count += 1;
                    $(this).prev().html(count);
                } else {
                    layer.open({
                        content: "@lang('order.Cannot add more quantities')",
                        skin: 'msg',
                        time: 2, // 2秒后自动关闭
                    });
                }
                // }
            }
        });
        // $(function () {
        // getComments();
        // });
        // 商品详情与商品评价切换
        $(".gIntroHead>span").on("click", function () {
            $(this).addClass("gIntroHeadActive").siblings().removeClass("gIntroHeadActive");
            // 通过 .index()方法获取元素下标，从0开始，赋值给某个变量
            var _index = $(this).index();
            if (_index == 1) {
                $(".dropload-down").remove();
                $(".lists").children().remove();
                getComments($('.gIntroConEvaluate'));
            }
            // 让内容框的第 _index 个显示出来，其他的被隐藏
            $(".gIntroCon>div").eq(_index).show().siblings().hide();
        });
        // 全部和有图进行切换
        $(".gEvaHead span").on("click", function () {
            $(this).addClass("gEvaHeadActive").siblings().removeClass("gEvaHeadActive");
        });
        $(".skuListMain").on("click", 'span', function () {
            $(this).parents('ul').find("span").removeClass("skuActive");
            $(this).parents('ul').find("li").removeClass("active");
            $(this).addClass("skuActive");
            $(this).parents("li").addClass("active");
            $(".pro_price").html($(this).parents("li").attr("code_price"));
            $(".pro_name").html("@lang('product.product_details.Choose')：" + $(this).html());
        });
        $(".btnBox button").on("click", function () {
            which_el_toDo(which_click, clickDom);
        });
        // 点击购物车
        $(".backCart").on("click", function () {
            window.location.href = "{{route('mobile.carts.index')}}";
        });
        // 点击收藏
        $(".gCollect").on("click", function () {
            if ($(this).hasClass('active') != true) {
                // $(".skuBox").css("display", "block");
                if ($(this).hasClass('for_show_login') == true) {
                    window.location.href = $(this).attr("data-url");
                } else {
                    add_favourites($(this));
                }
            } else {
                remove_favourites($(this));
            }
        });
        // 点击加入购物车
        $(".addCart").on("click", function () {
            $(".skuBox").css("display", "block");
            clickDom = $(this);
            which_click = 2;
        });
        // 点击立即购买
        $(".buy").on("click", function () {
            $(".skuBox").css("display", "block");
            clickDom = $(this);
            which_click = 3;
        });
        // 点击选择规格
        $(".gChooseBox").on("click", function () {
            $(".skuBox").css("display", "block");
            clickDom = $(this);
            which_click = 0;
        });
        // 点击确定根据不同的触发条件调用不用的事件
        function which_el_toDo(which_click, clickDom) {
            switch (which_click) {
                case 0:
                    var classificationText = $(".skuListHead select[name='{{ __('product.product_details.base_size') }}']").find("option:checked").text() + "-" +
                            $(".skuListHead select[name='{{ __('product.product_details.hair_colour') }}']").find("option:checked").text() + "-" +
                            $(".skuListHead select[name='{{ __('product.product_details.hair_density') }}']").find("option:checked").text();
                    $(".gChooseBox").html("@lang('product.product_details.classification')：" + classificationText);
                    $(".skuBox").css("display", "none");
                    break;
                case 1: // 添加收藏
                    break;
                case 2:
                    if (clickDom.hasClass('for_show_login') == true) {
                        window.location.href = clickDom.attr("data-url");
                    } else {
                        add_carts(clickDom);
                    }
                    break;
                case 3:
                    buy_now(clickDom);
                    break;
                default:
                    $(".skuBox").css("display", "none");
                    break;
            }

        }
        // 添加收藏
        function add_favourites(clickDom) {
            var data = {
                _token: "{{ csrf_token() }}",
                product_id: clickDom.attr("code")
            };
            var url = clickDom.attr('data-url');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    $(".gCollect").find("span").html("@lang('product.product_details.Favourites')");
                    $(".had_collection").removeClass("dis_n");
                    $(".no_collection").addClass("dis_n");
                    clickDom.attr('data-url_2', "{{ config('app.url') }}" + '/user_favourites/' + data.data.favourite.id);
                    clickDom.addClass('active');
                    $(".skuBox").css("display", "none");
                },
                error: function (err) {
                    if (err.status == 422) {
                        var arr = [];
                        var dataobj = err.responseJSON.errors;
                        for (let i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.open({
                            content: arr[0][0],
                            skin: 'msg',
                            time: 2, // 2秒后自动关闭
                        });
                    }
                }
            });
        }
        // 移除收藏
        function remove_favourites(clickDom) {
            var data = {
                _method: "DELETE",
                _token: "{{ csrf_token() }}",
            };
            var url = clickDom.attr('data-url_2');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    clickDom.attr('data-url_2', '');
                    clickDom.removeClass('active');
                    $(".gCollect").find("span").html("@lang('product.product_details.Collection')");
                    $(".had_collection").addClass("dis_n");
                    $(".no_collection").removeClass("dis_n");
                    $(".skuBox").css("display", "none");
                },
                error: function (err) {
                    var arr = [];
                    var dataobj = err.responseJSON.errors;
                    for (let i in dataobj) {
                        arr.push(dataobj[i]); //属性
                    }
                    layer.open({
                        content: arr[0][0],
                        skin: 'msg',
                        time: 2, // 2秒后自动关闭
                    });
                }
            });
        }
        // 加入购物车
        function add_carts(clickDom) {
            var data = {
                _token: "{{ csrf_token() }}",
                sku_id: sku_id,
                number: parseInt($(".gNum").html())
            };
            var url = clickDom.attr('data-url');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    layer.open({
                        content: "@lang('product.product_details.Shopping cart added successfully')",
                        skin: 'msg',
                        time: 2, // 2秒后自动关闭
                    });
                    $(".skuBox").css("display", "none");
                    $(".header-search").load(location.href + " .header-search");
                },
                error: function (err) {
                    var arr = [];
                    var dataobj = err.responseJSON.errors;
                    for (let i in dataobj) {
                        arr.push(dataobj[i]); //属性
                    }
                    layer.open({
                        content: arr[0][0],
                        skin: 'msg',
                        time: 2, // 2秒后自动关闭
                    });
                }
            });
        }
        // 立即购买
        function buy_now(clickDom) {
            if (clickDom.hasClass('for_show_login') == true) {
                window.location.href = clickDom.attr("data-url");
            } else {
                var url = clickDom.attr('data-url');
                window.location.href = url + "?sku_id=" + sku_id + "&number=" + parseInt($(".gNum").html()) + "&sendWay=1";
            }
        }
        // 分享复制到剪切板
        //      var clipboard = new ClipboardJS('.gShare');
        //
        //      clipboard.on('success', function (e) {
        //          console.log(e);
        //          layer.open({
        //              content: "@lang('product.Content has been copied to the clipboard')",
        //              skin: 'msg',
        //              time: 2, // 2秒后自动关闭
        //          });
        //      });
        //
        //      clipboard.on('error', function (e) {
        //          console.log(e);
        //          layer.open({
        //              content: "@lang('product.Copy to clipboard failed')",
        //              skin: 'msg',
        //              time: 2, // 2秒后自动关闭
        //          });
        //      });
        // 下拉加载获取评价内容
        function getComments(dom) {
            // 页数
            var page = 1;
            var Dom = dom || window;
            // dropload
            $('.gIntroConEvaluate').dropload({
                scrollArea: Dom,
                domDown: { // 下方DOM
                    domClass: 'dropload-down',
                    domRefresh: "<div class='dropload-refresh'>↑@lang('product.product_details.Pull up load more')</div>",
                    domLoad: "<div class='dropload-load'><span class='loading'></span>@lang('product.product_details.Loading in')...</div>",
                    domNoData: "<div class='dropload-noData'>@lang('product.product_details.over the end')</div>",
                },
                loadDownFn: function (me) {
                    // 拼接HTML
                    var html = '';
                    var data = {
                        page: page,
                    };
                    $.ajax({
                        type: 'GET',
                        url: $(".shopping_eva").attr("data-url"),
                        data: data,
                        dataType: 'json',
                        success: function (data) {
                            var dataObj = data.data.comments.data;
                            var dataObj_photo;
                            if (dataObj.length > 0) {
                                // var name;
                                var parameters;
                                $(".composite_index").text((data.data.composite_index).toFixed(1));
                                $(".description_index").text((data.data.description_index).toFixed(1));
                                $(".shipment_index").text((data.data.shipment_index).toFixed(1));
                                $.each(dataObj, function (i, n) {
                                    parameters = ($(".gIntroConEvaluate").attr("code") == "zh") ? n.order_item.sku.parameters_zh : n.order_item.sku.parameters_en;
                                    dataObj_photo = n.photo_urls;
                                    html += "<div class='commentDetail'>";
                                    html += "<div class='comUser'>";
                                    html += "<img src='" + n.user.avatar_url + "' class='userHead'/>";
                                    html += "<span>" + n.user.name + "</span>";
                                    html += "<div class='starBox'>";
                                    html += "<img class='star_img' src='" + $(".gIntroConEvaluate").attr('data-url') +
                                            "/static_m/img/star-" + n.composite_index + ".png'/>";
                                    html += "</div>";
                                    html += "</div>";
                                    html += "<div class='comSku'>";
                                    // html += "<span>" + name + "</span>";
                                    html += "<span>" + parameters + "</span>";
                                    html += "</div>";
                                    html += "<div class='comCon'>" + n.content + "</div>";
                                    html += "<div class='comPicture'>";
                                    $.each(dataObj_photo, function (a, b) {
                                        html += "<img src='" + b + "'>";
                                    });
                                    html += "</div>";
                                    html += "<div class='comDate'>" + n.created_at + "</div>";
                                    html += "</div>";
                                });
                                // 如果没有数据
                            } else {
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                                if (page == 1) {
                                    $(".no_eva").removeClass("dis_n");
                                    $(".dropload-down").remove();
                                }
                            }
                            // 为了测试，延迟1秒加载
                            $(".gIntroConEvaluate .lists").append(html);
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
        //数组选择器
        //定义skus数组内容
        var skus_arr = [];
        var size = null,
                colour = null,
                density = null;
        var skus_hide = $(".forSkusHidde").find("input");
        for (var skus_i = 0; skus_i <= skus_hide.length - 1; skus_i++) {
            skus_arr.push(JSON.parse($(skus_hide[skus_i]).val()));
        }
        sku_id = skus_arr[0].id;
        sku_stock = skus_arr[0].stock;
        //根据三个select的值进行数组查询
        function map_search(search_size, search_colour, search_density) {
            return skus_arr.map(function (item, index) {
                if (item.base_size_en == search_size
                        && item.hair_colour_en == search_colour
                        && item.hair_density_en == search_density
                ) {
//                  return skus_arr[index];
                    var search_result = skus_arr[index];
                    if (search_result.length != 0) {
                        $("#sku_price_in_usd").html(search_result.price);
                        $(".gPrice span").html(global_symbol + search_result.price);
                        var old_price = js_number_format(Math.imul(float_multiply_by_100(search_result.price), 12) / 1000);
                        $(".gPrice s").html(global_symbol + old_price);
                        sku_price = get_current_price(search_result.price);
                        sku_original_price = get_current_price(old_price);
                        sku_id = search_result.id;
                        sku_stock = search_result.stock;
                        var stock = search_result.stock || 0,
                                sales = search_result.sales || 0;
                        $("#sku_stock").html(stock);
                        $(".gStock span").eq(0).html("Freight：" + global_symbol + search_result.product.shipping_fee);
                        $(".gStock span").eq(1).html("Sales：" + sales);
                        $(".gStock span").eq(2).html("Stock：" + stock);
                        var sku_photo = search_result.photo_url;
                        if (sku_photo != "") {
                            $(".skuGoods img").attr("src", sku_photo);
                        } else {
                            $(".skuGoods img").attr("src", skus_arr[0].product.photo_urls[0]);
                        }
                    } else {
                        layer.msg("Current specifications do not exist. Please re-select the selected items!");
                    }
                }
            }).filter(function (item) {
                return item != undefined;
            });
        }
        //数据选择器
        if (!Array.prototype.filter) {
            Array.prototype.filter = function (fn, context) {
                var i,
                        value,
                        result = [],
                        length;

                if (!this || typeof fn !== 'function' || (fn instanceof RegExp)) {
                    throw new TypeError();
                }

                length = this.length;

                for (i = 0; i < length; i++) {
                    if (this.hasOwnProperty(i)) {
                        value = this[i];
                        if (fn.call(context, value, i, this)) {
                            result.push(value);
                        }
                    }
                }
                return result;
            };
        }
        var _findItemByValue = function (obj, prop, value) {
            return obj.filter(function (item) {
                return (item[prop] === value);
            });
        };
        //数组去重
        function unique(arr) {
            var new_arr = arr.filter(function (element, index, self) {
                return self.indexOf(element) === index;
            });
            return new_arr;
        }
        //数据计算方法
        function float_multiply_by_100(float) {
            float = String(float);
            // float = float.toString();
            var index_of_dec_point = float.indexOf('.');
            if (index_of_dec_point == -1) {
                float += '00';
            } else {
                var float_splitted = float.split('.');
                var dec_length = float_splitted[1].length;
                if (dec_length == 1) {
                    float_splitted[1] += '0';
                } else if (dec_length > 2) {
                    float_splitted[1] = float_splitted[1].substring(0, 1);
                }
                float = float_splitted.join('');
            }
            return Number(float);
        }
        function js_number_format(number) {
            number = String(number);
            var index_of_dec_point = number.indexOf('.');
            if (index_of_dec_point == -1) {
                number += '.00';
            } else {
                var number_splitted = number.split('.');
                var dec_length = number_splitted[1].length;
                if (dec_length == 1) {
                    number += '0';
                } else if (dec_length > 2) {
                    number_splitted[1] = number_splitted[1].substring(0, 2);
                    number = number_splitted.join('.');
                }
            }
            return number;
        }

        //切换
        $(".skuListHead").on("change", "select", function () {
            var current_val = $(this).val();
            var current_name = $(this).attr("name");
            var html_colour = '',
                    html_size = '',
                    html_density = '';
            var search_result = [],
                    judge_arr = [],
                    colour_arr = [],
                    size_arr = [],
                    density_arr = [];
            switch (current_name) {
                case '{{ __('product.product_details.base_size') }}':
                    size = current_val;
                    search_result = _findItemByValue(skus_arr, 'base_size_en', current_val);
                    $.each(search_result, function (i, n) {
                        colour_arr.push(n.hair_colour_en);
                        density_arr.push(n.hair_density_en)
                    });
                    colour_arr = unique(colour_arr);
                    density_arr = unique(density_arr);
                    $.each(colour_arr, function (i, n) {
                        html_colour += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $.each(density_arr, function (i, n) {
                        html_density += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}'] option").remove();
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}'] option").remove();
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}']").append(html_colour);
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}']").append(html_density);
                    if (colour != null) {
                        if ($.inArray(colour, colour_arr) >= 0) {
                            //已选select值存在
                            $(".skuListHead select[name='{{ __('product.product_details.hair_colour') }}']").find("option[value='" + colour + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("The selected colour is not available. Please re-select it!");
                        }
                    }
                    if (density != null) {
                        if ($.inArray(density, density_arr) >= 0) {
                            //已选select值存在
                            $(".skuListHead select[name='{{ __('product.product_details.hair_density') }}']").find("option[value='" + density + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected density no goods, please re-select!");
                        }
                    }
                    //每次选择后对当前三种规格的内容在数组中进行查找，改变价格及库存
                    var search_size = $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}']").val(),
                            search_colour = $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}']").val(),
                            search_density = $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}']").val();
                    map_search(search_size, search_colour, search_density);
                    break;
                case '{{ __('product.product_details.hair_colour') }}':
                    colour = current_val;
                    search_result = _findItemByValue(skus_arr, 'hair_colour_en', current_val);
                    $.each(search_result, function (i, n) {
                        size_arr.push(n.base_size_en);
                        density_arr.push(n.hair_density_en)
                    });
                    size_arr = unique(size_arr);
                    density_arr = unique(density_arr);
                    $.each(size_arr, function (i, n) {
                        html_size += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $.each(density_arr, function (i, n) {
                        html_density += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}'] option").remove();
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}'] option").remove();
                    $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}']").append(html_size);
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}']").append(html_density);
                    if (size != null) {
                        if ($.inArray(size, size_arr) >= 0) {
                            //已选select值存在
                            $(".skuListHead select[name='{{ __('product.product_details.base_size') }}']").find("option[value='" + size + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected dimensions are not available. Please re-select them!");
                        }
                    }
                    if (density != null) {
                        if ($.inArray(density, density_arr) >= 0) {
                            //已选select值存在
                            $(".skuListHead select[name='{{ __('product.product_details.hair_density') }}']").find("option[value='" + density + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected density no goods, please re-select!");
                        }
                    }
                    //每次选择后对当前三种规格的内容在数组中进行查找，改变价格及库存
                    var search_size = $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}']").val(),
                            search_colour = $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}']").val(),
                            search_density = $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}']").val();
                    map_search(search_size, search_colour, search_density);
                    break;
                case '{{ __('product.product_details.hair_density') }}':
                    density = current_val;
                    search_result = _findItemByValue(skus_arr, 'hair_density_en', current_val);
                    $.each(search_result, function (i, n) {
                        size_arr.push(n.base_size_en);
                        colour_arr.push(n.hair_colour_en)
                    });
                    size_arr = unique(size_arr);
                    colour_arr = unique(colour_arr);
                    $.each(size_arr, function (i, n) {
                        html_size += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $.each(colour_arr, function (i, n) {
                        html_colour += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}'] option").remove();
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}'] option").remove();
                    $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}']").append(html_size);
                    $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}']").append(html_colour);
                    if (size != null) {
                        if ($.inArray(size, size_arr) >= 0) {
                            //已选select值存在
                            $(".skuListHead select[name='{{ __('product.product_details.base_size') }}']").find("option[value='" + size + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected dimensions are not available. Please re-select them!");
                        }
                    }
                    if (colour != null) {
                        if ($.inArray(colour, colour_arr) >= 0) {
                            //已选select值存在
                            $(".skuListHead select[name='{{ __('product.product_details.hair_colour') }}']").find("option[value='" + colour + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("The selected colour is not available. Please re-select it!");
                        }
                    }
                    //每次选择后对当前三种规格的内容在数组中进行查找，改变价格及库存
                    var search_size = $(".skuListHead").find("select[name='{{ __('product.product_details.base_size') }}']").val(),
                            search_colour = $(".skuListHead").find("select[name='{{ __('product.product_details.hair_colour') }}']").val(),
                            search_density = $(".skuListHead").find("select[name='{{ __('product.product_details.hair_density') }}']").val();
                    map_search(search_size, search_colour, search_density);
                    break;
                default:
                    size = null;
                    colour = null;
                    density = null;
                    break;
            }
        })
    </script>
@endsection
