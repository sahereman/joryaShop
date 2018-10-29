@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
    <div class="evaluate_commont">
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
                    <a href="{{ route('orders.index') }}">评价</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
            <!--右侧内容-->
            <div class="comment_content">
            	@for ($i = 0; $i <3; $i++)
            		<div class="evaluation_order">
            			<table>
            				<thead>
            					<th></th>
            					<th>商品</th>
            					<th>规格</th>
            					<th>单价</th> 
            					<th>数量</th>
            					<th>小计</th>
            				</thead>
            				<tbody>
	            				<tr>
		                			<td class="col-pro-img">
		                				<a href="">
	                                        <img src="{{ asset('img/order-pro.png') }}">
	                                    </a>
		                			</td>
	                                <td class="col-pro-info">
	                                    <p class="p-info">
	                                        <a class="commodity_description"  href="">卓页美业长直假发片</a>
	                                    </p>
	                                </td>
	                                <td class="col-pro-speci">
	                                    <p class="p-info">
	                                        <a class="specifications"  href="">蓝色</a>
	                                    </p>
	                                </td>
	                                <td class="col-price">
	                                    <p class="p-price">
	                                        <em>¥</em>
	                                        <span>50.00</span>
	                                    </p>
	                                </td>
	                                <td class="col-quty">
	                                	<p>1</p>
	                                </td>
	                                <td class="col-pay">
	                                    <p>
	                                        <em>¥</em>
	                                        <span>120.00</span>
	                                    </p>
	                                </td>
		                		</tr>
	            			</tbody>
            			</table>
            			<div class="evaluation_content">
            				<p class="evaluat_title">请填写您宝贵的建议</p>
            				<!--
            					**
            					* 注：循环是请把下面的所有的{{ $i }}切换成对应循环的下标值，即第几个否则评价的五星会失效
            					* 切记！！！！
            					* 
            					* 
            					* 
            					-->
            				<div class="five_star_evaluation">
            					<div class="five_star_one star_area">
            						<p>
            							<i>*</i>
            							<span>综合评分</span>
            						</p>
            						<div class="starability-basic">
            							
            							<input type="radio" id="rate5-1_{{ $i }}" name="rating[45]" value="5" />
										<label for="rate5-1_{{ $i }}" title="Amazing"></label>
								
										<input type="radio" id="rate4-1_{{ $i }}" name="rating" value="4" />
										<label for="rate4-1_{{ $i }}" title="Very good"></label>
								
										<input type="radio" id="rate3-1_{{ $i }}" name="rating" value="3" />
										<label for="rate3-1_{{ $i }}" title="Average"></label>
								
										<input type="radio" id="rate2-1_{{ $i }}" name="rating" value="2" />
										<label for="rate2-1_{{ $i }}" title="Not good"></label>
								
										<input type="radio" id="rate1-1_{{ $i }}" name="rating" value="1" />
										<label for="rate1-1_{{ $i }}" title="Terrible"></label>
            						</div>
            					</div>
            					<div class="five_star_two star_area">
            						<p>
            							<i>*</i>
            							<span>描述相符</span>
            						</p>
            						<div class="starability-basic">
            							<input type="radio" id="rate5-2_{{ $i }}" name="rating" value="5" />
										<label for="rate5-2_{{ $i }}" title="Amazing"></label>
								
										<input type="radio" id="rate4-2_{{ $i }}" name="rating" value="4" />
										<label for="rate4-2_{{ $i }}" title="Very good"></label>
								
										<input type="radio" id="rate3-2_{{ $i }}" name="rating" value="3" />
										<label for="rate3-2_{{ $i }}" title="Average"></label>
								
										<input type="radio" id="rate2-2_{{ $i }}" name="rating" value="2" />
										<label for="rate2-2_{{ $i }}" title="Not good"></label>
								
										<input type="radio" id="rate1-2_{{ $i }}" name="rating" value="1" />
										<label for="rate1-2_{{ $i }}" title="Terrible"></label>
            						</div>
            					</div>
            					<div class="five_star_three star_area">
            						<p>
            							<i>*</i>
            							<span>物流服务</span>
            						</p>
            						<div class="starability-basic">
            							<input type="radio" id="rate5-3_{{ $i }}" name="rating" value="5" />
										<label for="rate5-3_{{ $i }}" title="Amazing"></label>
								
										<input type="radio" id="rate4-3_{{ $i }}" name="rating" value="4" />
										<label for="rate4-3_{{ $i }}" title="Very good"></label>
								
										<input type="radio" id="rate3-3_{{ $i }}" name="rating" value="3" />
										<label for="rate3-3_{{ $i }}" title="Average"></label>
								
										<input type="radio" id="rate2-3_{{ $i }}" name="rating" value="2" />
										<label for="rate2-3_{{ $i }}" title="Not good"></label>
								
										<input type="radio" id="rate1-3_{{ $i }}" name="rating" value="1" />
										<label for="rate1-3_{{ $i }}" title="Terrible"></label>
            						</div>
            					</div>
							</div>
            				<textarea placeholder="请输入少于200字的商品评价"></textarea>
            				<div class="picture_area">
            					<p>
        							<i>*</i>
        							<span>上传图片</span>
        						</p>
        						<div class="pictures" code="{{ $i }}">
        							<div class="pictures_btn" code="{{ $i }}">
        								<img src="{{ asset('img/pic_upload.png') }}">
        									<input type="hidden" name="img[45]" value="aaa.jpg,bb.jpg" />
        							</div>
        						</div>
            				</div>
            			</div>
            	    </div>
				@endfor
				<div class="sub_evaluation_area">
					<a class="sub_evaluation" href="">提交</a>
				</div>
				<form method="post" enctype="multipart/form-data"  id="formAddHandlingFee" style="display:none">
		            <div class="loadLine">
		                <a href="javascript:;" class="loadImgBtn" onclick="loadImgEnter(this)">选择本地图片</a>
		                <input type="file" name="image" id="file" style="display:none" onchange="imgChange(this)" />
		                <p class="fileerrorTip"></p>
		                <p class="showFileName"></p>
		            </div>
		        </form>
		        <input type="button" value="点击上传" onclick="UpLoadImg()"  id="trueUpload" style="display:none">
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
    	var which_click = 0;
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete").show();
            });
            $(".pictures_btn").on("click",function(){
            	which_click=$(this).attr("code");
            	$('.loadImgBtn').click();
            })
            
        });
        function loadImgEnter(obj){
	            $(obj).siblings('input').click();
	        }
            // 图片上传入口按钮 input[type=file]值发生改变时触发
	        function imgChange(obj){
	            var filePath=$(obj).val();
	            if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1 || filePath.indexOf("jpeg")!=-1 || filePath.indexOf("gif")!=-1 || filePath.indexOf("bmp")!=-1){
	                $(".fileerrorTip").html("").hide();
	                var arr=filePath.split('\\');
	                var fileName=arr[arr.length-1];
	                $(".showFileName").html(fileName);
	                upLoadBtnSwitch = 1;
	                $("#trueUpload").click();
	            }else{
	                $(".showFileName").html("");
	                $(".fileerrorTip").html("您未选择图片，或者您上传文件格式有误！（当前支持图片格式：jpg，png，jpeg，gif，bmp）").show();
	                upLoadBtnSwitch = 0;
	                return false 
	            }
	        }
	        
	         // 本地图片上传 按钮
	        function UpLoadImg(){
	            var formData = new FormData($( "#formAddHandlingFee" )[0]);
	            $.ajax({
	                url:"{{ route('image.preview') }}",
	                data:formData,
	                dataType:'json',
	                cache: false,  
	                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
	                processData: false,//必须false才会自动加上正确的Content-Type
	                type:'post',            
	                success:function(data){
	                   var html="<div><img src='"+data.preview+"'></div>"
	                   $(".pictures[code='"+which_click+"']").append(html)
	                },error:function(e){
	                    console.log(e);
	                }
	            });
	        }
    </script>
@endsection