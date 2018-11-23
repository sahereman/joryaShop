@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Personal Center-my order' : '个人中心-我的订单')
@section('content')
    <div class="User_center my_orders">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
                    <span>></span>
                    <a href="{{ route('orders.show', ['order' => $order->id]) }}">@lang('basic.users.The_order_details')</a>
                    <span>></span>
                    <a href="#">@lang('order.Apply for after sale')</a>
                </p>
            </div>
            <!--申请内容-->
            <div class="refund_content">
                <div class="technological_process">
                    <!--分步骤显示图片一共四张-->
                    @if(! $refund)
                        <!--<img src="{{ asset('img/process-refund-1.png') }}">-->
                    	<div class="first type_2 active">1.@lang('order.Seller applies for refunds only')</div>
				        <div class="second type_2">
				        	2.@lang('order.Seller handles refund Request')
				            <div class="active_2 active"></div>
				        </div>
				        <div class="third type_2">
				        	3.@lang('order.Refunds are complete')
				            <div class="active_2"></div>
				        </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                        <!--<img src="{{ asset('img/process-refund-2.png') }}">-->
                        <div class="first type_2">1.@lang('order.Seller applies for refunds only')</div>
				        <div class="second type_2 active">
				        	2.@lang('order.Seller handles refund Request')
				            <div class="active_2"></div>
				        </div>
				        <div class="third type_2">
				        	3.@lang('order.Refunds are complete')
				            <div class="active_2 active"></div>
				        </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED)
                        <!--<img src="{{ asset('img/process-refund-3.png') }}">-->
                        <div class="first type_2">1.@lang('order.Seller applies for refunds only')</div>
				        <div class="second type_2">
				        	2.@lang('order.Seller handles refund Request')
				            <div class="active_2"></div>
				        </div>
				        <div class="third type_2 active">
				        	3.@lang('order.Refunds are complete')
				            <div class="active_2"></div>
				        </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED)
                        <!--<img src="{{ asset('img/process-refund-4.png') }}">-->
                        <div class="first type_2">1.@lang('order.Seller applies for refunds only')</div>
				        <div class="second type_2">
				        	2.@lang('order.Seller handles refund Request')
				            <div class="active_2"></div>
				        </div>
				        <div class="third type_2 active">
				        	3.@lang('order.Refund failed')
				            <div class="active_2"></div>
				        </div>
                    @endif
                </div>
                <div class="process_content">
                    <!--左侧内容-->
                    <div class="pull-left left_content">
                        @if(! $refund)
                                <!--第一步买家申请退货并退款-->
                        <div class="step_content step-1">
                            <form method="POST" action="{{ route('orders.store_refund', ['order' => $order->id]) }}"
                                  enctype="multipart/form-data" id="step-1-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <ul class="step-1-ul step-ul">
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Refund amount')：</span>
                                        <input name="amount" type="text" class="refund_amount"
                                               value="{{ $order->total_amount }}"
                                               readonly>
                                    </li>
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Application description')：</span>
                                        <textarea name="remark_by_user" class="reasons_for_refunds"
                                                  placeholder="@lang('order.Please fill in the reason for the refund')"></textarea>
                                        <span class="remainder">200</span>
                                    </li>
                                </ul>
                            </form>
                            <p class="btn_submit_area">
                                <a class="step-1-submit step-submit">@lang('app.submit')</a>
                            </p>
                        </div>
                        @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                                <!--第二步卖家处理退货申请-->
                        <div class="step_content step-2">
                            <form method="POST" action="{{ route('orders.update_refund', ['order' => $order->id]) }}"
                                  enctype="multipart/form-data" id="step-2-form">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <ul class="step-1-ul step-ul">
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Refund amount')：</span>
                                        <input name="amount" type="text" class="refund_amount no_border"
                                               value="¥ {{ $order->total_amount }}" readonly>
                                    </li>
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Application description')：</span>
                                        <textarea name="remark_by_user" class="reasons_for_refunds no_border" readonly
                                                  placeholder="@lang('order.Please fill in the reason for the refund')">{{ $refund->remark_by_user }}</textarea>
                                        <span class="remainder hidden">200</span>
                                    </li>
                                </ul>
                            </form>
                            <p class="btn_submit_area">
                                <a class="step-2-submit-1 step-submit">@lang('order.Modify')</a>
                                <a class="step-2-submit-2 step-submit dis_ni">@lang('order.Save changes')</a>
                                <a class="step-2-submit-3 normal-submit"
                                   code="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">@lang('order.Revocation of application')</a>
                            </p>
                        </div>
                        @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED)
                                <!--第三步退款成功-->
                        <div class="step_content step-3">
                            <div class="read_info last_level">
                                <p class="read_info_title">
                                   @lang('order.Audit passed, successful refund')
                                    <span>
                                        @lang('order.Refunds were successful'), {{ $order->total_amount }}@lang('order.It has been returned according to the original hit path')
                                    </span>
                                </p>
                                <ul class="step-ul">
                                    <li>
                                        <span>@lang('order.Refund amount')：</span>
                                        <span class="amount_num">{{ ($order->currency == 'USD') ? '&#36;' : '&yen;' }} {{ $order->total_amount }}</span>
                                    </li>
                                    <li>
                                        <span>@lang('order.Application description')：</span>
                                        <p>{{ $refund->remark_by_user }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED)
                                <!--第四步退款失败-->
                        <div class="step_content step-4">
                            <div class="read_info last_level">
                                <p class="read_info_title">
                                    @lang('order.Audit not passed')
                                    <span>
                                        @lang('order.You can contact online customer service')
                                    </span>
                                </p>
                                <ul class="step-ul">
                                    <li>
                                        <span>@lang('order.Refund amount')：</span>
                                        <span class="amount_num">{{ ($order->currency == 'USD') ? '&#36;' : '&yen;' }} {{ $order->total_amount }}</span>
                                    </li>
                                    <li>
                                        <span>@lang('order.Application description')：</span>
                                        <p>{{ $refund->remark_by_user }}</p>
                                    </li>
                                    <li class="red">
                                        <span>@lang('order.Seller reply')：</span>
                                        <p>{{ $refund->remark_by_seller }}</p>
                                    </li>
                                </ul>
                                <p class="btn_submit_area">
                                    <a class="step-5-submit-1 step-submit">@lang('app.determine')</a>
                                    <a class="step-5-submit-2 normal-submit"
                                       code="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">@lang('order.Revocation of application')</a>
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!--右侧订单信息-->
                    <div class="pull-left order_lists">
                        <p class="step_content_title">@lang('order.Order Info')</p>
                        <ul>
                            @foreach($snapshot as $order_item)
                                <li>
                                    <a href="{{ route('products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                        <div class="info_img">
                                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                        </div>
                                        <div class="order_lists_info">
                                            <p><span>{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</span></p>
                                            <p>{{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}</p>
                                            <p>@lang('order.Unit Price')：{{ ($order->currency == 'USD') ? '&#36;' : '&yen;' }} {{ $order_item['price'] }} &#215; {{ $order_item['number'] }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            <li class="order_lists_total">
                                <p>
                                    <span>@lang('order.Order time')：</span>
                                    <span>{{ $order->created_at }}</span>
                                </p>
                                <p>
                                    <span>@lang('order.Order number')：</span>
                                    <span>{{ $order->order_sn }}</span>
                                </p>
                                <p>
                                    <span>@lang('order.Postage')：</span>
                                    <span><i>{{ ($order->currency == 'USD') ? '&#36;' : '&yen;' }} </i>{{ $order->total_shipping_fee }}</span>
                                </p>
                                <p>
                                    <span>@lang('order.A total of')：</span>
                                    <span><i>{{ ($order->currency == 'USD') ? '&#36;' : '&yen;' }} </i>{{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                        （@lang('order.Postage included')）</span>
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
            $(".order_lists ul").css("height", parseInt(h - 65));
            //上传图片
            $(".refunds_photos").on("click", function () {
                var img_num = $(this).parents('li').find(".refund-path");
                if (img_num.length < 3) {
                    $("#refunds_photos_file").click();
                } else {
                    layer.msg("@lang('order.Upload up to 3 images')");
                }
            });
            $(".refunds_2").on("click", function () {
                var img_num = $(this).parents('li').find(".refund-path");
                if (img_num.length < 3) {
                    $("#refunds_photos_2").click();
                } else {
                    layer.msg("@lang('order.Upload up to 3 images')");
                }
            });
            //图片删除
            $(".del_btn").on('click', function () {
                $(this).parents('.refund-path').remove();
            });
            //第一步提交退款申请
            $(".step-1-submit").on("click", function () {
                set_path("#step-1-form");
                if (set_finish == true) {
                    $("#step-1-form").submit();
                }
            });
            //判断文本域的字数
            $(".reasons_for_refunds").keyup(function () {
                var text = $(this).val();
                //中文字数统计
                str = (text.replace(/\w/g, "")).length;
                //非汉字的个数
                abcnum = text.length - str;
                total = str + abcnum;
                if (total > 200) {
                    $(this).val($(this).val().substring(0, 200));
                    $(".remainder").html('0');
                    layer.msg("@lang('order.The number of words exceeds the upper limit')");
                } else {
                    var num = 200 - total;
                    $(".remainder").html(num);
                }
            });
            $(".reasons_for_refunds").change(function () {
                var text = $(this).val();
                //中文字数统计
                str = (text.replace(/\w/g, "")).length;
                //非汉字的个数
                abcnum = text.length - str;
                total = str + abcnum;
                console.log(total);
                if (total > 200) {
                    $(this).val($(this).val().substring(0, 200));
                    $(".remainder").html('0');
                    layer.msg("@lang('order.The number of words exceeds the upper limit')");
                } else {
                    var num = 200 - total;
                    $(".remainder").html(num);
                }
            });
            //修改申请
            $(".step-2-submit-1").on("click", function () {
                $(".step-2 input").removeClass("no_border");
                $(".step-2 textarea").removeClass("no_border");
                $(".step-2 textarea").prop("readonly", false);
                $(".step-2 .del_btn").removeClass('dis_n');
                $(".refunds_2").removeClass('dis_n');
                $(".remainder").removeClass('hidden');
                $(this).addClass("dis_ni");
                $(".step-2-submit-2").removeClass("dis_ni");
            });
            //提交保存修改
            $(".step-2-submit-2").on("click", function () {
                set_path("#step-2-form");
                if (set_finish == true) {
                    $("#step-2-form").submit();
                }
            });
            //撤销退款申请
            $(".step-2-submit-3").on("click", function () {
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
                                title: "@lang('app.Prompt')",
                                content: "@lang('app.Unable to complete operation')",
                                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                            });
                        }
                    }
                });
            });
            //撤销退款申请
            $(".step-5-submit-2").on("click", function () {
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
                                title: "@lang('app.Prompt')",
                                content: "@lang('app.Unable to complete operation')",
                                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                            });
                        }
                    }
                });
            });
        });
        // 图片上传入口按钮 input[type=file]值发生改变时触发
        function imgChange(obj) {
            var filePath = $(obj).val();
            if (filePath.indexOf("jpg") != -1 || filePath.indexOf("png") != -1 || filePath.indexOf("jpeg") != -1 || filePath.indexOf("gif") != -1 || filePath.indexOf("bmp") != -1) {
                var arr = filePath.split('\\');
                var fileName = arr[arr.length - 1];
                upLoadBtnSwitch = 1;
                UpLoadImg(obj);
            } else {
                layer.open({
				  title: "@lang('app.Prompt')",
				  content: "@lang('app.picture_type_error')",
				  btn: "@lang('app.determine')"
				});     
                upLoadBtnSwitch = 0;
                return false;
            }
        }

        // 本地图片上传 按钮
        function UpLoadImg(obj) {
            var formData = new FormData();
            formData.append('image', $(obj)[0].files[0]);
            $.ajax({
                url: "{{ route('comment_image.upload') }}",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理
                processData: false,//必须false才会自动加上正确的Content-Type
                type: 'post',
                success: function (data) {
                    var html = "<div class='refund-path' data-path='" + data.path + "'>" +
                            "<img src='" + data.preview + "' data-path='" + data.path + "'>" +
                            "<img class='del_btn' src='{{ asset('img/delete_refund_photos.png') }}'/>" +
                            "</div>";
                    $(obj).parents('li').append(html);
                }, error: function (e) {
                    console.log(e);
                }
            });
        }
        function set_path(dom) {
            var order_list = $(dom).find(".refund-path");
            var path_url = "";
            $.each(order_list, function (i, n) {
                path_url += $(n).attr('data-path') + ",";
            });
            path_url = path_url.substring(0, path_url.length - 1);
            $(dom).find("input[name='photos']").val(path_url);
            set_finish = true;
            return set_finish;
        }
    </script>
@endsection
