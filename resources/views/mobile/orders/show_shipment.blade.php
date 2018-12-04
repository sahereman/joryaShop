@extends('layouts.mobile')
@section('title', '物流详情')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>物流详情</span>
    </div>
    <div class="logisticsBox">
        <div class="lgtHead">
            <div class="lgtHeadMain">
                <img src="{{ asset('static_m/img/icon_express.png') }}"/>
                <div class="lgtHeadInfo">
                    <div>{{ $shipment_company }}</div>
                    <div class="lgtHeadInfoCode">运单号: {{ $shipment_sn }}</div>
                </div>
            </div>
        </div>
        <div class="lgtCon">
            <div class="lgtConItem">
                <div class="lgtConItemDate"></div>
                <div class="lgtConRight">
                    <img src="{{ asset('static_m/img/icon_Collectgoods.png') }}"/>
                    <div class="lgtConRightMain">
                        <span></span>
                        <span>【收货地址】{{ $order->user_info['address'] }}</span>
                    </div>
                </div>
            </div>
            <!--物流信息根据需要判断是否显示，目前显示的订单状态：待收货、未评价、已评价、退款订单-->
            @if(!empty($order_shipment_traces))
                @foreach($order_shipment_traces as $key => $order_shipment_trace)
                    @if($key == 0)
                        <div class="lgtConItem">
                            <div class="lgtConItemDate">
                                <div>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $order_shipment_trace['AcceptTime'])->format('m-d') }}</div>
                                <div class="lgtConItemDateTime">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $order_shipment_trace['AcceptTime'])->format('H:i') }}</div>
                            </div>
                            <div class="lgtConRight">
                                <img src="{{ asset('static_m/img/icon_Indelivery.png') }}"/>
                                <div class="lgtConRightMain lgtConRightFirst">
                                    <div>运输中</div>
                                    @if(isset($order_shipment_trace['Remark']))
                                        <span>{{ $order_shipment_trace['Remark'] }}</span>
                                        <br>
                                    @endif
                                    <span>{{ $order_shipment_trace['AcceptStation'] }}</span>
                                </div>
                            </div>
                        </div>
                    @elseif($key == (count($order_shipment_traces) - 1))
                        <div class="lgtConItem lgtConItemLast">
                            <div class="lgtConItemDate">
                                <div>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $order_shipment_trace['AcceptTime'])->format('m-d') }}</div>
                                <div class="lgtConItemDateTime">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $order_shipment_trace['AcceptTime'])->format('H:i') }}</div>
                            </div>
                            <div class="lgtConRight">
                                <img src="{{ asset('static_m/img/icon_Alreadyordered.png') }}"/>
                                <div class="lgtConRightMain">
                                    <div>已下单</div>
                                    @if(isset($order_shipment_trace['Remark']))
                                        <span>{{ $order_shipment_trace['Remark'] }}</span>
                                        <br>
                                    @endif
                                    <span>{{ $order_shipment_trace['AcceptStation'] }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="lgtConItem lgtConItemC">
                            <div class="lgtConItemDate">
                                <div>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $order_shipment_trace['AcceptTime'])->format('m-d') }}</div>
                                <div class="lgtConItemDateTime">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $order_shipment_trace['AcceptTime'])->format('H:i') }}</div>
                            </div>
                            <div class="lgtConRight">
                                <div class="dot"></div>
                                <div class="lgtConRightMain">
                                    <div></div>
                                    @if(isset($order_shipment_trace['Remark']))
                                        <span>{{ $order_shipment_trace['Remark'] }}</span>
                                        <br>
                                    @endif
                                    <span>{{ $order_shipment_trace['AcceptStation'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                @if(in_array($order->status, [\App\Models\Order::ORDER_STATUS_RECEIVING, \App\Models\Order::ORDER_STATUS_COMPLETED]))
                    <div class="logistics_infor">
                        <p class="logistics_title">@lang('order.Logistics information')</p>
                        <div class="no_img">
                            <img src="{{ asset('img/no_Logistics.png') }}">
                            <p>@lang('order.No logistics information')</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
