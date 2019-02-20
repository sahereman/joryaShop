@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '我的订单' : 'My Orders') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="orderBox">
        <div class="orderHeadTop">
            @if(!is_wechat_browser())
                <div class="headerBar">
                    <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                         onclick="javascript:window.location.href='{{ route('mobile.users.home') }}'"/>
                    <span>@lang('basic.users.My_order')</span>
                </div>
            @endif
            <div class="orderHead">
                <div class="index orderActive"
                     data-url="{{ route('mobile.orders.more') }}">@lang('basic.orders.All orders')</div>
                <div class="pending_payment"
                     data-url="{{ route('mobile.orders.more') . '?status=' . \App\Models\Order::ORDER_STATUS_PAYING }}">@lang('basic.orders.Pending payment')</div>
                <div class="pending_reception"
                     data-url="{{ route('mobile.orders.more') . '?status=' . \App\Models\Order::ORDER_STATUS_RECEIVING }}">@lang('basic.orders.Pending reception')</div>
                <div class="Pending_comment"
                     data-url="{{ route('mobile.orders.more') . '?status=' . \App\Models\Order::ORDER_STATUS_UNCOMMENTED }}">@lang('basic.orders.Pending comment')</div>
                <div class="After_sale_order"
                     data-url="{{ route('mobile.orders.more') . '?status=' . \App\Models\Order::ORDER_STATUS_REFUNDING }}">@lang('basic.orders.After-sale order')</div>
            </div>
        </div>
        <div class="orderMain" code="{{ App::isLocale('zh-CN') ? 'zh' : 'en' }}">
            <!--暂无订单部分-->
            <div class="no_order dis_n">
                <img src="{{ asset('static_m/img/no_order.png') }}">
                <p>@lang('basic.users.No_orders_yet')</p>
                <a href="{{ route('root') }}">@lang('product.shopping_cart.Go_shopping')</a>
            </div>
            <div class="lists {{ is_wechat_browser() ? 'small_margin' : '' }}">
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript" src="{{ asset('static_m/js/dropload/dropload.min.js') }}"></script>
    <script type="text/javascript">
        // 页面单独JS写这里
        $(".orderHead div").on("click", function (e) {
            $(".orderHead div").removeClass("orderActive");
            $(this).addClass("orderActive");
            $(".no_order").addClass("dis_n");
            $(".orderMain .lists").removeClass("dis_n");
            $(".dropload-down").remove();
            $(".lists").children().remove();
            getResults();
        });
        /*$(".orderItemDetail").on("click", function () {
         window.location.href = $(this).attr('data-url');
         });*/
        // 获取url参数
        var action = "";
        function getUrlVars() {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars["status"];
        }
        window.onload = function () {
            if (getUrlVars() != undefined) {
                action = getUrlVars();
            }
            switch (action) {
                case "paying": // 待付款
                    $(".orderHead .pending_payment").trigger("click");
                    break;
                case "receiving": // 待收货
                    $(".orderHead .pending_reception").trigger("click");
                    break;
                case "uncommented": // 待评价
                    $(".orderHead .Pending_comment").trigger("click");
                    break;
                case "refunding": // 售后订单
                    $(".orderHead .After_sale_order").trigger("click");
                    break;
                default : // 所有订单
                    $(".orderHead .index").trigger("click");
                    // getResults();
                    break;
            }
        };
        // 获取订单列表列表
        function getResults() {
            // 页数
            var page = 1;
            var order_refund_status_finished = [
                "{{ \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED }}",
                "{{ \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED }}",
            ];
            $('.orderMain').dropload({
                scrollArea: window,
                domDown: { // 下方DOM
                    domClass: 'dropload-down',
                    domRefresh: "<div class='dropload-refresh'>↑@lang('product.product_details.Pull up load more')</div>",
                    domLoad: "<div class='dropload-load'><span class='loading'></span>@lang('product.product_details.Loading in')...</div>",
                    domNoData: "<div class='dropload-noData'>@lang('product.product_details.over the end')</div>",
                },
                loadDownFn: function (me) {
                    // 拼接HTML
                    var html = '';
                    var data = {
                        page: page,
                    };
                    $.ajax({
                        type: "get",
                        url: $(".orderHead").find(".orderActive").attr("data-url"),
                        data: data,
                        dataType: 'json',
                        success: function (data) {
                            var orders = data.data.orders.data;
                            var html = "";
                            // var name, sum, symbol, price, sku_name, total_price, total_price1, total_price2;
                            var name, sum, symbol, price, sku_parameters, total_price, total_price1, total_price2;
                            if (orders.length > 0) {
                                $(".no_order").addClass("dis_n");
                                $(".orderMain .lists").removeClass("dis_n");
                                $.each(orders, function (n, order) {
                                    sum = ($(".orderMain").attr("code") == "en") ? "Sum" : "实付款";
                                    // symbol = (order.currency == "USD") ? '&#36;' : '&#165;';
                                    symbol = get_symbol_by_currency(order.currency);
                                    html += "<div class='orderItem'>";
                                    html += "<div class='orderItemH'>";
                                    html += "<span class='order_info' code='" + order.id + "'>@lang('basic.users.Order_number')： " + order.order_sn + "</span>";
                                    // html += "<span class='order_info' code='" + order.id + "'>@lang('basic.users.The_order_details') >></span>";
                                    switch (order.status) {
                                        case "paying":
                                            html += "<span class='orderItemState'>@lang('basic.orders.Pending payment')</span>";
                                            break;
                                        case "closed":
                                            html += "<span class='orderItemState'>@lang('basic.orders.Closed')</span>";
                                            break;
                                        case "shipping":
                                            html += "<span class='orderItemState'>@lang('basic.orders.Pending shipment')</span>";
                                            break;
                                        case "receiving":
                                            html += "<span class='orderItemState'>@lang('basic.orders.Pending reception')</span>";
                                            break;
                                        case "completed":
                                            if (order.commented_at == null) {
                                                html += "<span class='orderItemState'>@lang('basic.orders.Pending comment')</span>";
                                            } else {
                                                html += "<span class='orderItemState'>@lang('basic.orders.Completed')</span>";
                                            }
                                            break;
                                        case "refunding":
                                            html += "<span class='orderItemState'>@lang('basic.orders.After-sale order')</span>";
                                            break;
                                    }
                                    html += "</div>";
                                    html += "<div class='orderItemDetail' data-url=" + "{{ config('app.url') }}" + "'/mobile/orders/" + order.id + "'>";
                                    if (order.snapshot.length > 0) {
                                        $.each(order.snapshot, function (i, order_item) {
                                            name = ($(".orderMain").attr("code") == "en") ? order_item.sku.product.name_en : order_item.sku.product.name_zh;
                                            // sku_name = ($(".orderMain").attr("code") == "en") ? order_item.sku.name_en : order_item.sku.name_zh;
                                            sku_parameters = ($(".orderMain").attr("code") == "en") ? order_item.sku.parameters_en : order_item.sku.parameters_zh;
                                            // price = (order.currency == "CNY") ? order_item.sku.product.price : order_item.sku.product.price_in_usd;
                                            price = exchange_price(order_item.sku.product.price, order.currency);
                                            total_price1 = float_multiply_by_100(order.total_amount) + float_multiply_by_100(order.total_shipping_fee);
                                            total_price = js_number_format(total_price1 / 100);
                                            html += "<div class='orderItemDetail_item'>";
                                            html += "<a class='product_info' code='" + order.id + "'>";
                                            html += "<img src='" + order_item.sku.product.thumb_url + "'/>";
                                            html += "</a>";
                                            html += "<div class='orderDal' code='" + order.id + "'>";
                                            html += "<div class='orderIntroduce'>";
                                            html += "<div class='goodsName'>" + name + "</div>";
                                            // html += "<div class='goodsSku'>" + sku_name + "</div>";
                                            html += "<div class='goodsSku'>" + sku_parameters + "</div>";
                                            html += "</div>";
                                            html += "<div class='orderPrice'>";
                                            html += "<div>" + price + "</div>";
                                            html += "<div class='orderItemNum'>&#215; " + order_item.number + "</div>";
                                            html += "</div>";
                                            html += "</div>";
                                            html += "</div>";
                                        });
                                    }
                                    html += "</div>";
                                    html += "<div class='orderItemTotle'>";

                                    /*if ($(".orderMain").attr("code") == "zh") {
                                     html += "<span>共" + order.snapshot.length + "件商品</span>";
                                     } else {
                                     if (order.snapshot.length == 1) {
                                     html += "<span>" + order.snapshot.length + " commodity {{--@lang('basic.orders.in total')--}}</span>";
                                     } else {
                                     html += "<span>" + order.snapshot.length + " commodities {{--@lang('basic.orders.in total')--}}</span>";
                                     }
                                     }*/

                                    html += " <span class='orderCen'>" + sum + ": </span>";
                                    html += "<span>" + symbol + " " + total_price + "</span>";
                                    // js_number_format(Math.imul(float_multiply_by_100(price), 12) / 1000)
                                    html += "<span>(@lang('order.Postage included'))</span>";
                                    html += "</div>";
                                    html += " <div class='orderBtns'>";
                                    switch (order.status) {
                                        case "paying":
                                            html += "<button class='orderBtnC cancel' code='" + order.id + "'>@lang('app.cancel')</button>";
                                            html += "<button class='orderBtnS payment' code='" + order.id + "'>@lang('order.Immediate payment')</button>";
                                            break;
                                        case "closed":
                                            html += "<button class='orderBtnC Delete' code='" + order.id + "'>@lang('order.Delete order')</button>";
                                            break;
                                        case "shipping":
                                            html += "<button class='orderBtnC refund' code='" + order.id + "'> @lang('order.Request a refund')</button>";
                                            html += "<button class='orderBtnC Remind_shipments' code='" + order.id + "'> @lang('basic.orders.Remind shipments')</button>";
                                            break;
                                        case "receiving":
                                            // html += "<button class='orderBtnC refund_with_ship' code='" + order.id + "'> @lang('order.Request a refund')</button>";
                                            html += "<button class='orderBtnC shipment_details' code='" + order.id + "'> @lang('order.View shipment details')</button>";
                                            html += "<button class='orderBtnS Confirm_reception' code='" + order.id + "'> @lang('order.Confirm reception')</button>";
                                            break;
                                        case "completed":
                                            if (order.commented_at == null) {
                                                html += "<button class='orderBtnC shipment_details' code='" + order.id + "'> @lang('order.View shipment details')</button>";
                                                html += "<button class='orderBtnS To_comment' code='" + order.id + "'> @lang('order.To comment')</button>";
                                            } else {
                                                html += "<button class='orderBtnC shipment_details' code='" + order.id + "'> @lang('order.View shipment details')</button>";
                                                html += "<button class='orderBtnS View_comments' code='" + order.id + "'> @lang('order.View comments')</button>";
                                                html += "<button class='orderBtnS Delete' code='" + order.id + "'> @lang('order.Delete order')</button>";
                                            }
                                            break;
                                        case "refunding":
                                            if (order.shipped_at == null) {
                                                html += "<button class='orderBtnC after_sales_status' code='" + order.id + "'> @lang('order.View after sales status')</button>";
                                                // html += "<button class='orderBtnS Revoke_refund' code='" + order.id + "'> @lang('order.Revoke the refund application')</button>";
                                            } else {
                                                html += "<button class='orderBtnC after_sales_status_ship' code='" + order.id + "'> @lang('order.View after sales status')</button>";
                                                // html += "<button class='orderBtnS Revoke_refund' code='" + order.id + "'> @lang('order.Revoke the refund application')</button>";
                                            }
                                            if (order.refund != null && order.refund.status != null && order_refund_status_finished.indexOf(order.refund.status) == -1) {
                                                html += "<button class='orderBtnS Revoke_refund' code='" + order.id + "'> @lang('order.Revoke the refund application')</button>";
                                            }
                                            break;
                                    }
                                    html += "</div>";
                                    html += "</div>";
                                });
                                // 如果没有数据
                            } else {
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                                if (page == 1) {
                                    $(".no_order").removeClass("dis_n");
                                    $(".orderMain .lists").addClass("dis_n");
                                    $(".dropload-down").remove();
                                }
                            }
                            $(".orderMain .lists").append(html);
                            page++;
                            // 每次数据插入，必须重置
                            me.resetload();
                        },
                        error: function (xhr, type) {
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                }
            });
        }
        // 点击查看订单详情
        $(".orderMain .lists").on("click", ".order_info", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code");
        });
        // 点击查看订单详情
        $(".orderMain .lists").on("click", ".product_info", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code");
        });
        // 点击查看订单详情
        $(".orderMain .lists").on("click", ".orderDal", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code");
        });
        // 付款按钮
        $(".orderMain .lists").on("click", ".payment", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/payment_method";
        });
        // 取消订单
        $(".orderMain .lists").on("click", ".cancel", function () {
            var clickDom = $(this);
            layer.open({
                content: "@lang('basic.orders.Make sure to cancel the order')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = "{{ config('app.url') }}" + "/orders/" + clickDom.attr('code') + "/close";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            $(clickDom.parents(".orderItem")).remove();
                            window.location.reload();
                            layer.open({
                                content: "@lang('order.Order cancelled successfully')",
                                skin: 'msg',
                                time: 2, // 2秒后自动关闭
                            });
                        },
                        error: function (err) {
                            console.log(err.status);
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, // 2秒后自动关闭
                                });
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });
        // 删除按钮
        $(".orderMain .lists").on("click", ".Delete", function () {
            var clickDom = $(this);
            layer.open({
                content: "@lang('order.Make sure to delete the order information')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "DELETE",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = "{{ config('app.url') }}" + "/orders/" + clickDom.attr('code');
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (json) {
                            $(clickDom.parents(".orderItem")).remove();
                            layer.open({
                                content: "@lang('order.Order deleted successfully')",
                                skin: 'msg',
                                time: 2, // 2秒后自动关闭
                            });
                            window.location.reload();
                        },
                        error: function (err) {
                            console.log(err.status);
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, // 2秒后自动关闭
                                });
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });
        // 提醒发货  Remind_shipments
        $(".orderMain .lists").on("click", ".Remind_shipments", function () {
            layer.open({
                content: "@lang('basic.orders.The seller has been reminded to ship the goods, please wait for good news')",
                skin: 'msg',
                time: 2, // 2秒后自动关闭
            });
        });
        // 申请退款
        $(".orderMain .lists").on("click", ".refund", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/refund";
        });
        // 申请退款并退货
        $(".orderMain .lists").on("click", ".refund_with_ship", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/refund_with_shipment";
        });
        // 确认收货
        $(".orderMain .lists").on("click", ".Confirm_reception", function () {
            var clickDom = $(this);
            layer.open({
                content: "@lang('order.Are you sure you want to confirm the receipt')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = "{{ config('app.url') }}" + "/orders/" + clickDom.attr('code') + "/complete";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            $(".orderHead .pending_reception").trigger("click");
                            layer.open({
                                content: "@lang('order.Confirm receipt success')",
                                skin: 'msg',
                                time: 2, // 2秒后自动关闭
                            });
                        },
                        error: function (err) {
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, // 2秒后自动关闭
                                });
                            }
                        }
                    });
                    layer.close(index);
                }
            });
        });
        // 查看物流信息 shipment_details
        $(".orderMain .lists").on("click", ".shipment_details", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/show_shipment";
        });
        // 去评价   To_comment
        $(".orderMain .lists").on("click", ".To_comment", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/create_comment";
        });
        // 查看评价 View_comments
        $(".orderMain .lists").on("click", ".View_comments", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/show_comment";
        });
        // 查看仅退款售后状态 after_sales_status
        $(".orderMain .lists").on("click", ".after_sales_status", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/refund";
        });
        // 查看退款并退货售后状态 after_sales_status
        $(".orderMain .lists").on("click", ".after_sales_status_ship", function () {
            window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/refund_with_shipment";
        });
        // 撤销售后申请
        $(".orderMain .lists").on("click", ".Revoke_refund", function () {
            var clickDom = $(this);
            // window.location.href = "{{--{{ config('app.url') }}--}}" + "/mobile/orders/" + $(this).attr("code") + "/revoke_refund";
            layer.open({
                content: "@lang('order.Make sure to apply after withdrawing sales')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/revoke_refund";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            $(".orderHead .After_sale_order").trigger("click");
                            layer.open({
                                content: "@lang('order.Cancel the application successfully')",
                                skin: 'msg',
                                time: 2, // 2秒后自动关闭
                            });
                        },
                        error: function (err) {
                            console.log(err);
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, // 2秒后自动关闭
                                });
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });

        // 数字转换
        function float_multiply_by_100(float) {
            float = String(float);
            // float = float.toString();
            var index_of_dec_point = float.indexOf('.');
            if (index_of_dec_point == -1) {
                float += '00';
            } else {
                var float_splitted = float.split('.');
                var dec_length = float_splitted[1].length;
                if (dec_length == 1) {
                    float_splitted[1] += '0';
                } else if (dec_length > 2) {
                    float_splitted[1] = float_splitted[1].substring(0, 1);
                }
                float = float_splitted.join('');
            }
            return Number(float);
        }

        function js_number_format(number) {
            number = String(number);
            // number = number.toString();
            var index_of_dec_point = number.indexOf('.');
            if (index_of_dec_point == -1) {
                number += '.00';
            } else {
                var number_splitted = number.split('.');
                var dec_length = number_splitted[1].length;
                if (dec_length == 1) {
                    number += '0';
                } else if (dec_length > 2) {
                    number_splitted[1] = number_splitted[1].substring(0, 2);
                    number = number_splitted.join('.');
                }
            }
            return number;
        }
    </script>
@endsection
