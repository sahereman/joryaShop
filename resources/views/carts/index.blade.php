@extends('layouts.app')
@section('title', '购物车')
@section('content')
    @include('common.error')
    <div class="shopping_cart">
        <div class="m-wrapper">
            <div class="carts">
            	<p class="Crumbs">
	                <a href="{{ route('root') }}">首页</a>
	                <span>></span>
	                <a href="#">购物车</a>
	            </p>
	            <!--当购物车内容为空时显示-->
            	<div class="empty_shopping_cart" style="display: none;">
            		<div></div>
            		<p>购物车还是空滴</p>
            		<a href="{{ route('root') }}">去逛逛</a>
            	</div>
            	<!--购物车有商品时显示下方内容包括cart-header，cart-items，cart-footer-->
            	<div class="cart-header">
            		<div class="left w130">
	                    <input id="selectAll" class="selectAll" type="checkbox">
	                    <label for="selectAll">全选</label>
	                </div>
	                <div class="left w250">商品信息</div>
	                <div class="left w120 center">规格</div>
	                <div class="left w100 center">单价</div>
	                <div class="left w150 center">数量</div>
	                <div class="left w100 center">小计</div>
	                <div class="left w120 center">操作</div>
            	</div>
            	<div class="cart-items">
            		@for ($a = 0; $a < 3; $a++)
            			<div class="clear single-item">
		                    <div class="left w20">
		                        <input name="selectOne" type="checkbox">
		                    </div>
		                    <div class="left w110 shop-img">
		                        <a class="cur_p" href="">
		                            <img src="{{ asset('img/list-1.png') }}">
		                        </a>
		                    </div>
		                    <div class="left w250 pro-info">
		                        <span>卓页美业长直假发片</span>
		                    </div>
		                    <div class="left w120 center"><span>颜色：蓝色</span></div>
		                    <div class="left w100 center">&yen;<span class="price">138.00</span></div>
		                    <div class="left w150 center counter">
		                        <button class="left small-button">-</button>
		                        <input class="left center count" type="text" size="2" value="1">
		                        <button class="left small-button">+</button>
		                    </div>
		                    <div class="left w100 s_total center">&yen;<span>138.00</span></div>
		                    <div class="left w120 center">
		                    	<p>
		                    		<a class="cur_p">移入收藏夹</a>
		                            <a class="cur_p">删除</a>
		                    	</p>
		                    </div>
		                </div>
            		@endfor
            	</div>
            	<div class="cart-footer">
	                <div class="clear left left-control">
	                	<div class="left w100">
	                		<input id="selectAll-2" class="selectAll" type="checkbox">
	                        <label for="selectAll-2">全选</label>
	                	</div>
	                    <a id="clearSelected" href="javascript:void(0);">删除选中商品</a>
	                    <a id="clearInvalid" href="javascript:void(0);">清空失效商品</a>
	                </div>
	                <div class="right">
	                    <!--<span>总共选中了<span id="totalCount">0</span>件商品</span>-->
	                    <span>合计: <span id="totalPrice">&yen;0.00</span></span>
	                    <button class="big-button">结算</button>
	                </div>
	            </div>
            </div>
            
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        	//全选
        	$('.selectAll').on('change', function(evt) {
                if ($(this).prop('checked')) {
                    $('.single-item input[type="checkbox"]').prop('checked', true);
                    $('.selectAll').prop('checked', true);
                    $(".big-button").addClass('active');
                    calcTotal();
                } else {
                    $('.single-item input[type="checkbox"]').prop('checked', false);
                    $('.selectAll').prop('checked', false);
                    $('#totalCount').text('0');
                    $('#totalPrice').html('&yen;0.00');
                    $(".big-button").removeClass('active');
                }
            });
            // 为单个商品项的复选框绑定改变事件
            $('input[name="selectOne"]').on('change', function() {
                calcTotal();
                if (!$(this).prop('checked')) {
                    $('.selectAll').prop('checked', false);
                }
            });
            // 为删除选中商品超链接绑定事件回调
            $('#clearSelected').on('click', function() {
                layer.alert('确定要删除所选商品吗', function(index){
                	$('.single-item').each(function() {
                        if ($(this).find('input[name="selectOne"]').prop('checked')) {
                            $(this).remove();
                        }
                    });
                    $('.selectAll').prop('checked', false);
                    calcTotal();
                    layer.close(index);
				});       
            });
            // 为减少和添加商品数量的按钮绑定事件回调
            $('.single-item button').on('click', function(evt) {
                $(this).parent().parent().find('input[name="selectOne"]').prop('checked', true);
                if ($(this).text() == '-') {
                    var count = parseInt($(this).next().val());
                    if (count > 1) {
                        count -= 1;
                        $(this).next().val(count);
                    } else {
                        layer.msg('商品数量最少为1');
                    }
                } else {
                    var count = parseInt($(this).prev().val());
                    if (count < 200) {
                        count += 1;
                        $(this).prev().val(count);
                    } else {
                        layer.msg('商品数量最少为1');
                    }
                }
                var price = parseFloat($(this).parent().prev().find('span').text());
                $(this).parent().next().html('&yen;' + (price * count).toFixed(2));
                calcTotal();
            });
             // 为单个商品项删除超链接绑定事件回调
            $('.single-item a').on('click', function() {
            	var clickDom = $(this);
                layer.alert('确定要删除该项吗', function(index){
                	clickDom.parents('.single-item').remove();
                    calcTotal();
                    layer.close(index);
				});       
            });
            // 为商品数量文本框绑定改变事件回调
            $('.single-item input[type="text"]').on('change', function() {
                $(this).parent().parent().find('input[name="selectOne"]').prop('checked', true);
                var count = parseInt($(this).val());

                if (count != $(this).val() || count < 1 || count > 200) {
                    layer.msg('无效的商品数量值');
                    count = 1;
                    $(this).val(count);
                }
                var price = parseFloat($(this).parent().prev().find('span').text());
                $(this).parent().next().html('&yen;' + (price * count).toFixed(2));
                calcTotal();
            });
            
            // 计算总计
            function calcTotal() {
                var checkBoxes = $('input[name="selectOne"]');
                var priceSpans = $('.single-item .price');
                var countInputs = $('.single-item .count');
                var totalCount = 0;
                var totalPrice = 0;
                for (var i = 0; i < priceSpans.length; i += 1) {
                    // 复选框被勾中的购物车项才进行计算
                    if ($(checkBoxes[i]).prop('checked')) {
                        // 强调: jQuery对象使用下标运算或get方法会还原成原生的JavaScript对象
                        var price = parseFloat($(priceSpans[i]).text());
                        var count = parseInt($(countInputs[i]).val());
                        totalCount += count;
                        totalPrice += price * count;
                    }
                }
                if(totalPrice > 0){
                	$(".big-button").addClass('active');
                }else {
                	$(".big-button").removeClass('active');
                }
                $('#totalCount').text(totalCount);
                $('#totalPrice').html('&yen;' + totalPrice.toFixed(2));
            }
        });
    </script>
@endsection
