@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Request a refund' : '申请退款')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
             onclick="javascript:history.back(-1);"/>
        <span>申请退款</span>
    </div>
    <div class="refund">
        <div class="refund_con">
        	<div class="refund_content">
        		<!--售后状态-->
	        	<div class="after_sales_status">
	        		<!--第一步-->
	        		@if(false) 
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_5.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Buyer applies for return refund')</span>
	        			</p>
	        		</div>
	        		<!--第2步-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_2.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Seller handles refund Request')</span>
	        			</p>
	        		</div>
	        		<!--第3步-->
	        		@elseif(true)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_3.png') }}">
	        			<p>
	        				<span class="status_title">审核通过等待买家发货</span>
	        			</p>
	        		</div>
	        		<!--第4步-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_6.png') }}">
	        			<p>
	        				<span class="status_title">等待卖家验货</span>
	        			</p>
	        		</div>
	        		<!--第5步成功-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_3.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Refunds are complete')</span>
	        			    <span>@lang('order.Refunds were successful')
                                        , 7200.00@lang('order.It has been returned according to the original hit path')</span>
	        			</p>
	        		</div>
	        		<!--第5步失败-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_4.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Refund failed')</span>
	        			    <span>@lang('order.You can contact online customer service')</span>
	        			</p>
	        		</div>
	        		@endif
	        	</div>
	        	<!--地址信息只在第三步之后显示，1，2不需要-->
	        	@if(true)
	        	<div class="user_address_info">
	        		<div class="read_address_info">
                        <p class="read_address_info_title">@lang('order.return address')</p>
                        <p>
                        	<span>@lang('order.Consignee')：</span>
                        	<span>{{ $refund->seller_info['name'] }}</span>
                        </p>
                        <p>
                        	<span>@lang('order.Contact information')：</span>
                        	<span>{{ $refund->seller_info['phone'] }}</span>
                        </p>
                        <p>
                        	<span>@lang('order.Shipping Address')：</span>
                        	<span>{{ $refund->seller_info['address'] }}</span>
                        </p>
                    </div>
	        	</div>
	        	@endif
	        	<!--申请内容-->
	        	<div class="refund_info">
	        		<!--第一步-->
	        		@if(false)
	        		<form method="POST"
                          action="{{ route('orders.store_refund_with_shipment', ['order' => $order->id]) }}"
                          enctype="multipart/form-data" id="step-1-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
		        		<p>
		        			<span>@lang('order.Refund amount')</span>
		        			<input name="amount" type="text" class="refund_price"
	                                               value="&#165;7200.00"
	                                               readonly>
		        		</p>
		        		<div class="refund_info_item">
		        			<span>@lang('order.Application description')</span>
		        			<textarea name="remark_from_user" placeholder="@lang('order.Please fill in the reason for the refund')" maxlength="200"></textarea>
		        		</div>
		        		<p class="upload_voucher_title">@lang('order.product picture')</p>
		        		<div class="refund_info_item upload_voucher">
		        			<img src="{{ asset('static_m/img/blockImg.png') }}" >
		        			<div class="refunds_photos">
                                <span class="uploader_camera"></span>
                                <span>0/5</span>
                            </div>
		        			<input class="dis_ni" type="file" name="image" accept="image/*" onchange='handleInputChange'>
		        		</div>
	        		</form>
	        		<!--第二步-->
	        		@elseif(false)
	        		<form method="POST" action="{{ route('orders.update_refund', ['order' => $order->id]) }}"
                          enctype="multipart/form-data" id="step-2-form">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
		        		<p>
		        			<span>@lang('order.Refund amount')</span>
		        			<input name="amount" type="text" class="refund_price"
                                   value="&#165;7200.00"
                                   readonly>
		        		</p>
		        		<div class="refund_info_item">
		        			<span>@lang('order.Application description')</span>
		        			<textarea name="remark_from_user" class="step2_textarea" readonly placeholder="@lang('order.Please fill in the reason for the refund')" maxlength="200"></textarea>
		        		</div>
		        		<p class="upload_voucher_title">@lang('order.product picture')</p>
		        		<div class="refund_info_item upload_voucher">
		        			<img src="{{ asset('static_m/img/blockImg.png') }}" >
		        			<div class="refunds_photos dis_n">
                                <span class="uploader_camera"></span>
                                <span>0/5</span>
                            </div>
		        			<input class="dis_ni" type="file" name="image" accept="image/*" onchange='handleInputChange'>
		        		</div>
	        		</form>
	        		<!--第三步第四步第五步都是这个-->
	        		@elseif(true)
		        		<p>
		        			<span>@lang('order.Refund amount')</span>
		        			<input name="amount" type="text" class="refund_price"
                                   value="&#165;7200.00"
                                   readonly>
		        		</p>
		        		<div class="refund_info_item">
		        			<span>@lang('order.Application description')</span>
		        			<textarea name="remark_from_user" readonly placeholder="@lang('order.Please fill in the reason for the refund')" maxlength="200"></textarea>
		        		</div>
		        		<p class="upload_voucher_title">@lang('order.product picture')</p>
		        		<div class="refund_info_item upload_voucher">
		        			@for($i = 0; $i < 5; $i++ )
		        			<img src="{{ asset('static_m/img/blockImg.png') }}" >
		        		    @endfor
		        		</div>
	        		@endif
	        	</div>
	        	<!--物流信息从第三步开始显示-->
	        	@if(true)
	        	<div class="Logistics_info">
	        		<form method="POST"
                          action="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}"
                          enctype="multipart/form-data" id="step-3-form">
	                    {{ method_field('PUT') }}
	                    {{ csrf_field() }}
	                    <input type="hidden" name="order_id" value="{{ $order->id }}">
		        		<ul class="step-ul">
		        			@if(true)
		        			<li>
	                            <span>@lang('order.Logistics company')：</span>
	                            <input type="text" name="shipment_company" placeholder="@lang('order.Please fill in the logistics company')">
	                        </li>
	                        <li>
	                            <span>@lang('order.shipment number')：</span>
	                            <input type="text" name="shipment_sn" placeholder="@lang('order.Please fill in the Logistics single number')">
	                        </li>
	                        <li>
	                            <span>@lang('order.Memo Content')：</span>
	                            <input name="remark_for_shipment_from_user" placeholder="@lang('order.Please describe in detail the reason for the refund')">
	                        </li>
	                        <li>
	                            <span>@lang('order.Logistics documents')：</span>
	                            <img src="{{ asset('static_m/img/blockImg.png') }}" >
			        			<div class="refunds_photos">
	                                <span class="uploader_camera"></span>
	                                <span>0/5</span>
	                            </div>
			        			<input class="dis_ni" type="file" name="image" accept="image/*" onchange='handleInputChange'>
	                        </li>
		        		    @else
	                        <li>
	                            <span>@lang('order.Logistics company')：</span>
	                            <span>{{ $shipment_company }}</span>
	                        </li>
	                        <li>
	                            <span>@lang('order.shipment number')：</span>
	                            <span>{{ $refund->shipment_sn }}</span>
	                        </li>
	                        <li>
	                            <span>@lang('order.Memo Content')：</span>
	                            <span>{{ $refund->remark_for_shipment_from_user }}</span>
	                        </li>
	                        <li>
	                            <span>@lang('order.Logistics documents')：</span>
	                             @for($i = 0; $i < 5; $i++ )
	                                <div class='refund-path'>
	                                    <img src="{{ asset('static_m/img/blockImg.png') }}" >
	                                </div>
	                            @endfor
	                        </li>
	                        @endif
	                    </ul>
                    </form>
	        	</div>
	        	@endif
	        	<!--订单内容-->
	        	<div class="order_products">
	        		@for ($i = 0; $i < 2; $i++)
	        			<div class="ordDetail_item">
		                    <img src="{{ asset('static_m/img/blockImg.png') }}"/>
		                    <div>
		                        <div class="ordDetailName">
		                            <a href="">卓页美业长直假发片可拆卸水洗亚麻</a>
		                        </div>
		                        <div>
		                            <span>
		                                @lang('basic.users.quantity')：2
		                                &nbsp;&nbsp;
		                            </span>
		                            <span>
		                                <a href="">颜色：{{ App::isLocale('en') ? 'yellow' : '黄' }}</a>
		                            </span>
		                        </div>
		                        <div class="ordDetailPri">
		                            <span>&#165;</span>
		                            <span>500.00</span>
		                        </div>
		                    </div>
		                </div>
	        		@endfor
	        		<div class="order_info">
	        			<p>
		        			<span>@lang('order.Order time')：</span>
		        			<span>2010-2-05 10:22:50</span>
		        		</p>
		        		<p>
		        			<span>@lang('order.Order number')：</span>
		        			<span>45748574867585023448</span>
		        		</p>
	        		</div>
	        	</div>
        	</div>
        	<div class="refund_btns">
        		<!--第一步显示-->
        		@if(false)
	        	<a href="javascript:void(0);" class="doneBtn submint_one">@lang('app.submit')</a>
	        	<!--第二步显示-->
	        	@elseif(false)
	        	<div>
	        		<a class="ordDetailBtnC change_btn" href="javascript:void(0);" data-url="">
	                    @lang('order.Modify')
	                </a>
	                <a class="ordDetailBtnC save_btn dis_ni" href="javascript:void(0);" data-url="">
	                    @lang('order.Save changes')
	                </a>
	                <a class="ordDetailBtnS Revocation_btn" href="javascript:void(0);" code="{{ route('orders.revoke_refund', ['order' => 1]) }}">
	                    @lang('order.Revocation of application')
	                </a>
	        	</div>
	        	<!--第三步显示，第四与第五步没有按钮不需要显示-->
	        	@else
	        	<a href="javascript:void(0);" class="doneBtn logistics_submint">@lang('app.submit')</a>
	        	@endif
	        </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        $(function () {
        	$(".refund_con").css("min-height",$(window).height()-$(".headerBar ").height());
        	//第一步表单提交
        	$(".submint_one").on("click",function(){
        		$("#step-1-form").submit();
        	})
        	//第二步修改申请
        	$(".change_btn").on("click",function(){
        		$(this).addClass("dis_ni");
        		$(".save_btn").removeClass("dis_ni");
        		$(".refunds_photos").removeClass("dis_n");
        	})
        	//保存修改
        	$(".save_btn").on("click",function(){
        		$(this).addClass("dis_ni");
        		$(".change_btn").removeClass("dis_ni");
        		$(".refunds_photos").addClass("dis_n");
        		$("#step-2-form").submit();
        	})
        	//撤销申请
        	$(".Revocation_btn").on("click",function(){
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
                        window.location.href = "{{ route('mobile.orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 403) {
                            layer.open({
		                        content: "@lang('app.Unable to complete operation')"
		                        , skin: 'msg'
		                        , time: 2 //2秒后自动关闭
		                    });
                        }
                    }
                });
        	})
        	//提交物流信息
        	$(".logistics_submint").on("click",function(){
        		$("#step-3-form").submit();
        	})
        	
        	// 全局对象，不同function使用传递数据
			const imgFile = {};
			function handleInputChange (event) {
			    // 获取当前选中的文件
			    const file = event.target.files[0];
			    const imgMasSize = 1024 * 1024 * 10; // 10MB
			    // 检查文件类型
			    if(['jpeg', 'png', 'gif', 'jpg'].indexOf(file.type.split("/")[1]) < 0){
			        // 自定义报错方式
			        // Toast.error("文件类型仅支持 jpeg/png/gif！", 2000, undefined, false);
			        return;
			    }
			    // 文件大小限制
			    if(file.size > imgMasSize ) {
			        // 文件大小自定义限制
			        // Toast.error("文件大小不能超过10MB！", 2000, undefined, false);
			        return;
			    }
			    // 判断是否是ios
			    if(!!window.navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)){
			        // iOS
			        transformFileToFormData(file);
			        return;
			    }
			    // 图片压缩之旅
			    transformFileToDataUrl(file);
			}
			// 将File append进 FormData
			function transformFileToFormData (file) {
			    const formData = new FormData();
			    // 自定义formData中的内容
			    // type
			    formData.append('type', file.type);
			    // size
			    formData.append('size', file.size || "image/jpeg");
			    // name
			    formData.append('name', file.name);
			    // lastModifiedDate
			    formData.append('lastModifiedDate', file.lastModifiedDate);
			    // append 文件
			    formData.append('file', file);
			    // 上传图片
			    uploadImg(formData);
			}
			// 将file转成dataUrl
			function transformFileToDataUrl (file) {
			    const imgCompassMaxSize = 200 * 1024; // 超过 200k 就压缩
			
			    // 存储文件相关信息
			    imgFile.type = file.type || 'image/jpeg'; // 部分安卓出现获取不到type的情况
			    imgFile.size = file.size;
			    imgFile.name = file.name;
			    imgFile.lastModifiedDate = file.lastModifiedDate;
			
			    // 封装好的函数
			    const reader = new FileReader();
			
			    // file转dataUrl是个异步函数，要将代码写在回调里
			    reader.onload = function(e) {
			        const result = e.target.result;
			
			        if(result.length < imgCompassMaxSize) {
			            compress(result, processData, false );    // 图片不压缩
			        } else {
			            compress(result, processData);            // 图片压缩
			        }
			    };
			
			    reader.readAsDataURL(file);
			}
			// 使用canvas绘制图片并压缩
			function compress (dataURL, callback, shouldCompress = true) {
			    const img = new window.Image();
			
			    img.src = dataURL;
			
			    img.onload = function () {
			        const canvas = document.createElement('canvas');
			        const ctx = canvas.getContext('2d');
			
			        canvas.width = img.width;
			        canvas.height = img.height;
			
			        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
			
			        let compressedDataUrl;
			
			        if(shouldCompress){
			            compressedDataUrl = canvas.toDataURL(imgFile.type, 0.2);
			        } else {
			            compressedDataUrl = canvas.toDataURL(imgFile.type, 1);
			        }
			
			        callback(compressedDataUrl);
			    }
			}
			
			function processData (dataURL) {
			    // 这里使用二进制方式处理dataUrl
			    const binaryString = window.atob(dataUrl.split(',')[1]);
			    const arrayBuffer = new ArrayBuffer(binaryString.length);
			    const intArray = new Uint8Array(arrayBuffer);
			    const imgFile = this.imgFile;
			
			    for (let i = 0, j = binaryString.length; i < j; i++) {
			        intArray[i] = binaryString.charCodeAt(i);
			    }
			
			    const data = [intArray];
			
			    let blob;
			
			    try {
			        blob = new Blob(data, { type: imgFile.type });
			    } catch (error) {
			        window.BlobBuilder = window.BlobBuilder ||
			            window.WebKitBlobBuilder ||
			            window.MozBlobBuilder ||
			            window.MSBlobBuilder;
			        if (error.name === 'TypeError' && window.BlobBuilder){
			            const builder = new BlobBuilder();
			            builder.append(arrayBuffer);
			            blob = builder.getBlob(imgFile.type);
			        } else {
			            // Toast.error("版本过低，不支持上传图片", 2000, undefined, false);
			            throw new Error('版本过低，不支持上传图片');
			        }
			    }
			
			    // blob 转file
			    const fileOfBlob = new File([blob], imgFile.name);
			    const formData = new FormData();
			
			    // type
			    formData.append('type', imgFile.type);
			    // size
			    formData.append('size', fileOfBlob.size);
			    // name
			    formData.append('name', imgFile.name);
			    // lastModifiedDate
			    formData.append('lastModifiedDate', imgFile.lastModifiedDate);
			    // append 文件
			    formData.append('file', fileOfBlob);
			
			    uploadImg(formData);
			}
			
			// 上传图片
			function uploadImg (formData) {
			    const xhr = new XMLHttpRequest();
			
			    // 进度监听
			    xhr.upload.addEventListener('progress', (e)=>{console.log(e.loaded / e.total)}, false);
			    // 加载监听
			    // xhr.addEventListener('load', ()=>{console.log("加载中");}, false);
			    // 错误监听
			    xhr.addEventListener('error', ()=>{Toast.error("上传失败！", 2000, undefined, false);}, false);
			    xhr.onreadystatechange = function () {
			        if (xhr.readyState === 4) {
			            const result = JSON.parse(xhr.responseText);
			            if (xhr.status === 200) {
			                // 上传成功
			                
			
			            } else {
			                // 上传失败
			            }
			        }
			    };
			    xhr.open('POST', '/uploadUrl' , true);
			    xhr.send(formData);
			}
        	
        });
    </script>
@endsection
