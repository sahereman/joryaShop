<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">订单流水号：{{ $order->order_sn }}</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 订单列表</a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td>买家：</td>
                <td>{{ $order->user->name }}</td>
                <td>订单状态：</td>
                <td>{{ \App\Models\Order::$orderStatusMap[$order->status]}}</td>
            </tr>
            <tr>
                <td>支付币种：</td>
                <td>{{$order->currency}}</td>
                <td>支付方式：</td>
                <td>{{ \App\Models\Order::$paymentMethodMap[$order->payment_method] ?? '无'  }}</td>
            </tr>
            <tr>
                <td>支付渠道单号：</td>
                <td>{{ $order->payment_sn }}</td>
            </tr>

            <tr>
                <td rowspan="7">时间信息</td>
                <td>时间</td>
                <td>操作</td>
            </tr>
            <tr>
                <td>{{ $order->closed_at or '' }}</td>
                <td>交易关闭</td>
            </tr>
            <tr>
                <td>{{$order->created_at}}</td>
                <td>下单</td>
            </tr>
            <tr>
                <td>{{ $order->paid_at or '' }}</td>
                <td>支付</td>
            </tr>
            <tr>
                <td>{{ $order->shipped_at or '' }}</td>
                <td>卖家发货</td>
            </tr>
            <tr>
                <td>{{ $order->completed_at or '' }}</td>
                <td>买家收货</td>
            </tr>
            <tr>
                <td>{{ $order->commented_at or '' }}</td>
                <td>买家评价</td>
            </tr>

            <tr>
                <td>收货地址</td>
                <td colspan="4">地址: {{ $order->user_info['address'] }} 联系人: {{ $order->user_info['name'] }} 联系方式: {{ $order->user_info['phone'] }}</td>
            </tr>
            <tr>
                <td rowspan="{{ $order->items->count() + 1 }}">商品列表</td>
                <td>商品名称</td>
                <td>属性规格</td>
                <td>单价</td>
                <td>数量</td>
            </tr>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->sku->product->name_zh }}</td>
                    <td>{{ $item->sku->name_zh }} </td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->number }}</td>
                </tr>
            @endforeach
            <tr>
                <td>运费：</td>
                <td>{{ $order->total_shipping_fee }}</td>
                <td>金额：</td>
                <td colspan="2">{{ $order->total_amount }}</td>
            </tr>

            @if (!in_array($order->status,[\App\Models\Order::ORDER_STATUS_CLOSED,\App\Models\Order::ORDER_STATUS_PAYING,\App\Models\Order::ORDER_STATUS_SHIPPING]))
                <tr>
                    <td>物流公司：</td>
                    <td>{{\App\Models\ShipmentCompany::codeTransformName($order->shipment_company) . " ($order->shipment_company)"}}</td>
                    <td>物流单号：</td>
                    <td>{{ $order->shipment_sn }}</td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
</div>


<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">售后流水号：{{ $refund->refund_sn }}</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="{{ route('admin.order_refunds.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 售后列表</a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td>类型：</td>
                <td>{{ \App\Models\OrderRefund::$orderRefundTypeMap[$refund->type] }}</td>
                <td>状态：</td>
                <td>{{ \App\Models\OrderRefund::$orderRefundStatusMap[$refund->status] }}</td>
            </tr>
            <tr>
                <td>买家申请退款说明：</td>
                <td colspan="3">{{$refund->remark_from_user}}</td>
            </tr>
            @if($refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)
                <tr>
                    <td>买家申请退款图片：</td>
                    <td colspan="3">
                        @foreach($refund->refund_photo_urls as $img)
                            <img src="{{$img}}" style="width: 240px; height: 240px;margin-right: 12px;">
                        @endforeach
                    </td>
                </tr>
            @endif
            @if($refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)

                @if($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING)
                    <tr>
                        <td>买家物流公司：</td>
                        <td colspan="3">{{ $refund->shipment_company }}</td>
                    </tr>
                    <tr>
                        <td>买家物流单号：</td>
                        <td colspan="3">{{ $refund->shipment_sn }}</td>
                    </tr>
                    <tr>
                        <td>买家物流备注：</td>
                        <td colspan="3">{{ $refund->remark_for_shipment_from_user }}</td>
                    </tr>

                    <tr>
                        <td>买家物流图片：</td>
                        <td colspan="3">
                            @foreach($refund->shipment_photo_urls as $img)
                                <img src="{{$img}}" style="width: 240px; height: 240px;margin-right: 12px;">
                            @endforeach
                        </td>
                    </tr>
                @endif
            @endif

            <tr>
                <td rowspan="5">时间信息</td>
                <td>时间</td>
                <td>操作</td>
            </tr>
            <tr>
                <td>{{ $refund->created_at or '' }}</td>
                <td>发起退款申请</td>
            </tr>
            <tr>
                <td>{{ $refund->checked_at or ''}}</td>
                <td>卖家审核通过</td>
            </tr>
            <tr>
                <td>{{ $refund->shipped_at or '' }}</td>
                <td>买家发货</td>
            </tr>
            <tr>
                <td>{{ $refund->refunded_at or '' }}</td>
                <td>卖家同意退款</td>
            </tr>

            @if($refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)

                @if($refund->status != \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                    <tr>
                        <td>卖家收货地址：</td>
                        <td colspan="3">
                            联系人: {{$refund->seller_info['name']}}
                            联系方式: {{$refund->seller_info['phone']}}
                            收货地址: {{$refund->seller_info['address']}}
                        </td>
                    </tr>
                @endif
            @endif


            <tr>
                <td colspan="5" style="padding: 30px ">

                    @if($refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND)
                        @if($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                            <button class="btn btn-success" id="check-refund">审核并退款</button>
                        @endif
                    @elseif($refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)
                        @if($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                            <div>
                                <label for="shipment_sn" class="control-label">退货收货人　: </label>
                                <input id="refund-name" style="width: 220px" type="text" value="" placeholder="输入退货收货人">
                            </div>

                            <div>
                                <label for="shipment_sn" class="control-label">退货联系方式: </label>
                                <input id="refund-phone" style="width: 220px" type="text" value="" placeholder="输入退货联系方式">
                            </div>

                            <div>
                                <label for="shipment_sn" class="control-label">退货收货地址: </label>
                                <input id="refund-address" style="width: 220px; margin-bottom: 20px" type="text" value="" placeholder="输入退货收货地址">
                            </div>

                            <button class="btn btn-success" id="check-refund_with_shipment">审核并提醒买家发货</button>
                        @elseif($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING)
                            <h3><span class="label label-info">等待买家发货...</span></h3>
                        @elseif($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING)
                            <button class="btn btn-success" id="receive-refund_with_shipment">已验货并退款</button>
                        @endif
                    @endif

                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    var network_switch = true;
    //仅退款 审核通过
    $('#check-refund').click(function () {
        swal({
            title: '确认要将款项退还给用户？',
            type: 'warning',
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "确认",
            cancelButtonText: "取消",
        }, function (ret, aa) {

            // 用户点击取消，不做任何操作
            if (!ret || !network_switch) {
                return;
            }
            network_switch = false;

            $.ajax({
                url: '{{ route('admin.order_refunds.check', [$refund->id]) }}',
                method: 'POST',
                data: {
                    '_token': LA.token,
                },
                dataType: "json",   //返回格式为json
                success: function (data) {
                    swal(data.messages, '', 'success');

                    $.pjax.reload('#pjax-container');
                },
                error: function (data) {
                    if (data.status == 422) {
                        swal(data.responseJSON.exception.message, '', 'error');
                    }
                    else {
                        swal('系统内部错误', '', 'error');
                    }

                },
                complete: function (xhr, status) {
                    //请求完成的处理
                    network_switch = true;
                }
            });
        });
    });


    //退货并退款 审核通过
    $('#check-refund_with_shipment').click(function () {
        swal({
            title: '确认同意退货吗？',
            type: 'warning',
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "确认",
            cancelButtonText: "取消",
        }, function (ret) {
            // 用户点击取消，不做任何操作
            if (!ret || !network_switch) {
                return;
            }
            network_switch = false;
            $.ajax({
                url: '{{ route('admin.order_refunds.check', [$refund->id]) }}',
                method: 'POST',
                data: {
                    '_token': LA.token,
                    'name': $('#refund-name').val(),
                    'phone': $('#refund-phone').val(),
                    'address': $('#refund-address').val()
                },
                dataType: "json",   //返回格式为json
                success: function (data) {
                    swal(data.messages, '', 'success');

                    $.pjax.reload('#pjax-container');
                },
                error: function (data) {
                    if (data.status == 422) {
                        swal(data.responseJSON.exception.message, '', 'error');
                    }
                    else {
                        swal('系统内部错误', '', 'error');
                    }

                },
                complete: function (xhr, status) {
                    //请求完成的处理
                    network_switch = true;
                }
            });
        });
    });


    //退货并退款 已收到货
    $('#receive-refund_with_shipment').click(function () {
        swal({
            title: '确认同意退货吗？',
            type: 'warning',
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "确认",
            cancelButtonText: "取消",
        }, function (ret) {
            // 用户点击取消，不做任何操作
            if (!ret || !network_switch) {
                return;
            }
            network_switch = false;
            $.ajax({
                url: '{{ route('admin.order_refunds.receive', [$refund->id]) }}',
                method: 'POST',
                data: {
                    '_token': LA.token,
                },
                dataType: "json",   //返回格式为json
                success: function (data) {
                    swal(data.messages, '', 'success');

                    $.pjax.reload('#pjax-container');
                },
                error: function (data) {
                    if (data.status == 422) {
                        swal(data.responseJSON.exception.message, '', 'error');
                    }
                    else {
                        swal('系统内部错误', '', 'error');
                    }

                },
                complete: function (xhr, status) {
                    //请求完成的处理
                    network_switch = true;
                }
            });
        });
    });

</script>
