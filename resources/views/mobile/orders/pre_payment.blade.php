@extends('layouts.mobile')
@section('title', '确认订单')
@section('content')
    <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>确认订单</span>
	</div>
    <div class="pre_payment">
        <div class="pre_paymentCon">
        	@if(false)
        	<div class="pre_address no_address">
        		<div>
        			<img src="{{ asset('static_m/img/icon_pre_address.png') }}">
        			<span class="no_address">添加收获地址</span>
        		</div>
        		<img src="{{ asset('static_m/img/icon_more.png') }}">
        	</div>
        	@else(true)
    		<div class="pre_address edit_address">
        		<div>
        			<p class="address_title">
        				<span>胡一天</span>
        				<span>12345678901</span>
        			</p>
        			<p class="address_info">
        				<span class="default_btn">默认</span>
        				<span class="address_info_all">安徽省-合肥市-庐阳区 某某某街道阜阳北路与北城大道交口创智天地</span>
        			</p>
        		</div>
        		<img src="{{ asset('static_m/img/icon_more.png') }}">
    	    </div>	
        	@endif
        	<div class="pre_products">
        		<ul>
        			@for($i = 0; $i < 3; $i++)
	    	   	        <li>
	    	   	        	<img src="{{ asset('static_m/img/blockImg.png') }}">
	    	   	            <span>&#215;1</span>
	    	   	        </li>
	    	   	    @endfor
        		</ul>
        		<span class="pre_products_num">共3件</span>
        		<img src="{{ asset('static_m/img/icon_more.png') }}">
        	</div>
        	<div class="pre_amount">
        		<p>
        			<span>商品金额</span>
        			<span>&#36; 248.00</span>
        		</p>
        		<p>
        			<span>运费</span>
        			<span>&#36; 0.00</span>
        		</p>
        	</div>
        	<div class="pre_currency">
        		<p class="main_title">币种选择</p>
                <p class="currency_selection">
                    <a href="javascript:void(0)" class="active" code="RMB" country="CNY">人民币</a>
                    <a href="javascript:void(0)" code="dollar" country="USD">美金</a>
                </p>
        	</div>
        	<div class="pre_note">
        		<p>订单备注</p>
        		<textarea placeholder="选填，给卖家留言（限50字）" maxlength="50"></textarea>
        	</div>
        </div>
        <div class="pre_paymentTotal">
            <span class="RMB_num amount_of_money">&#165; <span>248.00</span></span>
            <span class="dis_ni dollar_num amount_of_money">&#36; <span>248.00</span></span>
        	<a href="javascript:void(0)" class="payment_btn" data-url="{{ route('orders.store') }}">提交订单</a>
        </div>
        <!--新增地址与选择地址的弹窗-->
    <div class="address_choose animated dis_n">
    	<div class="headerBar fixHeader">
    		<a href="javascript:void(0)" class="close_layer_img">
    			<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg">
    		</a>
			<span>收货地址</span>
		</div>
    	@if(true)
    	   <!--如果有地址显示地址列表与新增地址按钮-->
    	   <div class="adsBox lay_content">
			<!--有收获地址数据时-->
				<div class="adsList">
					<div class="adsItem">
						<div class="adsName">
							<span>胡八一</span>
							<span class="defaultAds">默认</span>
						</div>
						<div class="adsDetail">
							<span class="adsP">152****4545</span>
							<span class="adsD">北京市朝阳街道石门街道</span>
						</div>
					</div>
					<div class="adsItem">
						<div class="adsName">
							<span>胡八一</span>
						</div>
						<div class="adsDetail">
							<span class="adsP">152****4545</span>
							<span class="adsD">北京市朝阳街道石门街道北京市朝阳街道石门街道</span>
						</div>
					</div>
				</div>
				<div class="btnBox">
					<a href="{{ route('mobile.user_addresses.create') }}" class="doneBtn">新建地址</a>
				</div>
	        </div>
    	@else(false)
    	<!--如果没有地址显示新建地址与保存按钮-->
    	<div class="addAdsBox lay_content">
			<form action="" method="post" class="addAdsForm">
				<div class="addAdsItem">
					<label class="must">收货人</label>
					<input type="text" name="" id="" value="" placeholder="请填写收货人"/>
				</div>
				<div class="addAdsItem">
					<label class="must">手机号码</label>
					<input type="text" name="" id="" value="" placeholder="请填写手机号"/>
				</div>
				<div class="addAdsItem" style="border:none;">
					<label class="must">详细地址</label>
					<input type="text" name="" id="" value="" placeholder="请填写详细地址，街道，门牌号等"/>
				</div>
				<button type="submit" class="doneBtn">保存</button>
			</form>
			<div class="defaultBox">
				<label>设为默认地址</label>
				<img src="{{ asset('static_m/img/icon_OFF.png') }}" class="switchBtn"/>
			</div>
		</div>
    	@endif
    </div>
    <!--商品明细弹窗-->
    <div class="pro_lists animated dis_n">
    	<div class="headerBar fixHeader">
    		<a href="javascript:void(0)" class="close_pro_lists_img">
    			<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg">
    		</a>
			<span>商品明细</span>
		</div>
    	<div class="pro_listsCon lay_content">
			@for($i = 0; $i < 10; $i++)
				<div class="pro_listsItem">
					<img src="{{ asset('static_m/img/blockImg.png') }}"/>
					<div class="pro_listsDetail">
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
								<span class="gNum">&#215; 1</span>
							</div>
						</div>
					</div>
				</div>
			@endfor
		</div>
    </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        	var address_layer;   //地址弹窗
        	var pro_lists_layer;   //商品明细弹窗
        	var vh = parseInt($(window).height()-48);
        	console.log($(window).height());
        	$(".address_choose .lay_content").css("min-height",vh);
        	$(".pre_address").on("click",function(){
        		$('.address_choose').removeClass("dis_n");
				$('.address_choose').removeClass("fadeOutRightBig");
				$('.address_choose').addClass("fadeInRightBig");
        	})
        	//关闭地址弹窗
        	$(".close_layer_img").on('click',function() {
				$('.address_choose').removeClass("fadeInRightBig");
				$('.address_choose').addClass("fadeOutRightBig");
			});
			//查看商品明细
			$(".pre_products").on("click",function(){
				$('.pro_lists').removeClass("dis_n");
				$('.pro_lists').removeClass("fadeOutRightBig");
				$('.pro_lists').addClass("fadeInRightBig");
			})
			//关闭商品明细弹窗
			$(".close_pro_lists_img").on('click',function() {
			  $('.pro_lists').removeClass("fadeInRightBig");
			  $('.pro_lists').addClass("fadeOutRightBig");
			});
			//切换币种
			$(".currency_selection").on("click",'a',function(){
				$(".currency_selection").find("a").removeClass("active");
				$(this).addClass("active");
			})
        });
    </script>
@endsection
