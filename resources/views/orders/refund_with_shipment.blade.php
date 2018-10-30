@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
    <div class="User_center my_orders">
        <div class="m-wrapper">
            <div class="refun_crumbs">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">我的订单</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">订单详情</a>
                    <span>></span>
                    <a href="">申请售后</a>
                </p>
            </div>
            <!--申请内容-->
            <div class="refund_content">
            	<div class="technological_process">
            		<!--分步骤显示图片一共四张-->
            		<img src="{{ asset('img/process-1.png') }}">
            		<img src="{{ asset('img/process-2.png') }}" style="display: none;">
            		<img src="{{ asset('img/process-3.png') }}" style="display: none;">
            		<img src="{{ asset('img/process-4.png') }}" style="display: none;">
            		<!--process-5.png图片是退款失败时展示-->
            		<img src="{{ asset('img/process-5.png') }}" style="display: none;">
            	</div>
            	<div class="process_content">
            		<!--第一步买家申请退货并退款-->
            		<div class="step_content step-1">
            			<div class="pull-left application_content">
            				<form method="POST" action="{{ route('orders.store_refund_with_shipment',32) }}" enctype="multipart/form-data" id="step-1-form">
		            			{{ csrf_field() }}
			                    <input type="hidden" name="order_id" value="">
			                    <ul class="step-1-ul">
			                    	<li>
			                    		<span><i class="red">*</i>退款金额</span>
			                    		<input type="text" >
			                    	</li>
			                    	<li>
			                    		<span><i class="red">*</i>申请说明</span>
			                    		<textarea placeholder="请填写退款原因"></textarea>
			                    	</li>
			                    	<li>
			                    		<span><i class="red">*</i>上传凭证</span>
			                    		<input type="button" value="选择凭证图片">
			                    		<input type="file" name="image" value="" id="" onchange="imgChange(this)">
			                    		<input type="hidden" name="photos" code="" >
			                    	</li>
			                    </ul>
		            		</form>	
		            		<a class="step-1-submit step-submit">提交</a>
		                </div>
		                <!--右侧订单信息-->
		                <div class="pull-left order_lists">
		                	<p class="step_content_title">订单信息</p>
		                	<ul>
		                		<li>
		                			
		                		</li>
		                	</ul>
		                </div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".myorder_classification li").on('click', function () {
                $(".myorder_classification li").removeClass('active');
                $(this).addClass("active");
            });
        });
        // 图片上传入口按钮 input[type=file]值发生改变时触发
	        function imgChange(obj){
	            var filePath=$(obj).val();
	            if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1 || filePath.indexOf("jpeg")!=-1 || filePath.indexOf("gif")!=-1 || filePath.indexOf("bmp")!=-1){
	                $(".fileerrorTip").html("").hide();
	                var arr=filePath.split('\\');
	                var fileName=arr[arr.length-1];
	                $(".showFileName").html(fileName);
	                upLoadBtnSwitch = 1;
                    UpLoadImg();
	            }else{
	                $(".showFileName").html("");
	                $(".fileerrorTip").html("您未选择图片，或者您上传文件格式有误！（当前支持图片格式：jpg，png，jpeg，gif，bmp）").show();
	                upLoadBtnSwitch = 0;
	                return false 
	            }
	        }
	        
	         // 本地图片上传 按钮
	        function UpLoadImg(){
	            var formData = new FormData();
	            formData.append('image',document.getElementById("upload_head").files[0]);
	            $.ajax({
	                url:"{{ route('image.preview') }}",
	                data:formData,
	                dataType:'json',
	                cache: false,  
	                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
	                processData: false,//必须false才会自动加上正确的Content-Type
	                type:'post',            
	                success:function(data){
	                   $(".user_Avatar img").attr('src',data.preview);
	                },error:function(e){
	                    console.log(e);
	                }
	            });
	        }
    </script>
@endsection
