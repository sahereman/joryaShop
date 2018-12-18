@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Request a refund' : '申请退款')
@section('content')
    <div class="headerBar fixHeader">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>@lang('order.Refund request')</span>
        @endif
    </div>
    <div class="refund">
        <div class="refund_con">
            <div class="refund_content">
                <div class="img-box full photoList dis_n" id="imgupup">
                    <div class="input_content up_img clear_fix">
                        <div id="div_imglook">
                            <div id="div_imgfile"></div>
                        </div>
                    </div>
                </div>
                <!--售后状态-->
                <div class="after_sales_status">
                    @if(! $refund)
                            <!--第一步-->
                    <div class="aftersales_status_item">
                        <img src="{{ asset('static_m/img/refund_5.png') }}">
                        <p>
                            <span class="status_title">@lang('order.Request for refund begins')</span>
                        </p>
                    </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                            <!--第2步-->
                    <div class="aftersales_status_item">
                        <img src="{{ asset('static_m/img/refund_2.png') }}">
                        <p>
                            <span class="status_title">@lang('order.Seller handles refund Request')</span>
                        </p>
                    </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING)
                            <!--第3步-->
                    <div class="aftersales_status_item">
                        <img src="{{ asset('static_m/img/refund_3.png') }}">
                        <p>
                            <span class="status_title">@lang('order.Request granted and waiting for customer to ship the goods back')</span>
                        </p>
                    </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING)
                            <!--第4步-->
                    <div class="aftersales_status_item">
                        <img src="{{ asset('static_m/img/refund_6.png') }}">
                        <p>
                            <span class="status_title">@lang('order.Goods shipped back and waiting for seller to receive')</span>
                        </p>
                    </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED)
                            <!--第5步成功-->
                    <div class="aftersales_status_item">
                        <img src="{{ asset('static_m/img/refund_3.png') }}">
                        <p>
                            <span class="status_title">@lang('order.Request for refund terminated')</span>
                            <span>
                                @lang('order.Refund successfully'),
                                {{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                @lang('order.has been refunded by the previous payment method').
                            </span>
                        </p>
                    </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED)
                            <!--第5步失败-->
                    <div class="aftersales_status_item">
                        <img src="{{ asset('static_m/img/refund_4.png') }}">
                        <p>
                            <span class="status_title">@lang('order.Refund failed')</span>
                            <span>@lang('order.You can contact online with our customer service agent')</span>
                        </p>
                    </div>
                    @endif
                </div>
                @if(isset($refund) && $refund->status != \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                        <!--地址信息只在第三步之后显示，1，2不需要-->
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
                    @if(! $refund)
                            <!--第一步-->
                    <form method="POST" enctype="multipart/form-data" id="step-1-form"
                          action="{{ route('orders.store_refund_with_shipment', ['order' => $order->id]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <p>
                            <span>@lang('order.Refund amount')</span>
                            <input name="amount" type="text" class="refund_price" readonly
                                   value="{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">
                        </p>
                        <div class="refund_info_item">
                            <span>@lang('order.Application description')</span>
                            <textarea name="remark_from_user" maxlength="200"
                                      placeholder="@lang('order.Please fill in the reason for the refund')">{{ old('remark_from_user') }}</textarea>
                        </div>
                        <p class="upload_voucher_title">@lang('order.product picture')</p>
                        <div class="refund_info_item upload_voucher">
                            <div class="refunds_photos product_pic" code="{{ $order->id }}">
                                <span class="uploader_camera"></span>
                                <span>@lang('order.No more than 5 sheets')</span>
                            </div>
                            <input type="hidden" name="photos_for_refund">
                        </div>
                    </form>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                            <!--第二步-->
                    <form method="POST" enctype="multipart/form-data" id="step-2-form"
                          action="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <p>
                            <span>@lang('order.Refund amount')</span>
                            <input name="amount" type="text" class="refund_price" readonly
                                   value="{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">
                        </p>
                        <div class="refund_info_item">
                            <span>@lang('order.Application description')</span>
                            <textarea name="remark_from_user" class="step2_textarea" maxlength="200"
                                      readonly>{{ $refund->remark_from_user }}</textarea>
                        </div>
                        <p class="upload_voucher_title">@lang('order.product picture')</p>
                        <div class="refund_info_item upload_voucher">
                            @if($refund->photos_for_refund)
                                @foreach($refund->refund_photo_urls as $refund_photo_url)
                                    <div class='refundItem' data-path='{{ $refund_photo_url }}'>
                                        <img src='{{ $refund_photo_url }}' class='goodsItemPicImg'>
                                        <img src="{{ asset('static_m/img/icon_Closed.png') }}" class='closeImg dis_n'/>
                                    </div>
                                @endforeach
                            @endif
                            <div class="refunds_photos product_pic dis_n" code="{{ $order->id }}">
                                <span class="uploader_camera"></span>
                                <span>@lang('order.No more than 5 sheets')</span>
                            </div>
                            <input type="hidden" name="photos_for_refund" value="">
                        </div>
                    </form>
                    @elseif(isset($refund) && in_array($refund->status, [\App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING, \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING, \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED, \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED]))
                            <!--第三步第四步第五步都是这个-->
                    <p>
                        <span>@lang('order.Refund amount')</span>
                        <input name="amount" type="text" class="refund_price" readonly
                               value="{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">
                    </p>
                    <div class="refund_info_item">
                        <span>@lang('order.Application description')</span>
                        <textarea name="remark_from_user" maxlength="200"
                                  readonly>{{ $refund->remark_from_user }}</textarea>
                    </div>
                    <p class="upload_voucher_title">@lang('order.product picture')</p>
                    <div class="refund_info_item upload_voucher">
                        @if($refund->photos_for_refund)
                            @foreach($refund->refund_photo_urls as $refund_photo_url)
                                <img src="{{ $refund_photo_url }}">
                            @endforeach
                        @endif
                    </div>
                    @endif
                </div>
                @if(isset($refund) && in_array($refund->status, [\App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING, \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING, \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED, \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED]))
                        <!--物流信息从第三步开始显示-->
                <div class="Logistics_info">
                    <form method="POST" enctype="multipart/form-data" id="step-3-form"
                          action="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <ul class="step-ul">
                            @if($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING)
                                <li>
                                    <span>@lang('order.Logistics company')：</span>
                                    <input type="text" name="shipment_company"
                                           placeholder="@lang('order.Please fill in the logistics company')">
                                </li>
                                <li>
                                    <span>@lang('order.shipment number')：</span>
                                    <input type="text" name="shipment_sn"
                                           placeholder="@lang('order.Please fill in the Logistics single number')">
                                </li>
                                <li>
                                    <span>@lang('order.Memo Content')：</span>
                                    <input name="remark_for_shipment_from_user"
                                           placeholder="@lang('order.Please describe in detail the reason for the refund')"
                                           value="{{ old('remark_for_shipment_from_user') }}">
                                </li>
                                <li>
                                    <span>@lang('order.Logistics documents')：</span>
                                    <div class="refunds_photos logistics_pic" code="{{ $order->id }}">
                                        <span class="uploader_camera"></span>
                                        <span>@lang('order.No more than 5 sheets')</span>
                                    </div>
                                    <input type="hidden" name="photos_for_shipment">
                                </li>
                            @else
                                <li>
                                    <span>@lang('order.Logistics company')：</span>
                                    <span>{{ $refund->shipment_company }}</span>
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
                                    @if($refund->photos_for_shipment)
                                        @foreach($refund->shipment_photo_urls as $shipment_photo_url)
                                            <div class='refundItem'>
                                                <img src='{{ $shipment_photo_url }}' class='goodsItemPicImg'>
                                            </div>
                                        @endforeach
                                    @endif
                                </li>
                            @endif
                        </ul>
                    </form>
                </div>
                @endif
                        <!--订单内容-->
                <div class="order_products">
                    @foreach($snapshot as $order_item)
                        <div class="ordDetail_item">
                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                            <div>
                                <div class="ordDetailName">
                                    <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                        {{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}
                                    </a>
                                </div>
                                <div>
                                    <span>
                                        @lang('basic.users.quantity')：{{ $order_item['number'] }}
                                        &nbsp;&nbsp;
                                    </span>
                                    <span>
                                        <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                            {{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}
                                        </a>
                                    </span>
                                </div>
                                <div class="ordDetailPri">
                                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>
                                    <span>{{ $order_item['price'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="order_info">
                        <p>
                            <span>@lang('order.Order time')：</span>
                            <span>{{ $order->created_at }}</span>
                        </p>
                        <p>
                            <span>@lang('order.Order number')：</span>
                            <span>{{ $order->order_sn }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="refund_btns">
                <div>
                    @if(! $refund)
                            <!--第一步显示-->
                    <a href="javascript:void(0);" class="doneBtn submint_one">@lang('app.submit')</a>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                            <!--第二步显示-->
                    <a class="ordDetailBtnC change_btn" href="javascript:void(0);"
                       data-url="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}">
                        @lang('order.Modify')
                    </a>
                    <a class="ordDetailBtnC save_btn dis_ni" href="javascript:void(0);"
                       data-url="{{ route('orders.store_refund', ['order' => $order->id]) }}">
                        @lang('order.Save changes')
                    </a>
                    <a class="ordDetailBtnS Revocation_btn" href="javascript:void(0);"
                       data-url="{{ route('orders.revoke_refund', ['order' => 1]) }}">
                        @lang('order.Revocation of application')
                    </a>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING)
                            <!--第三步显示，第四与第五步没有按钮不需要显示-->
                    <a class="doneBtn logistics_submint" href="javascript:void(0);"
                       data-url="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}">
                        @lang('app.submit')
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            var click_whichDom = "";
            var set_finish = false;
            $(".refund_con").css("min-height", $(window).height() - $(".headerBar ").height());
            //第一步表单提交
            $(".submint_one").on("click", function () {
                if ($("#step-1-form").find("textarea").val() == null || $("#step-1-form").find("textarea").val() == "") {
                    layer.open({
                        content: "@lang('order.Please fill in the application instructions')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return false;
                } else {
                    if ($("#step-1-form").find("textarea").val().length < 3) {
                        layer.open({
                            content: "@lang('product.Evaluation content is not less than 15 words')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        return false;
                    } else if ($("#step-1-form").find("textarea").val().length >= 199) {
                        layer.open({
                            content: "@lang('product.The content of the evaluation should not exceed 200 words')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        return false;
                    }
                }
                set_path("#step-1-form", 'photos_for_refund');
                if (set_finish == true) {
                    $("#step-1-form").submit();
                }
            });
            //第二步修改申请
            $(".change_btn").on("click", function () {
                $(this).addClass("dis_ni");
                $(".save_btn").removeClass("dis_ni");
                $(".refunds_photos").removeClass("dis_n");
                $(".closeImg").removeClass("dis_n");
            });
            //保存修改
            $(".save_btn").on("click", function () {
                set_path("#step-2-form", 'photos_for_refund');
                $(this).addClass("dis_ni");
                $(".change_btn").removeClass("dis_ni");
                $(".refunds_photos").addClass("dis_n");
                if ($("#step-2-form").find("textarea").val() == null || $("#step-2-form").find("textarea").val() == "") {
                    layer.open({
                        content: "@lang('order.Please fill in the application instructions')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return false;
                } else {
                    if ($("#step-2-form").find("textarea").val().length < 3) {
                        layer.open({
                            content: "@lang('product.Evaluation content is not less than 15 words')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        return false;
                    } else if ($("#step-2-form").find("textarea").val().length >= 199) {
                        layer.open({
                            content: "@lang('product.The content of the evaluation should not exceed 200 words')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        return false;
                    }
                }
                if (set_finish == true) {
                    $("#step-2-form").submit();
                }
            });
            //撤销申请
            $(".Revocation_btn").on("click", function () {
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
                                content: "@lang('app.Unable to complete operation')",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                        }
                    }
                });
            });
            //提交物流信息
            $(".logistics_submint").on("click", function () {
                if ($("input[name='shipment_company']").val() == "" || $("input[name='shipment_sn']").val() == "") {
                    layer.open({
                        content: "@lang('order.Please complete the information')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                } else {
                    set_path("#step-3-form", 'photos_for_shipment');
                    if (set_finish == true) {
                        $("#step-3-form").submit();
                    }
                }
            });

            //点击上传退货商品图片与修改相同
            var which_click = 0;
            $(".product_pic").on("click", function () {
                var had_evaImg = $(this).parents(".upload_voucher").find(".refundItem");
                if (had_evaImg.length < 5) {
                    $("#div_imgfile").trigger("click");
                    which_click = $(this).attr("code");
                    click_whichDom = "product_pic";
                } else {
                    layer.open({
                        content: "@lang('product.Upload up to 5 image')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                }
            });
            //上传物流信息
            $(".logistics_pic").on("click", function () {
                var had_evaImg = $(this).parents("li").find(".refundItem");
                if (had_evaImg.length < 5) {
                    $("#div_imgfile").trigger("click");
                    which_click = $(this).attr("code");
                    click_whichDom = "logistics_pic";
                } else {
                    layer.open({
                        content: "@lang('product.Upload up to 5 image')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                }
            });

            //删除图片
            $(document).on("click", ".closeImg", function () {
                var code = $(this).parents(".refundItem").attr("code")
                $(this).parents('.refundItem').remove();
                $("#div_imglook").find(".lookimg[num=" + code + "]").remove();
            });

            // 本地图片上传 按钮
            function UpLoadImg(obj, number) {
                var formData = new FormData();
                formData.append('image', obj);
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: "{{ route('image.upload') }}",
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理
                    processData: false,//必须false才会自动加上正确的Content-Type
                    type: 'post',
                    success: function (data) {
                        var html = "<div class='refundItem' code='" + number + "' data-path='" + data.path + "'>" +
                                "<img src='" + data.preview + "' class='goodsItemPicImg' >" +
                                "<img src='{{ asset('static_m/img/icon_Closed.png') }}' class='closeImg'/>" +
                                "</div>"
                        $("." + click_whichDom + "[code='" + which_click + "']").before(html);
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }

            function set_path(dom, input_name) {
                var order_list = $(dom).find(".refundItem");
                var path_url = "";
                $.each(order_list, function (i, n) {
                    path_url += $(n).attr('data-path') + ",";
                });
                path_url = path_url.substring(0, path_url.length - 1);
                $(dom).find("input[name='" + input_name + "']").val(path_url);
                set_finish = true;
                return set_finish;
            }

            var IMG_LENGTH = 10;//图片最大1MB
            var IMG_MAXCOUNT = 5;//最多选中图片张数
            var UP_IMGCOUNT = 0;//上传图片张数记录
            //打开文件选择对话框
            $("#div_imgfile").click(function () {
                /*if ($(".lookimg").length >= IMG_MAXCOUNT) {
                 layer.open({
                 content: "一次最多上传" + IMG_MAXCOUNT + "张图片",
                 skin: 'msg',
                 time: 2, //2秒后自动关闭
                 });
                 return;
                 }*/
                var sUserAgent = navigator.userAgent.toLowerCase();
                var _CRE_FILE = document.createElement("input");
                if ($(".imgfile").length <= $(".lookimg").length) {//个数不足则新创建对象
                    _CRE_FILE.setAttribute("type", "file");
                    _CRE_FILE.setAttribute("name", "image");
                    _CRE_FILE.setAttribute("class", "imgfile");
                    if (sUserAgent.match(/Android/i) == "android") {
                        _CRE_FILE.setAttribute("capture", "camera");
                    }
//                  _CRE_FILE.setAttribute("capture", "camera");
                    _CRE_FILE.setAttribute("accept", "image/png,image/jpg,image/jpeg");
                    _CRE_FILE.setAttribute("id", "{{ $order_item['id'] }}");
                    _CRE_FILE.setAttribute("data-url", "{{ route('comment_image.upload') }}");
                    _CRE_FILE.setAttribute("num", UP_IMGCOUNT);//记录此对象对应的编号

                    $("#div_imgfile").nextAll().remove();     //上传头像只能传一张照片

                    $("#div_imgfile").after(_CRE_FILE);
                }
                else { //否则获取最后未使用对象
                    _CRE_FILE = $(".imgfile").eq(0).get(0);
                }
                return $(_CRE_FILE).click();//打开对象选择框
            });

            //创建预览图，在动态创建的file元素onchange事件中处理
            $("#imgupup").on("change", ".imgfile", function () {
                if ($(this).val().length > 0) {//判断是否有选中图片
                    //判断图片格式是否正确
                    var FORMAT = $(this).val().substr($(this).val().length - 3, 3);
                    if (FORMAT != "png" && FORMAT != "jpg" && FORMAT != "peg") {
                        layer.open({
                            content: "@lang('basic.users.File format is incorrect')！！！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        return;
                    }

                    //判断图片是否过大，当前设置1MB
                    var file = this.files[0];//获取file文件对象
                    if (file.size > (IMG_LENGTH * 1024 * 1024)) {
                        layer.open({
                            content: "@lang('basic.users.Picture size cannot exceed')" + IMG_LENGTH + "MB",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        $(this).val("");
                        return;
                    }
                    //创建预览外层
                    var _prevdiv = document.createElement("div");
                    _prevdiv.setAttribute("class", "lookimg");
                    //创建内层img对象
                    var preview = document.createElement("img");
                    $(_prevdiv).append(preview);
                    //创建删除按钮
                    var IMG_DELBTN = document.createElement("div");
                    IMG_DELBTN.setAttribute("class", "lookimg_delBtn");
                    //      IMG_DELBTN.innerHTML = "移除";
                    $(_prevdiv).append(IMG_DELBTN);
                    //记录此对象对应编号
                    _prevdiv.setAttribute("num", $(this).attr("num"));
                    //对象注入界面
                    $("#div_imglook").children("div:last").before(_prevdiv);
                    UP_IMGCOUNT++;//编号增长防重复
                    //预览功能 start
                    var reader = new FileReader();//创建读取对象
                    reader.onloadend = function () {
                        preview.src = reader.result;//读取加载，将图片编码绑定到元素
                    };
                    if (file) {//如果对象正确
                        reader.readAsDataURL(file);//获取图片编码
                    } else {
                        preview.src = "";//返回空值
                    }
                    //预览功能 end
                    var number = UP_IMGCOUNT;
                    UpLoadImg(file, number);
                }
            });
        });
    </script>
@endsection
