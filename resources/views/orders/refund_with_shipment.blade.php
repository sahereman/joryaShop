@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '个人中心 - 我的订单' : 'Personal Center - My Orders') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="User_center my_orders">
        <div class="m-wrapper">
            <div class="refun_crumbs">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
                    <span>></span>
                    <a href="{{ route('orders.show', ['order' => $order->id]) }}">@lang('basic.users.The_order_details')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('order.Apply for after sale')</a>
                </p>
            </div>
            <!--申请内容-->
            <div class="refund_content">
                <div class="technological_process">
                    <!--分步骤显示图片一共四张-->
                    @if(! $refund)
                        <div class="first active">
                            1.@lang('order.Request for refund begins')
                        </div>
                        <div class="second">
                            2.@lang('order.Request being handled')
                            <div class="active_2 active"></div>
                        </div>
                        <div class="third">
                            3.@lang('order.Goods being shipped back')
                            <div class="active_2"></div>
                        </div>
                        <div class="fourth">
                            4.@lang('order.Request for refund terminated')
                            <div class="active_2"></div>
                        </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_CHECKING)
                        {{--<img src="{{ asset('img/process-2.png') }}">--}}
                        <div class="first">
                            1.@lang('order.Request for refund begins')
                        </div>
                        <div class="second active">
                            2.@lang('order.Request being handled')
                            <div class="active_2"></div>
                        </div>
                        <div class="third">
                            3.@lang('order.Goods being shipped back')
                            <div class="active_2 active"></div>
                        </div>
                        <div class="fourth">
                            4.@lang('order.Request for refund terminated')
                            <div class="active_2"></div>
                        </div>
                    @elseif(isset($refund) && in_array($refund->status, [\App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING, \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING]))
                        {{--<img src="{{ asset('img/process-3.png') }}">--}}
                        <div class="first">
                            1.@lang('order.Request for refund begins')
                        </div>
                        <div class="second">
                            2.@lang('order.Request being handled')
                            <div class="active_2"></div>
                        </div>
                        <div class="third active">
                            3.@lang('order.Goods being shipped back')
                            <div class="active_2"></div>
                        </div>
                        <div class="fourth">
                            4.@lang('order.Request for refund terminated')
                            <div class="active_2 active"></div>
                        </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED)
                        {{--<img src="{{ asset('img/process-4.png') }}">--}}
                        <div class="first">
                            1.@lang('order.Request for refund begins')
                        </div>
                        <div class="second">
                            2.@lang('order.Request being handled')
                            <div class="active_2"></div>
                        </div>
                        <div class="third">
                            3.@lang('order.Goods being shipped back')
                            <div class="active_2"></div>
                        </div>
                        <div class="fourth active">
                            4.@lang('order.Request for refund terminated')
                            <div class="active_2"></div>
                        </div>
                    @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED)
                        {{--<img src="{{ asset('img/process-5.png') }}">--}}
                        <div class="first">
                            1.@lang('order.Request for refund begins')
                        </div>
                        <div class="second">
                            2.@lang('order.Request being handled')
                            <div class="active_2"></div>
                        </div>
                        <div class="third">
                            3.@lang('order.Goods being shipped back')
                            <div class="active_2"></div>
                        </div>
                        <div class="fourth active">
                            4.@lang('order.Refund failed')
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
                            <form method="POST"
                                  action="{{ route('orders.store_refund_with_shipment', ['order' => $order->id]) }}"
                                  enctype="multipart/form-data" id="step-1-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <ul class="step-1-ul step-ul">
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Refund amount')：</span>
                                        <input name="amount" type="text" class="refund_amount" readonly
                                               {{--value="{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">--}}
                                               value="{{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">
                                    </li>
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Application description')：</span>
                                        <select class="choose_remark" name="">
                                            <option value="default" selected="selected" disabled="disabled">
                                                @lang('order.Please select the refund reason')
                                            </option>
                                            @if($refund_reasons = \App\Models\RefundReason::refundReasons())
                                                @foreach($refund_reasons as $refund_reason)
                                                    <option value="{{ \Illuminate\Support\Facades\App::isLocale('zh-CN') ? $refund_reason->reason_zh : $refund_reason->reason_en }}">
                                                        {{ \Illuminate\Support\Facades\App::isLocale('zh-CN') ? $refund_reason->reason_zh : $refund_reason->reason_en }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            <option value="etc">@lang('order.Etc')</option>
                                        </select>
                                        <textarea name="remark_from_user"
                                                  class="reasons_for_refunds step-1-textarea dis_n"
                                                  placeholder="@lang('order.Please fill in the reason for the refund')">{{ old('remark_from_user') }}</textarea>
                                        <span class="remainder dis_ni">200</span>
                                    </li>
                                    <li>
                                        <span><i class="red">*</i>@lang('order.product picture')：</span>
                                        <div class="refunds_photos">
                                            <span>@lang('order.upload certificate')</span>
                                            <span>（@lang('order.Up to 3 sheets')）</span>
                                        </div>
                                        <input type="file" name="image" value="" id="refunds_photos_file"
                                               onchange="imgChange(this)">
                                        <input type="hidden" name="photos_for_refund">
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
                            <form method="POST" enctype="multipart/form-data" id="step-2-form"
                                  action="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <ul class="step-1-ul step-ul">
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Refund amount')：</span>
                                        <input name="amount" type="text" class="refund_amount no_border" readonly
                                               {{--value="{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">--}}
                                               value="{{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}">
                                    </li>
                                    <li>
                                        <span><i class="red">*</i>@lang('order.Application description')：</span>
                                        <select class="choose_remark dis_n" name="">
                                            <option value="default" selected="selected" disabled="disabled">
                                                @lang('order.Please select the refund reason')
                                            </option>
                                            @if($refund_reasons = \App\Models\RefundReason::refundReasons())
                                                @foreach($refund_reasons as $refund_reason)
                                                    <option value="{{ \Illuminate\Support\Facades\App::isLocale('zh-CN') ? $refund_reason->reason_zh : $refund_reason->reason_en }}">
                                                        {{ \Illuminate\Support\Facades\App::isLocale('zh-CN') ? $refund_reason->reason_zh : $refund_reason->reason_en }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            <option value="etc">@lang('order.Etc')</option>
                                        </select>
                                        <textarea name="remark_from_user" class="reasons_for_refunds marginLeftImpor"
                                                  readonly>{{ $refund->remark_from_user }}</textarea>
                                        <span class="remainder hidden dis_ni">200</span>
                                    </li>
                                    <li>
                                        <span><i class="red">*</i>@lang('order.product picture')：</span>
                                        <div class="refunds_photos dis_n refunds_2">
                                            <span>@lang('order.upload certificate')</span>
                                            <span>（@lang('order.Up to 3 sheets')）</span>
                                        </div>
                                        <input type="file" name="image" value="" id="refunds_photos_2"
                                               onchange="imgChange(this)">
                                        <input type="hidden" name="photos_for_refund" value="">
                                        <!--获取的图片按照下面的格式存放不超过3张-->
                                        @if($refund->photos_for_refund)
                                            @foreach($refund->refund_photo_urls as $refund_photo_url)
                                                <div class='refund-path' data-path='{{ $refund_photo_url }}'>
                                                    <img src="{{ $refund_photo_url }}"
                                                         data-path='{{ $refund_photo_url }}'>
                                                    <img class='del_btn dis_n'
                                                         src="{{ asset('img/delete_refund_photos.png') }}"/>
                                                </div>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            </form>
                            <p class="btn_submit_area">
                                <a class="step-2-submit-1 step-submit">@lang('order.Modify')</a>
                                <a class="step-2-submit-2 step-submit dis_ni">@lang('order.Save changes')</a>
                                <a class="step-2-submit-3 normal-submit"
                                   code="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                                    @lang('order.Revocation of application')
                                </a>
                            </p>
                        </div>
                        @elseif(isset($refund) && in_array($refund->status, [\App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING, \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING]))
                                <!--第三步买家退货-->
                        <div class="step_content step-3">
                            <div class="read_info">
                                <!--需要根据退款的流程来进行判断显示哪一个-->
                                @if($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING)
                                    <p class="read_info_title">
                                        @lang('order.Approved')
                                    </p>
                                @elseif($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING)
                                    <p class="read_info_title">
                                        @lang('order.Waiting for the seller to receive the goods')
                                    </p>
                                @endif
                                <div class="read_address_info">
                                    <p>@lang('order.return address')</p>
                                    <p><span>@lang('order.Consignee')
                                            ：</span><span>{{ $refund->seller_info['name'] }}</span></p>
                                    <p><span>@lang('order.Contact information')
                                            ：</span><span>{{ $refund->seller_info['phone'] }}</span></p>
                                    <p><span>@lang('order.Shipping Address')
                                            ：</span><span>{{ $refund->seller_info['address'] }}</span></p>
                                </div>
                                <ul class="step-ul">
                                    <li>
                                        <span>@lang('order.Refund amount')：</span>
                                        <span class="amount_num">
                                            {{--{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}--}}
                                            {{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                        </span>
                                    </li>
                                    <li>
                                        <span>@lang('order.Application description')：</span>
                                        <p>{{ $refund->remark_from_user }}</p>
                                    </li>
                                    <li>
                                        <span>@lang('order.product picture')：</span>
                                        @if($refund->photos_for_refund)
                                            @foreach($refund->refund_photo_urls as $refund_photo_url)
                                                <div class='refund-path'>
                                                    <img src="{{ $refund_photo_url }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            @if($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_SHIPPING)
                                <form method="POST" enctype="multipart/form-data" id="step-3-form"
                                      action="{{ route('orders.update_refund_with_shipment', ['order' => $order->id]) }}">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <ul class="step-1-ul step-ul">
                                        <li>
                                            <span><i class="red">*</i>@lang('order.Logistics company')：</span>
                                            <input name="shipment_company" type="text" class="refund_company" value="">
                                        </li>
                                        <li>
                                            <span><i class="red">*</i>@lang('order.shipment number')：</span>
                                            <input name="shipment_sn" type="text" class="refund_ numbers" value="">
                                        </li>
                                        <li>
                                            <span><i class="red">*</i>@lang('order.Memo Content')：</span>
                                        <textarea name="remark_for_shipment_from_user" class="remarks_for_refunds"
                                                  placeholder="@lang('order.Please fill in the contents of the remarks')">{{ old('remark_for_shipment_from_user') }}</textarea>
                                        </li>
                                        <li>
                                            <span><i class="red">*</i>@lang('order.Logistics documents')：</span>
                                            <!--这个refunds_3区域和read_info_title设置相同的判断显示隐藏，
                                                当地一条显示的时候下面的区域显示，
                                                第二条显示的时候下面这条隐藏
                                            -->
                                            <div class="refunds_photos refunds_3">
                                                <span>@lang('order.upload certificate')</span>
                                                <span>（@lang('order.Up to 3 sheets')）</span>
                                            </div>
                                            <input type="file" name="image" value="" id="refunds_photos_bill"
                                                   onchange="imgChange(this)">
                                            <input type="hidden" name="photos_for_shipment">
                                        </li>
                                    </ul>
                                </form>
                                <!--这个区域和read_info_title设置相同的判断显示隐藏，
                                    当地一条显示的时候下面的区域显示，
                                    第二条显示的时候下面这条隐藏
                                -->
                                <p class="btn_submit_area">
                                    <a class="step-3-submit step-submit">@lang('app.submit')</a>
                                </p>
                            @elseif($refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_RECEIVING)
                                <div class="read_info last_level">
                                    <ul class="step-ul">
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
                                            <p>{{ $refund->remark_for_shipment_from_user }}</p>
                                        </li>
                                        <li>
                                            <span>@lang('order.Logistics documents')：</span>
                                            @if($refund->photos_for_shipment)
                                                @foreach($refund->shipment_photo_urls as $shipment_photo_url)
                                                    <div class='refund-path'>
                                                        <img src="{{ $shipment_photo_url }}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED)
                                <!--第四步退款成功-->
                        <div class="step_content step-4">
                            <div class="read_info">
                                <p class="read_info_title">
                                    @lang('order.Request granted, and refund successfully')
                                    <span>
                                        @lang('order.Refund successfully'),
                                        {{--{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}--}}
                                        {{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                        @lang('order.has been refunded by the previous payment method').
                                    </span>
                                </p>
                                <div class="read_address_info">
                                    <p>@lang('order.return address')</p>
                                    <p><span>@lang('order.Consignee')
                                            ：</span><span>{{ $refund->seller_info['name'] }}</span></p>
                                    <p><span>@lang('order.Contact information')
                                            ：</span><span>{{ $refund->seller_info['phone'] }}</span></p>
                                    <p><span>@lang('order.Shipping Address')
                                            ：</span><span>{{ $refund->seller_info['address'] }}</span></p>
                                </div>
                                <ul class="step-ul">
                                    <li>
                                        <span>@lang('order.Refund amount')：</span>
                                        <span class="amount_num">
                                            {{--{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}--}}
                                            {{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                        </span>
                                    </li>
                                    <li>
                                        <span>@lang('order.Refund amount')：</span>
                                        <p>{{ $refund->remark_from_user }}</p>
                                    </li>
                                    <li>
                                        <span>@lang('order.product picture')：</span>
                                        @if($refund->photos_for_refund)
                                            @foreach($refund->refund_photo_urls as $refund_photo_url)
                                                <div class='refund-path'>
                                                    <img src="{{ $refund_photo_url }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <div class="read_info last_level">
                                <ul class="step-ul">
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
                                        <p>{{ $refund->remark_for_shipment_from_user }}</p>
                                    </li>
                                    <li>
                                        <span>@lang('order.Logistics documents')：</span>
                                        @foreach($refund->shipment_photo_urls as $shipment_photo_url)
                                            <div class='refund-path'>
                                                <img src="{{ $shipment_photo_url }}">
                                            </div>
                                        @endforeach
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @elseif(isset($refund) && $refund->status == \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED)
                                <!--第五步退款失败-->
                        <div class="step_content step-5">
                            <div class="read_info last_level">
                                <p class="read_info_title">
                                    @lang('order.Request denied')
                                    <span>
                                        @lang('order.You can contact online with our customer service agent')
                                    </span>
                                </p>
                                <ul class="step-ul">
                                    <li>
                                        <span>@lang('order.Refund amount')：</span>
                                        <span class="amount_num">
                                            {{--{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}--}}
                                            {{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                        </span>
                                    </li>
                                    <li>
                                        <span>@lang('order.Refund Instructions')：</span>
                                        <p>{{ $refund->remark_from_user }}</p>
                                    </li>
                                    <li>
                                        <span>@lang('order.product picture')：</span>
                                        @if($refund->photos_for_refund)
                                            @foreach($refund->refund_photo_urls as $refund_photo_url)
                                                <div class='refund-path'>
                                                    <img src="{{ $refund_photo_url }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </li>
                                    <li class="red">
                                        <span>@lang('order.Seller reply')：</span>
                                        <p>{{ $refund->remark_from_seller }}</p>
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
                                    <a href="{{ route('seo_url', $order_item['sku']['product']['slug']) }}">
                                        <div class="info_img">
                                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                        </div>
                                        <div class="order_lists_info">
                                            <p>
                                                <span>{{ App::isLocale('zh-CN') ? $order_item['sku']['product']['name_zh'] : $order_item['sku']['product']['name_en'] }}</span>
                                            </p>
                                            <p>
                                                {{--{{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}--}}
                                                @if($order_item['sku']['product']['type'] == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                                    {{ $order_item['sku']['custom_attr_value_string'] }}
                                                @else
                                                    {{ $order_item['sku']['attr_value_string'] }}
                                                @endif
                                            </p>
                                            <p>
                                                @lang('order.Unit Price')
                                                {{--：{{ $order->currency == "USD" ? '&#36;' : '&#165;' }} {{ $order_item['price'] }}--}}
                                                ：{{ get_symbol_by_currency($order->currency) }} {{ $order_item['price'] }}
                                                &#215; {{ $order_item['number'] }}
                                            </p>
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
                                    <span>
                                        {{--<i>{{ $order->currency == "USD" ? '&#36;' : '&#165;' }} </i>--}}
                                        <i>{{ get_symbol_by_currency($order->currency) }} </i>
                                        {{ $order->total_shipping_fee }}
                                    </span>
                                </p>
                                <p>
                                    <span>@lang('order.Sum')：</span>
                                    <span>
                                        {{--<i>{{ $order->currency == "USD" ? '&#36;' : '&#165;' }}</i>--}}
                                        <i>{{ get_symbol_by_currency($order->currency) }}</i>
                                        {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                                        （@lang('order.Postage included')）
                                    </span>
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
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        var set_finish = false;
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".myorder_classification li").on('click', function () {
                $(".myorder_classification li").removeClass('active');
                $(this).addClass("active");
            });
            // 页面加载时判断右侧订单信息的高度
            var h = $(".left_content").height();
            $(".order_lists ul").css("height", parseInt(h - 65));
            // 上传图片
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
            $(".refunds_3").on("click", function () {
                var img_num = $(this).parents('li').find(".refund-path");
                if (img_num.length < 3) {
                    $("#refunds_photos_bill").click();
                } else {
                    layer.msg("@lang('order.Upload up to 3 images')");
                }
            });
            // 图片删除
            $(".del_btn").on('click', function () {
                $(this).parents('.refund-path').remove();
            });
            // 第一步提交退款申请
            $(".step-1-submit").on("click", function () {
                set_path("#step-1-form", 'photos_for_refund');
                if ($("#step-1-form").find("textarea").val() == null || $("#step-1-form").find("textarea").val() == "") {
                    layer.msg("@lang('order.Please fill in the application instructions')");
                    return false;
                } else {
                    if ($("#step-1-form").find("textarea").val().length < 3) {
                        layer.msg("@lang('product.Evaluation content is not less than 3 words')");
                        return false;
                    } else if ($("#step-1-form").find("textarea").val().length >= 199) {
                        layer.open({
                            content: "@lang('product.The content of the evaluation should not exceed 200 words')！",
                            skin: 'msg',
                            time: 2, // 2秒后自动关闭
                        });
                        layer.msg("@lang('product.The content of the evaluation should not exceed 200 words')！");
                        return false;
                    }
                }
                if (set_finish == true) {
                    $("#step-1-form").submit();
                }
            });
            // 判断文本域的字数
            $(".reasons_for_refunds").keyup(function () {
                var text = $(this).val();
                // 中文字数统计
                str = (text.replace(/\w/g, "")).length;
                // 非汉字的个数
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
                // 中文字数统计
                str = (text.replace(/\w/g, "")).length;
                // 非汉字的个数
                abcnum = text.length - str;
                total = str + abcnum;
                // console.log(total);
                if (total > 200) {
                    $(this).val($(this).val().substring(0, 200));
                    $(".remainder").html('0');
                    layer.msg("@lang('order.The number of words exceeds the upper limit')");
                } else {
                    var num = 200 - total;
                    $(".remainder").html(num);
                }
            });
            // 修改申请
            $(".step-2-submit-1").on("click", function () {
                $(".step-2 input").removeClass("no_border");
                $(".step-2 textarea").removeClass("no_border");
                $(".step-2 textarea").prop("readonly", false);
                $(".step-2 textarea").removeClass("marginLeftImpor");
                $(".step-2 .del_btn").removeClass('dis_n');
                $(".refunds_2").removeClass('dis_n');
                $(".remainder").removeClass('hidden');
                $(".choose_remark").removeClass("dis_n");
                $(this).addClass("dis_ni");
                $(".step-2-submit-2").removeClass("dis_ni");
            });
            // 提交保存修改
            $(".step-2-submit-2").on("click", function () {
                set_path("#step-2-form", 'photos_for_refund');
                if ($("#step-2-form").find("textarea").val() == null || $("#step-2-form").find("textarea").val() == "") {
                    layer.msg("@lang('order.Please fill in the application instructions')");
                    return false;
                } else {
                    if ($("#step-2-form").find("textarea").val().length < 3) {
                        layer.msg("@lang('product.Evaluation content is not less than 3 words')！");
                        return false;
                    } else if ($("#step-2-form").find("textarea").val().length >= 199) {
                        layer.msg("@lang('product.The content of the evaluation should not exceed 200 words')！");
                        return false;
                    }
                }
                if (set_finish == true) {
                    $("#step-2-form").submit();
                }
            });
            // 撤销退款申请
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
                    },
                });
            });
            // 提交物流单据
            $(".step-3-submit").on("click", function () {
                set_path("#step-3-form", 'photos_for_shipment');
                if (set_finish == true) {
                    $("#step-3-form").submit();
                }
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
                    },
                });
            });
            // 退款理由下拉菜单切换
            $(".choose_remark").on("change", function () {
                if ($(this).val() == "etc") {
                    $(".reasons_for_refunds").removeClass("dis_n");
                    $(".remainder").removeClass("dis_ni");
                } else {
                    $(".reasons_for_refunds").addClass("dis_n");
                    $(".reasons_for_refunds").val($(this).val());
                    $(".remainder").addClass("dis_ni");
                }
            })
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
                    btn: "@lang('app.determine')",
                });
                upLoadBtnSwitch = 0;
                return false;
            }
        }

        // 本地图片上传 按钮
        function UpLoadImg(obj) {
            var formData = new FormData();
            formData.append('image', $(obj)[0].files[0]);
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                url: "{{ route('image.upload') }}",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false, // 必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理
                processData: false, // 必须false才会自动加上正确的Content-Type
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
        function set_path(dom, input_name) {
            var order_list = $(dom).find(".refund-path");
            var path_url = "";
            $.each(order_list, function (i, n) {
                path_url += $(n).attr('data-path') + ",";
            });
            path_url = path_url.substring(0, path_url.length - 1);

            $(dom).find("input[name='" + input_name + "']").val(path_url);
            set_finish = true;
            return set_finish;
        }
    </script>
@endsection
