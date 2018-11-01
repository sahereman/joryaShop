@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
    <div class="User_center my_orders">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">我的订单</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">订单详情</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">申请售后</a>
                </p>
            </div>
            <!--申请内容-->
            <div class="refund_content">
            	<div class="technological_process">
            		<!--分步骤显示图片一共四张-->
            		<img src="{{ asset('img/process-refund-1.png') }}">
            		<img src="{{ asset('img/process-refund-2.png') }}" style="display: none;">
            		<img src="{{ asset('img/process-refund-3.png') }}" style="display: none;">
            		<!--process-5.png图片是退款失败时展示-->
            		<img src="{{ asset('img/process-refund-4.png') }}" style="display: none;">
            	</div>
            	<div class="process_content">
            		<!--左侧内容-->
            		<div class="pull-left left_content">
            			<!--第一步买家申请退货并退款-->
	            		<div class="step_content step-1"  style="display: none;">
            				<form method="POST" action="{{ route('orders.store_refund',32) }}" enctype="multipart/form-data" id="step-1-form">
		            			{{ csrf_field() }}
			                    <input type="hidden" name="order_id" value="">
			                    <ul class="step-1-ul step-ul">
			                    	<li>
			                    		<span><i class="red">*</i>退款金额：</span>
			                    		<input type="text" class="refund_amount" value="55.00" readonly>
			                    	</li>
			                    	<li>
			                    		<span><i class="red">*</i>申请说明：</span>
			                    		<textarea class="reasons_for_refunds" placeholder="请填写退款原因"></textarea>
			                    		<span class="remainder">200</span>
			                    	</li>
			                    </ul>
		            		</form>	
		            		<p class="btn_submit_area">
		            			<a class="step-1-submit step-submit">提交</a>
		            		</p>
	            		</div>
	            		<!--第二步卖家处理退货申请-->
	            		<div class="step_content step-2"  style="display: none;">
	            			<form method="POST" action="{{ route('orders.update_refund',32) }}" enctype="multipart/form-data" id="step-2-form">
		            			{{ csrf_field() }}
			                    <input type="hidden" name="order_id" value="">
			                    <ul class="step-1-ul step-ul">
			                    	<li>
			                    		<span><i class="red">*</i>退款金额：</span>
			                    		<input type="text" class="refund_amount no_border" value="55.00" readonly>
			                    	</li>
			                    	<li>
			                    		<span><i class="red">*</i>申请说明：</span>
			                    		<textarea class="reasons_for_refunds no_border" readonly placeholder="请填写退款原因">包装不好看，膏体也不好，赞一个，想下单的小伙伴赶快吧，真的超级赞，下次继续光顾。在品牌升级之后更加心水 每个口红都是爱的 有的
			                    		</textarea>
			                    		<span class="remainder hidden">200</span>
			                    	</li>
			                    </ul>
		            		</form>	
		            		<p class="btn_submit_area">
		            			<a class="step-2-submit-1 step-submit">修改申请</a>
		            			<a class="step-2-submit-2 step-submit dis_ni">保存修改</a>
		            			<a class="step-2-submit-3 normal-submit" code="{{ route('orders.revoke_refund', 32) }}">撤销申请</a>
		            		</p>
	            		</div>
	            		<!--第三步退款成功-->
	            		<div class="step_content step-3" style="display: none;">
	            			<div class="read_info last_level">
	            				<p class="read_info_title">审核通过，退款成功<span>退款成功！ 550元已按照原打款路径退回。</span></p>
	            				<ul class="step-ul">
	            					<li>
			                    		<span>退款金额：</span>
			                    		<span class="amount_num">55.00</span>
			                    	</li>
			                    	<li>
			                    		<span>退款说明：</span>
			                    		<p>买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要</p>
			                    	</li>
	            				</ul>
	            			</div>
	            		</div>
	            		<!--第四步退款失败-->
	            		<div class="step_content step-4">
	            			<div class="read_info last_level">
	            				<p class="read_info_title">审核未通过<span>你可以修改后再次<a>发起申请</a>，联系在线客服或者拨打400电话</span></p>
	            				<ul class="step-ul">
	            					<li>
			                    		<span>退款金额：</span>
			                    		<span class="amount_num">55.00</span>
			                    	</li>
			                    	<li>
			                    		<span>退款说明：</span>
			                    		<p>买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要</p>
			                    	</li>
			                    	<li class="red">
			                    		<span>卖家回复：</span>
			                    		<p>买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要买错了不想要</p>
			                    	</li>
	            				</ul>
	            				<p class="btn_submit_area">
			            			<a class="step-5-submit-1 step-submit">确定</a>
			            			<a class="step-5-submit-2 normal-submit" code="{{ route('orders.revoke_refund', 32) }}">撤销申请</a>
			            		</p>
	            			</div>
	            		</div>
            		</div>
            		<!--右侧订单信息-->
	                <div class="pull-left order_lists">
	                	<p class="step_content_title">订单信息</p>
	                	<ul>
	                		@for ($i = 0; $i < 10; $i++)
							    <li>
							    	<a href="">
							    		<div class="info_img">
			                				<img src="{{ asset('img/refund-lists.png') }}">
			                			</div>
			                			<div class="order_lists_info">
			                				<p><span>女士长发</span><span>（无毛躁感）</span></p>
			                				<p>颜色：蓝色</p>
			                				<p>单价：￥55.00  x  1</p>
			                			</div>
							    	</a>
		                		</li>
							@endfor
							<!--这一条内容显示订单信息不需要循环-->
							<li class="order_lists_total">
								<p>
									<span>订单时间：</span>
									<span>2018-7-29  19:43:41 </span>
								</p>
								<p>
									<span>订单编号：</span>
									<span>5646565654</span>
								</p>
								<p>
									<span>邮费：</span>
									<span><i>￥</i>10.00</span>
								</p>
								<p>
									<span>合计：</span>
									<span><i>￥</i>555.00  （含邮费）</span>
								</p>
							</li>
	                    </ul>
	                </div>
            	</div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
    	var set_finish = false;
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".myorder_classification li").on('click', function () {
                $(".myorder_classification li").removeClass('active');
                $(this).addClass("active");
            });
            //页面加载时判断右侧订单信息的高度
            var h = $(".left_content").height();
            $(".order_lists ul").css("height",parseInt(h - 65));
            //上传图片
            $(".refunds_photos").on("click",function(){
            	var img_num = $(this).parents('li').find(".refund-path");
            	if(img_num.length<3) {
            		$("#refunds_photos_file").click();	
            	}else {
            		layer.msg('最多上传3张图片！！');
            	}
            })
            $(".refunds_2").on("click",function(){
            	var img_num = $(this).parents('li').find(".refund-path");
            	if(img_num.length<3) {
            		$("#refunds_photos_2").click();	
            	}else {
            		layer.msg('最多上传3张图片！！');
            	}
            })
            //图片删除
            $(".del_btn").on('click',function(){
            	$(this).parents('.refund-path').remove();
            })
            //第一步提交退款申请
            $(".step-1-submit").on("click",function(){
            	set_path("#step-1-form");
	        	if(set_finish==true){
	        		$("#step-1-form").submit();
	        	}
            })
            //判断文本域的字数
            $(".reasons_for_refunds").keyup(function(){
	            var text = $(this).val();
	            //中文字数统计
	            str = (text.replace(/\w/g,"")).length;
	            //非汉字的个数
	            abcnum = text.length-str;
	            total = str+abcnum;
	            if(total > 200){
	                $(this).val($(this).val().substring(0,200));
	                $(".remainder").html('0');
	                layer.msg('字数超过上限')
	            }else {
	            	var num = 200 - total;
	                $(".remainder").html(num);
	            }
		    })
		    $(".reasons_for_refunds").change(function(){
	            var text = $(this).val();
	            //中文字数统计
	            str = (text.replace(/\w/g,"")).length;
	            //非汉字的个数
	            abcnum = text.length-str;
	            total = str+abcnum;
	            console.log(total)
	            if(total > 200){
	                $(this).val($(this).val().substring(0,200));
	                $(".remainder").html('0');
	                layer.msg('字数超过上限')
	            }else {
	            	var num = 200 - total;
	                $(".remainder").html(num);
	            }
		    })
		    //修改申请
		    $(".step-2-submit-1").on("click",function(){
		    	$(".step-2 input").removeClass("no_border");
		    	$(".step-2 textarea").removeClass("no_border");
		    	$(".step-2 textarea").prop("readonly",false);
		    	$(".step-2 .del_btn").removeClass('dis_n');
		    	$(".refunds_2").removeClass('dis_n');
		    	$(".remainder").removeClass('hidden');
		    	$(this).addClass("dis_ni");
		    	$(".step-2-submit-2").removeClass("dis_ni");
		    	
		    })
		    //提交保存修改
		    $(".step-2-submit-2").on("click",function(){
		    	set_path("#step-2-form");
	        	if(set_finish==true){
	        		$("#step-2-form").submit();
	        	}
		    })
		    //撤销退款申请
            $(".step-2-submit-3").on("click",function(){
            	var data = {
                    _method: "PATCH",
                    _token: "{{ csrf_token() }}",
                };
                var url = $(this).attr('code');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.href = "{{ route('orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 403) {
                        	layer.open({
							  type: 1, 
							  content: '您无权限执行此操作！' 
							});
                        }
                    }
                });
            })
            //撤销退款申请
            $(".step-5-submit-2").on("click",function(){
            	var data = {
                    _method: "PATCH",
                    _token: "{{ csrf_token() }}",
                };
                var url = $(this).attr('code');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.href = "{{ route('orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 403) {
                        	layer.open({
							  type: 1, 
							  content: '您无权限执行此操作！' 
							});
                        }
                    }
                });
            })
        });
        // 图片上传入口按钮 input[type=file]值发生改变时触发
	        function imgChange(obj){
	            var filePath=$(obj).val();
	            if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1 || filePath.indexOf("jpeg")!=-1 || filePath.indexOf("gif")!=-1 || filePath.indexOf("bmp")!=-1){
	                var arr=filePath.split('\\');
	                var fileName=arr[arr.length-1];
	                upLoadBtnSwitch = 1;
                    UpLoadImg(obj);
	            }else{
	                layer.open({
					  title: '提示',
					  content: '您未选择图片，或者您上传文件格式有误！（当前支持图片格式：jpg，png，jpeg，gif，bmp）'
					});
	                upLoadBtnSwitch = 0;
	                return false 
	            }
	        }
	        
	         // 本地图片上传 按钮
	        function UpLoadImg(obj){
	            var formData = new FormData();
	            formData.append('image',$(obj)[0].files[0]);
	            $.ajax({
	                 url:"{{ route('comment_image.upload') }}",
	                data:formData,
	                dataType:'json',
	                cache: false,  
	                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
	                processData: false,//必须false才会自动加上正确的Content-Type
	                type:'post',            
	                success:function(data){
	                   var html = "<div class='refund-path' data-path='"+ data.path +"'>"+
	                    		  "<img src='" + data.preview + "' data-path='"+ data.path +"'>"+
	                    		  "<img class='del_btn' src='{{ asset('img/delete_refund_photos.png') }}'/>"+
	                    		  "</div>"
                    $(obj).parents('li').append(html);
	                },error:function(e){
	                    console.log(e);
	                }
	            });
	        }
	        function set_path(dom) {
	        	var order_list = $(dom).find(".refund-path");
	        	var path_url = "";
	        	$.each(order_list, function(i,n) {
	        		path_url+=$(n).attr('data-path') + ",";
	        	});
	        	path_url=path_url.substring(0, path_url.length - 1);
        		$(dom).find("input[name='photos']").val(path_url);
	        	set_finish=true;
	        	return set_finish;
	        }
    </script>
@endsection
