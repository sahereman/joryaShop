<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">订单流水号：{{ $order->order_sn }}</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 10px">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
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
            <!-- 订单发货开始 -->
            <!-- 如果订单未发货，展示发货表单 -->
            @if($order->status === \App\Models\Order::ORDER_STATUS_SHIPPING)
                <tr>
                    <td colspan="5">
                        <form action="{{ route('admin.orders.ship', [$order->id]) }}" method="post" class="form-inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group {{ $errors->has('shipment_company') ? 'has-error' : '' }}">
                                <label for="shipment_company" class="control-label">物流公司</label>
                                <input type="text" id="shipment_company" name="shipment_company" value="" class="form-control" placeholder="输入物流公司">
                                @if($errors->has('shipment_company'))
                                    @foreach($errors->get('shipment_company') as $msg)
                                        <span class="help-block">{{ $msg }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('shipment_company') ? 'has-error' : '' }}">
                                <label for="shipment_sn" class="control-label">物流单号</label>
                                <input type="text" id="shipment_sn" name="shipment_sn" value="" class="form-control" placeholder="输入物流单号">
                                @if($errors->has('shipment_sn'))
                                    @foreach($errors->get('shipment_sn') as $msg)
                                        <span class="help-block">{{ $msg }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success" id="ship-btn">发货</button>
                        </form>
                    </td>
                </tr>
            @else
                <!-- 否则展示物流公司和物流单号 -->
                <tr>
                    <td>物流公司：</td>
                    <td>{{ $order->shipment_company }}</td>
                    <td>物流单号：</td>
                    <td>{{ $order->shipment_sn }}</td>
                </tr>
            @endif
            <!-- 订单发货结束 -->

            {{--@if($order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)--}}
            {{--<tr>--}}
            {{--<td>退款状态：</td>--}}
            {{--<td colspan="2">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}，理由：{{ $order->extra['refund_reason'] }}</td>--}}
            {{--<td>--}}
            {{--<!-- 如果订单退款状态是已申请，则展示处理按钮 -->--}}
            {{--@if($order->refund_status === \App\Models\Order::REFUND_STATUS_APPLIED)--}}
            {{--<button class="btn btn-sm btn-success" id="btn-refund-agree">同意</button>--}}
            {{--<button class="btn btn-sm btn-danger" id="btn-refund-disagree">不同意</button>--}}
            {{--@endif--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--@endif--}}
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        {{--// 同意按钮的点击事件--}}
        {{--$('#btn-refund-agree').click(function () {--}}
        {{--swal({--}}
        {{--title: '确认要将款项退还给用户？',--}}
        {{--type: 'warning',--}}
        {{--showCancelButton: true,--}}
        {{--closeOnConfirm: false,--}}
        {{--confirmButtonText: "确认",--}}
        {{--cancelButtonText: "取消",--}}
        {{--}, function (ret) {--}}
        {{--// 用户点击取消，不做任何操作--}}
        {{--if (!ret) {--}}
        {{--return;--}}
        {{--}--}}
        {{--$.ajax({--}}
        {{--url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',--}}
        {{--type: 'POST',--}}
        {{--data: JSON.stringify({--}}
        {{--agree: true, // 代表同意退款--}}
        {{--_token: LA.token,--}}
        {{--}),--}}
        {{--contentType: 'application/json',--}}
        {{--success: function (data) {--}}
        {{--swal({--}}
        {{--title: '操作成功',--}}
        {{--type: 'success'--}}
        {{--}, function () {--}}
        {{--location.reload();--}}
        {{--});--}}
        {{--}--}}
        {{--});--}}
        {{--});--}}
        {{--});--}}


        // 不同意 按钮的点击事件
        {{--$('#btn-refund-disagree').click(function () {--}}
        {{--// 注意：Laravel-Admin 的 swal 是 v1 版本，参数和 v2 版本的不太一样--}}
        {{--swal({--}}
        {{--title: '输入拒绝退款理由',--}}
        {{--type: 'input',--}}
        {{--showCancelButton: true,--}}
        {{--closeOnConfirm: false,--}}
        {{--confirmButtonText: "确认",--}}
        {{--cancelButtonText: "取消",--}}
        {{--}, function (inputValue) {--}}
        {{--// 用户点击了取消，inputValue 为 false--}}
        {{--// === 是为了区分用户点击取消还是没有输入--}}
        {{--if (inputValue === false) {--}}
        {{--return;--}}
        {{--}--}}
        {{--if (!inputValue) {--}}
        {{--swal('理由不能为空', '', 'error')--}}
        {{--return;--}}
        {{--}--}}
        {{--// Laravel-Admin 没有 axios，使用 jQuery 的 ajax 方法来请求--}}
        {{--$.ajax({--}}
        {{--url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',--}}
        {{--type: 'POST',--}}
        {{--data: JSON.stringify({   // 将请求变成 JSON 字符串--}}
        {{--agree: false,  // 拒绝申请--}}
        {{--reason: inputValue,--}}
        {{--// 带上 CSRF Token--}}
        {{--// Laravel-Admin 页面里可以通过 LA.token 获得 CSRF Token--}}
        {{--_token: LA.token,--}}
        {{--}),--}}
        {{--contentType: 'application/json',  // 请求的数据格式为 JSON--}}
        {{--success: function (data) {  // 返回成功时会调用这个函数--}}
        {{--swal({--}}
        {{--title: '操作成功',--}}
        {{--type: 'success'--}}
        {{--}, function () {--}}
        {{--// 用户点击 swal 上的 按钮时刷新页面--}}
        {{--location.reload();--}}
        {{--});--}}
        {{--}--}}
        {{--});--}}
        {{--});--}}
        {{--});--}}
    });
</script>