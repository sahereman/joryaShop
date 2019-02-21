@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '个人中心' : 'Personal Center') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="userBox">
        <div class="userH">
            <div class="userHTop">
                <img src="{{ asset('static_m/img/icon_search2.png') }}" class="searchImg"/>
                <span>@lang('basic.users.Personal_Center')</span>
                <img src="{{ asset('static_m/img/icon_setting.png') }}" class="setImg"/>
            </div>
            <div class="userHMain">
                <img src="{{ $user->avatar_url }}" class="userHeaderImg"/>
                <div class="userInfo">
                    <span>{{ $user->name }}</span>
                    <img src="{{ asset('static_m/img/icon_Editpersonal.png') }}"/>
                    <p>{{ substr($user->phone, 0, 3) . '****' . substr($user->phone, -4) }}</p>
                </div>
            </div>
        </div>
        <div class="userOrder">
            <div class="userOdrHead">
                <span>@lang('basic.users.My_order')</span>
                <a href="{{ route('mobile.orders.index') }}">@lang('basic.users.ALL')></a>
            </div>
            <div class="userOrderMain">
                <a href="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_PAYING }}">
                    <div class="userOrderMainItem">
                        {{--@if($paying_orders_count > 0)
                            <label class="num">{{ $paying_orders_count }}</label>
                        @endif--}}
                        <img src="{{ asset('static_m/img/icon_Pendingpayment.png') }}"/>
                        <span>@lang('basic.users.Pending_payment')</span>
                    </div>
                </a>
                <a href="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_RECEIVING }}">
                    <div class="userOrderMainItem">
                        {{--@if($receiving_orders_count > 0)
                            <label class="num">{{ $receiving_orders_count }}</label>
                        @endif--}}
                        <img src="{{ asset('static_m/img/icon_Goodstobereceived2.png') }}"/>
                        <span>@lang('basic.users.On_the_receiving_line')</span>
                    </div>
                </a>
                <a href="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_UNCOMMENTED }}">
                    <div class="userOrderMainItem">
                        {{--@if($uncommented_orders_count > 0)
                            <label class="num">{{ $uncommented_orders_count }}</label>
                        @endif--}}
                        <img src="{{ asset('static_m/img/icon_stayevaluate.png') }}"/>
                        <span>@lang('basic.users.Pending_feedback')</span>
                    </div>
                </a>
                <a href="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_REFUNDING }}">
                    <div class="userOrderMainItem">
                        {{--@if($refunding_orders_count > 0)
                            <label class="num">{{ $refunding_orders_count }}</label>
                        @endif--}}
                        <img src="{{ asset('static_m/img/icon_After-saleorder.png') }}"/>
                        <span>@lang('basic.users.After_sales_order')</span>
                    </div>
                </a>
            </div>
        </div>
        <div class="userItemBox">
            <a href="{{ route('mobile.user_favourites.index') }}">
                <div class="userItem userItemCollection">
                    <img src="{{ asset('static_m/img/icon_Collection.png') }}" class="userItemImgF"/>
                    <span>@lang('basic.users.My_collection')</span>
                    <img src="{{ asset('static_m/img/icon_more.png') }}" class="userItemBack"/>
                </div>
            </a>
            <a href="{{ route('mobile.user_addresses.index') }}">
                <div class="userItem userItemAddress">
                    <img src="{{ asset('static_m/img/icon_Myaddress.png') }}" class="userItemImgD"/>
                    <span>@lang('basic.users.My address')</span>
                    <img src="{{ asset('static_m/img/icon_more.png') }}" class="userItemBack"/>
                </div>
            </a>
            <a href="{{ route('mobile.user_histories.index') }}">
                <div class="userItem userItemFootprint">
                    <img src="{{ asset('static_m/img/icon_MyTracks.png') }}" class="userItemImgF"/>
                    <span>@lang('basic.users.My footprints')</span>
                    <img src="{{ asset('static_m/img/icon_more.png') }}" class="userItemBack"/>
                </div>
            </a>
        </div>
    </div>
    @include('layouts._footer_mobile')
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
        $(".itemsW").addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Unchecked_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Unchecked_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Unchecked_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Select_my.png') }}");
        $(".userHMain").on("click", function () {
            //跳转修改个人信息页面
            window.location.href = "{{route('mobile.users.edit',Auth::id())}}";
        });
        $(".setImg").on("click", function () {
            //跳转设置页面
            window.location.href = "{{route('mobile.users.setting',Auth::id())}}";
        });
        $(".searchImg").on("click", function () {
            window.location.href = "{{route('mobile.search')}}";
        });
    </script>
@endsection
