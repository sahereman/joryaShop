@extends('layouts.mobile')
@section('title', '购物车')
@section('content')
   <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>购物车</span>
	</div>
	<div class="cartsBox">
		<div class="cartsCon">
			@for($i = 0; $i < 10; $i++)
				<div class="cartItem">
					<label class="cartItemLab">
						<input type="checkbox" name="selectOne" id="" value="1" />
						<span></span>
					</label>
					<img src="{{ asset('static_m/img/blockImg.png') }}"/>
					<div class="cartDetail">
						<div class="goodsName">
							卓业美业长直假发片卓业美业长直假发片
						</div>
						<div class="goodsSpec">
							<sapn>颜色：</sapn>
							<span>黄</span>
						</div>
						<div class="goodsPri">
							<div>
								<span class="price">{{ App::isLocale('en') ? '&#36;' : '&#165;' }}</span>
								<span class="realPri">520.00</span>
							</div>
							<div class="goodsNum">
								<span class="Operation_btn">-</span>
								<input class="gNum" type="number" size="4" value="1" disabled>
								<span class="Operation_btn">+</span>
							</div>
						</div>
					</div>
				</div>
			@endfor
		</div>
		<div class="cartsTotle">
			<div class="cartsTotleDiv">
				<input type="checkbox" name="" id="totalIpt" value="" />
				<span class="bagLbl"></span>
				<label for="totalIpt" class="totalIpt">全选</label>
			</div>
			<div class="Settlement_btns">
				<a class="cancelBtn">删除所选</a>
				@guest
            	    <a class="total_num for_show_login" data-url="{{route('mobile.orders.pre_payment')}}">结算：{{ App::isLocale('en') ? '&#36;' : '&#165;' }}<span>0.00</span></a>
				@else
				    <a class="total_num" data-url="{{ route('mobile.orders.pre_payment') }}">结算：{{ App::isLocale('en') ? '&#36;' : '&#165;' }}<span>0.00</span></a>
				@endguest
			</div>
		</div>
	</div>


    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobile')
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
		$(".itemsG").addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Select_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
		//实现全选与反选
            $("#totalIpt").click(function () {
                if ($(this).prop("checked")) {
                    $("input[name=selectOne]:checkbox").each(function () {
                        $(this).prop("checked", true);
                        $(".cancelBtn").css("background","#bc8c61");
                    });
                    calcTotal();
                    $(".total_num").addClass('active');
                } else {
                    $("input[name=selectOne]:checkbox").each(function () {
                        $(this).prop("checked", false);
                        $(".cancelBtn").css("background","#dcdcdc");
                    });
                    calcTotal();
                    $(".total_num").removeClass('active');
                }
            });
        //单个商品绑定计算
        $('input[name="selectOne"]').on('change', function () {
            calcTotal();
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
//                      update_pro_num($(this).next());
                    } else {
                    	layer.open({
						    content: "@lang('order.The number of goods is at least 1')"
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						});
                    }
                } else {
                    var count = parseInt($(this).prev().val());
                    if (count < 200) {
                        count += 1;
                        $(this).prev().val(count);
//                      update_pro_num($(this).prev());
                    } else {
                        layer.open({
						    content: "@lang('order.Cannot add more quantities')"
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
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
        $(".total_num").on("click",function(){
        	var clickDom = $(this);
            	if (clickDom.hasClass('for_show_login') == true) {
            		window.location.href="{{ route('mobile.login.show') }}";
	        	}else {
	        		if(clickDom.hasClass("active")!=true){
	        			layer.open({
						    content: "请选择需要结算的商品！"
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						});
	        		}else {
	        			var cart_ids = "";
	        			var cartIds = $(".cartsCon").find("input[name='selectOne']:checked");
	        			if(cartIds.length>0){
	        				$.each(cartIds, function(i,n) {
	        					cart_ids+=$(n).val()+","
	        				});
	        				cart_ids=cart_ids.substring(0,cart_ids.length-1);
			        		var url = clickDom.attr('data-url');
			        		window.location.href = url+"?cart_ids=26";
	        			}else {
	        				layer.open({
							    content: "请选择需要结算的商品！"
							    ,skin: 'msg'
							    ,time: 2 //2秒后自动关闭
							});
	        			}
	        		}
	        	}	
        })
    </script>
@endsection
