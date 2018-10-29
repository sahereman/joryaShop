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
            	<form method="POST" action="{{ route('orders.store_comment',$order->id) }}" enctype="multipart/form-data">
	                {{ csrf_field() }}
	                <input type="hidden" name="order_id" value="{{ $order->id }}">
	                @foreach($order->snapshot as $order_item)
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
	                                        <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
	                                    </a>
	                                </td>
	                                <td class="col-pro-info">
	                                    <p class="p-info">
	                                        <a class="commodity_description"
	                                           href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ $order_item['sku']['product']['name_zh'] }}</a>
	                                    </p>
	                                </td>
	                                <td class="col-pro-speci">
	                                    <p class="p-info">
	                                        <a class="specifications"
	                                           href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ $order_item['sku']['name_zh'] }}</a>
	                                    </p>
	                                </td>
	                                <td class="col-price">
	                                    <p class="p-price">
	                                        <em>¥</em>
	                                        <span>{{ $order_item['price'] }}</span>
	                                    </p>
	                                </td>
	                                <td class="col-quty">
	                                    <p>{{ $order_item['number'] }}</p>
	                                </td>
	                                <td class="col-pay">
	                                    <p>
	                                        <em>¥</em>
	                                        <span>{{ $order_item['price'] * $order_item['number'] }}</span>
	                                    </p>
	                                </td>
	                            </tr>
	                            </tbody>
	                        </table>
	                        <div class="evaluation_content">
	                            <p class="evaluat_title">请填写您宝贵的建议</p>
	                            {{--**
	                                * 注：循环是请把下面的所有的{{ $order_item->id }}切换成对应循环的下标值，即第几个否则评价的五星会失效
	                                * 切记！！！！
	                                * --}}
	                            <div class="five_star_evaluation">
	                                <div class="five_star_one star_area">
	                                    <p>
	                                        <i>*</i>
	                                        <span>综合评分</span>
	                                    </p>
	                                    <div class="starability-basic">
	
	                                        <input type="radio" id="rate5-1_{{ $order_item['id'] }}"
	                                               name="composite_index[{{ $order_item['id'] }}]" value="5"/>
	                                        <label for="rate5-1_{{ $order_item['id'] }}" title="Amazing"></label>
	
	                                        <input type="radio" id="rate4-1_{{ $order_item['id'] }}"
	                                               name="composite_index[{{ $order_item['id'] }}]" value="4"/>
	                                        <label for="rate4-1_{{ $order_item['id'] }}" title="Very good"></label>
	
	                                        <input type="radio" id="rate3-1_{{ $order_item['id'] }}"
	                                               name="composite_index[{{ $order_item['id'] }}]" value="3"/>
	                                        <label for="rate3-1_{{ $order_item['id'] }}" title="Average"></label>
	
	                                        <input type="radio" id="rate2-1_{{ $order_item['id'] }}"
	                                               name="composite_index[{{ $order_item['id'] }}]" value="2"/>
	                                        <label for="rate2-1_{{ $order_item['id'] }}" title="Not good"></label>
	
	                                        <input type="radio" id="rate1-1_{{ $order_item['id'] }}"
	                                               name="composite_index[{{ $order_item['id'] }}]" value="1"/>
	                                        <label for="rate1-1_{{ $order_item['id'] }}" title="Terrible"></label>
	                                    </div>
	                                </div>
	                                <div class="five_star_two star_area">
	                                    <p>
	                                        <i>*</i>
	                                        <span>描述相符</span>
	                                    </p>
	                                    <div class="starability-basic">
	                                        <input type="radio" id="rate5-2_{{ $order_item['id'] }}"
	                                               name="description_index[{{ $order_item['id'] }}]" value="5"/>
	                                        <label for="rate5-2_{{ $order_item['id'] }}" title="Amazing"></label>
	
	                                        <input type="radio" id="rate4-2_{{ $order_item['id'] }}"
	                                               name="description_index[{{ $order_item['id'] }}]" value="4"/>
	                                        <label for="rate4-2_{{ $order_item['id'] }}" title="Very good"></label>
	
	                                        <input type="radio" id="rate3-2_{{ $order_item['id'] }}"
	                                               name="description_index[{{ $order_item['id'] }}]" value="3"/>
	                                        <label for="rate3-2_{{ $order_item['id'] }}" title="Average"></label>
	
	                                        <input type="radio" id="rate2-2_{{ $order_item['id'] }}"
	                                               name="description_index[{{ $order_item['id'] }}]" value="2"/>
	                                        <label for="rate2-2_{{ $order_item['id'] }}" title="Not good"></label>
	
	                                        <input type="radio" id="rate1-2_{{ $order_item['id'] }}"
	                                               name="description_index[{{ $order_item['id'] }}]" value="1"/>
	                                        <label for="rate1-2_{{ $order_item['id'] }}" title="Terrible"></label>
	                                    </div>
	                                </div>
	                                <div class="five_star_three star_area">
	                                    <p>
	                                        <i>*</i>
	                                        <span>物流服务</span>
	                                    </p>
	                                    <div class="starability-basic">
	                                        <input type="radio" id="rate5-3_{{ $order_item['id'] }}"
	                                               name="shipment_index[{{ $order_item['id'] }}]" value="5"/>
	                                        <label for="rate5-3_{{ $order_item['id'] }}" title="Amazing"></label>
	
	                                        <input type="radio" id="rate4-3_{{ $order_item['id'] }}"
	                                               name="shipment_index[{{ $order_item['id'] }}]" value="4"/>
	                                        <label for="rate4-3_{{ $order_item['id'] }}" title="Very good"></label>
	
	                                        <input type="radio" id="rate3-3_{{ $order_item['id'] }}"
	                                               name="shipment_index[{{ $order_item['id'] }}]" value="3"/>
	                                        <label for="rate3-3_{{ $order_item['id'] }}" title="Average"></label>
	
	                                        <input type="radio" id="rate2-3_{{ $order_item['id'] }}"
	                                               name="shipment_index[{{ $order_item['id'] }}]" value="2"/>
	                                        <label for="rate2-3_{{ $order_item['id'] }}" title="Not good"></label>
	
	                                        <input type="radio" id="rate1-3_{{ $order_item['id'] }}"
	                                               name="shipment_index[{{ $order_item['id'] }}]" value="1"/>
	                                        <label for="rate1-3_{{ $order_item['id'] }}" title="Terrible"></label>
	                                    </div>
	                                </div>
	                            </div>
	                            <textarea name="content[{{ $order_item['id'] }}]" placeholder="请输入少于200字的商品评价"></textarea>
	                            <input id="###-[{{ $order_item['id'] }}]" type="hidden" name="photos[{{ $order_item['id'] }}]"
	                                   value="">
	                            <div class="picture_area">
	                                <p>
	                                    <i>*</i>
	                                    <span>上传图片</span>
	                                </p>
	                                <div class="pictures" code="{{ $order_item['id'] }}">
	                                    <div class="pictures_btn" code="{{ $order_item['id'] }}">
	                                        <img src="{{ asset('img/pic_upload.png') }}">
	                                        <input type="file" name="avatar" value=""  id="{{ $order_item['id'] }}" code="{{ $order_item['id'] }}" onchange="imgChange(this)" >
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                @endforeach
	                <div class="sub_evaluation_area">
	                    <a class="sub_evaluation" href="">提交</a>
	                </div>
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
            $(".pictures_btn").on("click",function () {
                which_click = $(this).attr("code");
                console.log($(this).find("input[type='file']").attr('code'))
                $(document).on("click",".pictures_btn input",function(){})
            })
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
                UpLoadImg(obj);
            }else{
                $(".showFileName").html("");
                $(".fileerrorTip").html("您未选择图片，或者您上传文件格式有误！（当前支持图片格式：jpg，png，jpeg，gif，bmp）").show();
                upLoadBtnSwitch = 0;
                return false 
            }
        }
        
         // 本地图片上传 按钮
        function UpLoadImg(obj){
            var formData = new FormData();
            formData.append('image',$(obj)[0].files[0]);
            $.ajax({
                url:"{{ route('image.preview') }}",
                data:formData,
                dataType:'json',
                cache: false,  
                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
                processData: false,//必须false才会自动加上正确的Content-Type
                type:'post',            
                success:function(data){
                   var html = "<div><img src='" + data.preview + "'></div>";
                   $(".pictures[code='" + which_click + "']").append(html);
                },error:function(e){
                    console.log(e);
                }
            });
        }
    </script>
@endsection
