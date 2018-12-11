@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Cart' : '购物车')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('app.Shopping Cart')</span>
    </div>
    <div class="cartsBox">
    	@if($carts->isEmpty())
        <!--当购物车内容为空时显示-->
        <div class="empty_shopping_cart">
            <div></div>
            <p>@lang('product.shopping_cart.shopping_cart_still_empty')</p>
            <a href="{{ route('root') }}">@lang('product.shopping_cart.Go_shopping')</a>
        </div>
        @else
        <div class="cartsCon">
            @foreach($carts as $cart)
                <div class="cartItem">
                    <label class="cartItemLab">
                        <input type="checkbox" name="selectOne" id="" code="{{ $cart->sku->id }}" value="{{ $cart->id }}"/>
                        <span></span>
                    </label>
                    <img src="{{ $cart->sku->product->thumb_url }}"/>
                    <div class="cartDetail">
                        <div class="goodsName">
                            {{ App::isLocale('en') ? $cart->sku->product->name_en : $cart->sku->product->name_zh }}
                        </div>
                        <div class="goodsSpec">
                            <span>{{ App::isLocale('en') ? $cart->sku->name_en : $cart->sku->name_zh }}</span>
                        </div>
                        <div class="goodsPri">
                            <div>
                                <span class="price">{{ App::isLocale('en') ? '&#36;' : '&#165;' }}</span>
                                <span class="realPri">{{ App::isLocale('en') ? $cart->sku->price_in_usd : $cart->sku->price }}</span>
                            </div>
                            <div class="goodsNum">
                                <span class="Operation_btn">-</span>
                                <input class="gNum" type="text" size="4"
                                       data-url="{{ route('carts.update', $cart->id) }}" value="{{ $cart->number }}">
                                <span class="Operation_btn">+</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        <div class="cartsTotle">
            <div class="cartsTotleDiv">
                <input type="checkbox" name="cart_ids" id="totalIpt" value=""/>
                <span class="bagLbl"></span>
                <label for="totalIpt" class="totalIpt">@lang('product.shopping_cart.all_selected')</label>
            </div>
            <div class="Settlement_btns">
                <a class="cancelBtn">@lang('product.Deletes the selected')</a>
                @guest
                <a class="total_num for_show_login" data-url="{{ route('mobile.orders.pre_payment') }}">
                    @lang('product.shopping_cart.Total')：{{ App::isLocale('en') ? '&#36;' : '&#165;' }}
                    <span>0.00</span>
                </a>
                @else
                    <a class="total_num" data-url="{{ route('mobile.orders.pre_payment') }}">
                        @lang('product.shopping_cart.Total')：{{ App::isLocale('en') ? '&#36;' : '&#165;' }}
                        <span>0.00</span>
                    </a>
                @endguest
            </div>
        </div>
    </div>
    @include('layouts._footer_mobile')
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
        $(".itemsG").addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Unchecked_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Unchecked_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Select_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Unchecked_my.png') }}");
        //实现全选与反选
        $("#totalIpt").click(function () {
            if ($(this).prop("checked")) {
                $("input[name=selectOne]:checkbox").each(function () {
                    $(this).prop("checked", true);
                    $(".cancelBtn").css("background", "#bc8c61");
                });
                calcTotal();
                $(".total_num").addClass('active');
            } else {
                $("input[name=selectOne]:checkbox").each(function () {
                    $(this).prop("checked", false);
                    $(".cancelBtn").css("background", "#dcdcdc");
                });
                calcTotal();
                $(".total_num").removeClass('active');
            }
        });
        //单个商品绑定计算
        $('input[name="selectOne"]').on('change', function () {
            calcTotal();
            if($("input[name=selectOne]:checked").length==0){
            	$(".cancelBtn").css("background", "#dcdcdc");
            }else {
            	$(".cancelBtn").css("background", "#bc8c61");
            }
            if (!$(this).prop('checked')) {
                $('#totalIpt').prop('checked', false);
            }
        });
        
        // 为减少和添加商品数量的按钮绑定事件回调
        $('.cartItem .Operation_btn').on('click', function (evt) {
            $(this).parent().parent().find('input[name="selectOne"]').prop('checked', true);
            if ($(this).text() == '-') {
                var count = parseInt($(this).next().val());
                if (count > 1) {
                    count -= 1;
                    $(this).next().val(count);
                       update_pro_num($(this).next());
                } else {
                    layer.open({
                        content: "@lang('order.The number of goods is at least 1')"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                }
            } else {
                var count = parseInt($(this).prev().val());
                if (count < 200) {
                    count += 1;
                    $(this).prev().val(count);
                       update_pro_num($(this).prev());
                } else {
                    layer.open({
                        content: "@lang('order.Cannot add more quantities')"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                }
            }
            var price = parseFloat($(this).parent().prev().find('span').text());
            $(this).parent().next().html("{{ App::isLocale('en') ? '&#36;' : '&#165;' }}" + (price * count).toFixed(2));
            calcTotal();
        });
        // 计算总计
        function calcTotal() {
            var checkBoxes = $('input[name="selectOne"]');
            var priceSpans = $('.cartItem .realPri');
            var countInputs = $('.cartItem .gNum');
            var totalPrice = 0;
            for (var i = 0; i < priceSpans.length; i += 1) {
                // 复选框被勾中的购物车项才进行计算
                if ($(checkBoxes[i]).prop('checked')) {
                    // 强调: jQuery对象使用下标运算或get方法会还原成原生的JavaScript对象
                    var price = parseFloat($(priceSpans[i]).text());
                    var count = parseInt($(countInputs[i]).val());
                    totalPrice += price * count;
                }
            }
            if (totalPrice > 0) {
                $(".total_num").addClass('active');
            } else {
                $(".total_num").removeClass('active');
            }
            $('.total_num span').html(totalPrice.toFixed(2));
        }
        //点击结算
        $(".total_num").on("click", function () {
            var clickDom = $(this);
            if (clickDom.hasClass('for_show_login') == true) {
                window.location.href = "{{ route('mobile.login.show') }}";
            } else {
                if (clickDom.hasClass("active") != true) {
                    layer.open({
                        content: "@lang('product.shopping_cart.Please select the item you want to settle')！",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                } else {
                    var cart_ids = "";
                    var cartIds = $(".cartsCon").find("input[name='selectOne']:checked");
                    if (cartIds.length > 0) {
                        $.each(cartIds, function (i, n) {
                            cart_ids += $(n).val() + ",";
                        });
                        cart_ids = cart_ids.substring(0, cart_ids.length - 1);
                        var url = clickDom.attr('data-url');
                        window.location.href = url + "?cart_ids="+cart_ids+"&sendWay=2";
                    } else {
                        layer.open({
                            content: "@lang('product.shopping_cart.Please select the item you want to settle')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                    }
                }
            }
        });
         //更新购物车记录（增减数量）
        function update_pro_num(dom) {
            var url = dom.attr("data-url");
            var data = {
                _method: "PATCH",
                _token: "{{ csrf_token() }}",
                number: dom.val()
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    calcTotal();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    </script>
@endsection
