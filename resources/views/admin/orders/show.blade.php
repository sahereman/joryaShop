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
                <td>{{ $order->user_name }}</td>
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
                    <td><a href="{{route('admin.products.show',$item->sku->product->id)}}">{{ $item->sku->product->name_en }}</a></td>
                    <td>{{ $item->sku->base_size_en }}
                        | {{ $item->sku->hair_colour_en }}
                        | {{ $item->sku->hair_density_en }}
                    </td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->number }}</td>
                </tr>
            @endforeach
            {{--<tr>
                <td>运费：</td>
                <td>{{ $order->total_shipping_fee }}</td>
                <td>金额：</td>
                <td colspan="2">{{ $order->total_amount }}</td>
            </tr>--}}

            @if ($order->status === \App\Models\Order::ORDER_STATUS_PAYING)
                <tr>
                    <td colspan="5">
                        <form action="{{ route('admin.orders.modify', ['order' => $order->id]) }}" method="post" class="form-inline" style="padding: 18px 0">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group {{ $errors->has('total_shipping_fee') ? 'has-error' : '' }}" style="margin-right: 20px;">
                                <label for="total_shipping_fee" style="margin-left: 20px;">运费：</label>
                                <input type="text" name="total_shipping_fee" id="total_shipping_fee" value="{{ $order->total_shipping_fee }}">
                                <span style="margin-left: 20px;">金额：</span>
                                <span>{{ $order->total_amount }}</span>
                                @if($errors->has('total_shipping_fee'))
                                    @foreach($errors->get('total_shipping_fee') as $msg)
                                        <span class="help-block">{{ $msg }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success" id="ship-btn" style="margin-left: 20px;">确认修改</button>
                        </form>
                    </td>
                </tr>
            @elseif ($order->status === \App\Models\Order::ORDER_STATUS_SHIPPING)
                <!-- 订单发货开始 -->
                <!-- 如果订单未发货，展示发货表单 -->
                <tr>
                    <td>运费：</td>
                    <td>{{ $order->total_shipping_fee }}</td>
                    <td>金额：</td>
                    <td colspan="2">{{ $order->total_amount }}</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <form action="{{ route('admin.orders.ship', ['order' => $order->id]) }}" method="post" class="form-inline" style="padding: 18px 0">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group {{ $errors->has('shipment_company') ? 'has-error' : '' }}" style="margin-right: 20px;">
                                <label for="shipment_company" class="control-label">物流公司 : </label>
                                <select style="width: 220px" class="form-control" name="shipment_company">
                                    <option value="">输入物流公司</option>
                                    @foreach(\App\Models\ShipmentCompany::shipmentCompanies() as $company)
                                        <option value="{{ $company->code }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('shipment_company'))
                                    @foreach($errors->get('shipment_company') as $msg)
                                        <span class="help-block">{{ $msg }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('shipment_sn') ? 'has-error' : '' }}" style="margin-right: 20px;">
                                <label for="shipment_sn" class="control-label">物流单号 : </label>
                                <input style="width: 220px" type="text" name="shipment_sn" value="" class="form-control" placeholder="输入物流单号">
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
            @elseif (!in_array($order->status, [\App\Models\Order::ORDER_STATUS_CLOSED, \App\Models\Order::ORDER_STATUS_PAYING]))
                <!-- 否则展示物流公司和物流单号 -->
                <tr>
                    <td>运费：</td>
                    <td>{{ $order->total_shipping_fee }}</td>
                    <td>金额：</td>
                    <td colspan="2">{{ $order->total_amount }}</td>
                </tr>
                <tr>
                    <td>物流公司：</td>
                    <td>{{ \App\Models\ShipmentCompany::codeTransformName($order->shipment_company) . " ($order->shipment_company)" }}</td>
                    <td>物流单号：</td>
                    <td>{{ $order->shipment_sn }}</td>
                </tr>
            @endif
            <!-- 订单发货结束 -->

            </tbody>
        </table>
    </div>
</div>
