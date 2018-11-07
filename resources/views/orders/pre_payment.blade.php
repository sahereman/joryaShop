@extends('layouts.app')
@section('title', '确认订单')
@section('content')
    @include('common.error')
    <div class="pre_payment">
    	<div class="m-wrapper">
            <div class="pre_payment_content">
            	<p class="Crumbs">
	                <a href="{{ route('root') }}">首页</a>
	                <span>></span>
	                <a href="#">确认订单</a>
	            </p>
            	<div class="pre_payment_header">
            		<div class="address_info clear">
            			<ul class="left">
            				<li class="clear">
            					<img src="{{ asset('img/sure_ad_local.png') }}">
            					<span>默认地址</span>
            				</li>
            				<li>
            					<span>联&ensp;系&ensp;人：</span>
            					<span class="address_name">小王</span>
            				</li>
            				<li>
            					<span>联系方式：</span>
            					<span class="address_phone">186****8877</span>
            				</li>
            				<li>
            					<span>联系地址：</span>
            					<span class="address_location">山东省青岛市市北区敦化路328号诺德广场</span>
            				</li>
            			</ul>
            			<div class="right">
            				<a class="change_address" href="javascript:void(0)">切换地址</a>
            				<a class="add_new_address" href="javascript:void(0)">新建地址</a>
            			</div>
            		</div>
            	</div>
            	<div class="pre_payment_main">
            		<p class="main_title">商品清单</p>
            		<div class="pre_payment_main_header">
            			<div class="left w110"></div>
		                <div class="left w250">商品信息</div>
		                <div class="left w150 center">规格</div>
		                <div class="left w150 center">单价</div>
		                <div class="left w150 center">数量</div>
		                <div class="left w150 center">小计</div>
            		</div>
            		<div class="pre_payment-items">
	            		@for ($a = 0; $a < 3; $a++)
	            			<div class="clear single-item">
			                    <div class="left w110 shop-img">
			                        <a class="cur_p" href="">
			                            <img src="{{ asset('img/list-1.png') }}">
			                        </a>
			                    </div>
			                    <div class="left w250 pro-info">
			                        <span>卓页美业长直假发片</span>
			                    </div>
			                    <div class="left w150 center"><span>颜色：蓝色</span></div>
			                    <div class="left w150 center">&yen;<span>138.00</span></div>
			                    <div class="left w150 center counter"><span>1</span></div>
			                    <div class="left w150 s_total red center">&yen;<span>138.00</span></div>
			                </div>
	            	    @endfor
	            	</div>
            	</div>
            	<div class="pre_payment_footer">
            		<p class="main_title">币种选择</p>
            		<p class="currency_selection">
            			<a href="javascript:void(0)" class="active">人民币</a>
            			<a href="javascript:void(0)">美金</a>
            		</p>
            		<ul>
            			<li class="clear">
            				<span>订单备注：</span>
            				<textarea placeholder="选填，给卖家留言（限50字）"></textarea>
            			</li>
            			<li>
            				<p>
            					<span>合计：</span>
            					<span>&yen;<span>138.00</span></span>
            				</p>
            				<p>
            					<span>运费：</span>
            					<span>&yen;<span>138.00</span></span>
            				</p>
            			</li>
            			<li>
            				<p>
            					<span>应付金额：</span>
            					<span class="red">&yen;<span>138.00</span></span>
            				</p>
            				<p>
            					<a>付款</a>
            				</p>
            				<p class="address_info">
            					<span>收货人</span>
            					<span>152****8012</span>
            				</p>
            				<p class="address_info">山西省晋城市阳城县町店镇护肤ishi富i</p>
            			</li>
            		</ul>
            	</div>
            </div>
    	</div>
    </div>
<!--新建收获地址弹出层-->
<div class="dialog_popup new_receipt_address">
    <div class="dialog_content">
        <div class="close">
            <i></i>
        </div>
        <div class="dialog_textarea">
            <div class="textarea_title">
                <span>新建地址</span>
            </div>
            <div class="textarea_content">
                <form method="POST" action="{{ route('user_addresses.store') }}" enctype="multipart/form-data"
                      id="creat-form">
                    {{ csrf_field() }}
                    <ul>
                        <li>
                            <p>
                                <span class="input_name"><i>*</i>收货人：</span>
                                <input class="user_name" name="name" type="text" placeholder="输入收货人姓名">
                            </p>
                            <p>
                                <span class="input_name"><i>*</i>手机号码：</span>
                                <input class="user_tel" name="phone" type="text" placeholder="输入真实有效的手机号">
                            </p>
                        </li>
                        <li>
                            <span class="input_name"><i>*</i>详细地址：</span>
                            <textarea name="address" placeholder="详细地址，街道、门牌号等"></textarea>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="btn_area">
            <a class="cancel">取消</a>
            <a class="success">确定</a>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        	//货币种类切换
        	$(".currency_selection a").on("click",function(){
        		$(".currency_selection a").removeClass("active");
        		$(this).addClass('active');
        	})
        	//新建收获地址
        	$(".add_new_address").on("click",function(){
        		$(".new_receipt_address").show();
        	})
        	$(".new_receipt_address").on("click",".success",function(){
        		$(".address_name").html($(".new_receipt_address .user_name").val());
        		$(".address_phone").html($(".new_receipt_address .user_tel").val());
        		$(".address_location").html($(".new_receipt_address textarea").val());
        		$(".new_receipt_address").hide();
        	})
        });
    </script>
@endsection
