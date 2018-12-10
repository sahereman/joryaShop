@extends('layouts.mobile')
@section('title', App::isLocale('en') ? $product->name_en : $product->name_zh)
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
                <span class="gIntroHeadActive">@lang('product.product_details.Commodity details')</span>
                <span class="shopping_eva" data-url="{{ route('products.comment', ['product' => $product->id]) }}">@lang('product.product_details.Commodity feedback')</span>
            </div>
            <div class="gIntroCon">
                <div class="gIntroConDetail">
                    {{ App::isLocale('en') ? $product->content_en : $product->content_zh }}
                </div>
                <div class="gIntroConEvaluate" code="App::isLocale('en') ? en : zh" data-url="{{ config('app.url') }}">
                    <!--<div class="gEvaHead">
                        <span class="gEvaHeadActive">全部({{ $comment_count }})</span>
                        <span>有图({{ $photo_comment_count }})</span>
                    </div>-->
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
                <div class="gShare">
                    <img src="{{ asset('static_m/img/icon_share4.png') }}" alt=""/>
                    <span>@lang('product.product_details.Share')</span>
                </div>
                <div class="backCart">
                    <img src="{{ asset('static_m/img/icon_ShoppingCart5.png') }}" alt=""/>
                    <span>@lang('product.shopping_cart.Shop_cart')</span>
                </div>
                <div class="gCollect" code = "{{ $product->id }}"data-url="{{ route('user_favourites.store') }}"
                     data-url_2="{{ route('user_favourites.destroy', $product->id) }}">
                    <img src="{{ asset('static_m/img/icon_Collection4.png') }}" alt="" class="no_collection"/>
                    <img src="{{ asset('static_m/img/icon_Collection3.png') }}" alt="" class="had_collection dis_n"/>
                    <span>@lang('product.product_details.Collection')</span>
                </div>
            </div>
            @guest
	            <div class="addCart for_show_login" data-url="{{ route('mobile.login.show') }}">@lang('app.Add to Shopping Cart')</div>
	            <div class="buy for_show_login" data-url="{{ route('mobile.login.show') }}">@lang('product.product_details.Buy now')</div>
            @else
	            <div class="addCart" data-url="{{ route('carts.store') }}">@lang('app.Add to Shopping Cart')</div>
	            <div class="buy" data-url="{{ route('mobile.orders.pre_payment') }}">@lang('product.product_details.Buy now')</div>
            @endguest
        </div>
        <div class="skuBox">
            <div class="mask"></div>
            <div class="skuCon">
                <div class="skuGoods">
                    <img src="{{ $product->thumb_url }}"/>
                    <div>
                        <label>
                            @lang('basic.currency.symbol')
                            <span class="pro_price">{{ App::isLocale('en') ? $skus[0]->price_in_usd : $skus[0]->price }}</span>
                        </label>
                        <p>
                            @lang('product.product.product_details.stock'):
                            <span>{{ $skus[0]->stock }}</span>
                        </p>
                        <span class="pro_name">
                            @lang('product.product_details.Choose'):{{ App::isLocale('en') ? $skus[0]->name_en : $skus[0]->name_zh }}
                        </span>
                    </div>
                </div>
                <div class="skuListBox">
                    <div class="skuListHead">@lang('product.product_details.classification')</div>
                    <ul class="skuListMain">
                        @foreach($skus as $sku)
                            <li code_price='{{ App::isLocale('en') ? $sku->price_in_usd : $sku->price }}'>
                                <span>{{ App::isLocale('en') ? $sku->name_en : $sku->name_zh }}</span>
                                <input type="hidden" name="sku_id" value="{{ $sku->id }}">
                            </li>
                        @endforeach
                    </ul>
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
<script type="text/javascript" src="{{ asset('static_m/js/dropload/dropload.min.js') }}"></script>
    <script type="text/javascript">
        //页面单独JS写这里
        var mySwiper = new Swiper('.swiper-container', {
            loop: true,
            // 如果需要分页器
            pagination: '.swiper-pagination',
            autoplay: 3000,
            stopOnLastSlide: true,

        });
        var which_click = 0;   //通过判断which_click的值来确定是什么功能,0:选择规格,1:添加收藏，2：加入购物车，3：立即购买
        var clickDom;
        // 为减少和添加商品数量的按钮绑定事件回调
        $('.buyNum .Operation_btn').on('click', function (evt) {
            if ($(this).text() == '-') {
                var count = parseInt($(this).next().html());
                if (count > 1) {
                    count -= 1;
                    $(this).next().html(count);
                } else {
                    layer.open({
                        content: "@lang('order.The number of goods is at least 1')"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                }
            } else {
                var count = parseInt($(this).prev().html());
                if (count < 10000) {
                    count += 1;
                    $(this).prev().html(count);
                } else {
                    layer.open({
                        content: "@lang('order.Cannot add more quantities')"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                }
            }
        });
        //商品详情与商品评价切换
        $(".gIntroHead>span").on("click", function () {
            $(this).addClass("gIntroHeadActive").siblings().removeClass("gIntroHeadActive");
            //通过 .index()方法获取元素下标，从0开始，赋值给某个变量
            var _index = $(this).index();
            if(_index == 1){
            	$(".dropload-down").remove();
            	$(".lists").children().remove(); 
            	getEva();
            }
            //让内容框的第 _index 个显示出来，其他的被隐藏
            $(".gIntroCon>div").eq(_index).show().siblings().hide();
            
        });
        //全部和有图进行切换
        $(".gEvaHead span").on("click", function () {
            $(this).addClass("gEvaHeadActive").siblings().removeClass("gEvaHeadActive");
        });
        $(".skuListMain").on("click", 'span', function () {
        	$(this).parents('ul').find("span").removeClass("skuActive");
        	$(this).parents('ul').find("li").removeClass("active");
        	$(this).addClass("skuActive");
        	$(this).parents("li").addClass("active");
        	$(".pro_price").html($(this).parents("li").attr("code_price"));
        	$(".pro_name").html("@lang('product.product_details.Choose')："+$(this).html());
        });
        $(".btnBox button").on("click", function () {
            which_el_toDo(which_click,clickDom);
        });
        //点击购物车
        $(".backCart").on("click", function () {
            window.location.href = "{{route('mobile.carts.index')}}";
        });
        //点击收藏
        $(".gCollect").on("click", function () {
            $(".skuBox").css("display", "block");
            which_click = 1;
            clickDom = $(this);
        });
        //点击加入购物车
        $(".addCart").on("click", function () {
            $(".skuBox").css("display", "block");
            clickDom = $(this);
            which_click = 2;
        });
        //点击立即购买
        $(".buy").on("click", function () {
            $(".skuBox").css("display", "block");
            clickDom = $(this);
            which_click = 3;
        });
        //点击选择规格
        $(".gChooseBox").on("click", function () {
            $(".skuBox").css("display", "block");
            clickDom = $(this);
            which_click = 0;
        });
        //点击确定根据不同的触发条件调用不用的事件
        function which_el_toDo(which_click,clickDom){
        	switch(which_click){
        		case 0:
        		$(".gChooseBox").html("@lang('product.product_details.classification')："+$(".skuListMain").find("li.active").find("span").html());
        		 $(".skuBox").css("display", "none");
        		break;
        		case 1:      //添加收藏
        		if (clickDom.hasClass('active') != true){
        			add_favourites(clickDom);	
        		}else {
        			remove_favorites(clickDom);
        		}
        		break;
        		case 2:
        		if ($(".skuListMain").find("li").hasClass('active') != true) {
	                layer.open({
                        content: "@lang('product.product_details.Please select specifications')"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
	            } else {
	            	if (clickDom.hasClass('for_show_login') == true) {
	                    window.location.href = clickDom.attr("data-url");
	                } else {
	                    add_carts(clickDom);
	                }
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
        //添加收藏
        function add_favourites(clickDom){
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
                	$(".gCollect").find("span").html("@lang('product.product_details.Favorites')");
                	$(".had_collection").removeClass("dis_n");
                	$(".no_collection").addClass("dis_n");
                	clickDom.addClass('active');
                	$(".skuBox").css("display", "none");
                },
                error: function (err) {
                    console.log(err);
                    if (err.status == 422) {
                        layer.open({
	                        content: $.parseJSON(err.responseText).errors.product_id[0]
	                        , skin: 'msg'
	                        , time: 2 //2秒后自动关闭
	                    });
                    }
                }
            });
        }
        //移除收藏
        function remove_favorites(clickDom){
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
                    clickDom.removeClass('active');
                    $(".gCollect").find("span").html("@lang('product.product_details.Collection')");
                	$(".had_collection").addClass("dis_n");
                	$(".no_collection").removeClass("dis_n");
                	$(".skuBox").css("display", "none");
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        //加入购物车
        function add_carts(clickDom){
        	var data = {
                _token: "{{ csrf_token() }}",
                sku_id: $(".skuListMain").find("li.active").find("input").val(),
                number: parseInt($(".gNum").html())
            };
            var url = clickDom.attr('data-url');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                	layer.open({
                        content: "@lang('product.product_details.Shopping cart added successfully')"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                    $(".skuBox").css("display", "none");
                    $(".header-search").load(location.href + " .header-search");
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        //立即购买
        function buy_now(clickDom){
            if ($(".skuListMain").find("li").hasClass('active') != true) {
                layer.open({
                    content: "@lang('product.product_details.Please select specifications')"
                    , skin: 'msg'
                    , time: 2 //2秒后自动关闭
                });
            } else {
                if (clickDom.hasClass('for_show_login') == true) {
                    window.location.href = clickDom.attr("data-url");
                } else {
                    var url = clickDom.attr('data-url');
                    window.location.href = url + "?sku_id=" + $(".skuListMain").find("li.active").find("input").val() + "&number=" + parseInt($(".gNum").html()) + "&sendWay=1";
                }
            }
        }
    //下拉加载获取评价内容
	function getEva(){
		// 页数
	    var page = 1;
	    // dropload
	    $('.gIntroConEvaluate').dropload({
	        scrollArea : window,
	        domDown : {                                                          // 下方DOM
	            domClass   : 'dropload-down',
	            domRefresh : "<div class='dropload-refresh'>↑@lang('product.product_details.Pull up load more')</div>",
	            domLoad    : "<div class='dropload-load'><span class='loading'></span>@lang('product.product_details.Loading in')...</div>",
	            domNoData  : "<div class='dropload-noData'>@lang('product.product_details.over the end')</div>"
	        },
	        loadDownFn : function(me){
	            // 拼接HTML
	            var html = '';
	            var data = {
	                page: page
	            };
	            $.ajax({
	                type: 'GET',
	                url: $(".shopping_eva").attr("data-url"),
	                data: data,
	                dataType: 'json',
	                success: function(data){
	                	var dataObj = data.data.comments.data;
	                    var dataObj_photo;
	                    if(dataObj.length > 0){
	                        var name;
	                        $(".composite_index").text((data.data.composite_index).toFixed(1));
	                        $(".description_index").text((data.data.description_index).toFixed(1));
	                        $(".shipment_index").text((data.data.shipment_index).toFixed(1));
	                        $.each(dataObj, function (i, n) {
	                        	name = ($(".gIntroConEvaluate").attr("code") == "en") ? n.order_item.sku.name_en : n.order_item.sku.name_zh;
	                            dataObj_photo = n.photo_urls;
	                            html+="<div class='commentDetail'>"
	                            html+="<div class='comUser'>"
	                            html+="<img src='" + n.user.avatar_url + "' class='userHead'/>"
	                            html+="<span>"+ n.user.name +"</span>"
	                            html+="<div class='starBox'>"
	                            html+="<img class='star_img' src='"+ $(".gIntroConEvaluate").attr('data-url') +"/static_m/img/star-" + n.composite_index + ".png'/>"
	                            html+="</div>"
	                            html+="</div>"
	                            html+="<div class='comSku'>"
	                            html+="<span>"+ name +"</span>"
	                            html+="</div>"
	                            html+="<div class='comCon'>"+n.content+"</div>"
	                            html+="<div class='comPicture'>"
	                            $.each(dataObj_photo, function (a, b) {
	                                html += "<img src='" + b + "'>";
	                            });
	                            html+="</div>"
	                            html+="<div class='comDate'>"+ n.created_at +"</div>"
	                            html+="</div>"
	                        });
	                    // 如果没有数据
	                    }else{
	                        // 锁定
	                        me.lock();
	                        // 无数据
	                        me.noData();
	                        $(".no_eva").removeClass("dis_n");
	                        $(".dropload-down").remove();
	                    }
	                    // 为了测试，延迟1秒加载
	                    setTimeout(function(){
	                        $(".gIntroConEvaluate .lists").append(html);
	                        page++;
	                        // 每次数据插入，必须重置
	                        me.resetload();
	                    },1000);
	                },
	                error: function(xhr, type){
	                    // 即使加载出错，也得重置
	                    me.resetload();
	                }
	            });
	        }
	    });	
	}
    </script>
@endsection
