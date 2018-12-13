@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Address List' : '地址列表')
@section('content')
    <div class="headerBar">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('basic.address.Address Management')</span>
    </div>
    <div class="ads1Box">
        @if($addresses->isEmpty())
                <!--暂无收货地址时-->
        <div class="notAds">
            <img src="{{ asset('static_m/img/Noreceiptaddress.png') }}"/>
            <span>@lang('basic.address.not have a shipping address yet')</span>
        </div>
        @else
                <!--有收货地址数据时-->
        <div class="adsList">
            @foreach($addresses as $address)
                <div class="adsItem">
                    <div class="adsName">
                        <span class="address_name">{{ $address->name }}</span>
                        @if($address->is_default)
                            <span class="defaultAds">@lang('basic.address.Default')</span>
                        @endif
                    </div>
                    <div class="adsDetail">
                        <span class="adsP">{{ substr($address->phone, 0, 3) . '****' . substr($address->phone, -4) }}</span>
                        <span class="adsD">{{ $address->address }}</span>
                    </div>
                    <div class="adsEdit">
                        <img data-url="{{ route('mobile.user_addresses.edit', ['address' => $address->id]) }}"
                             src="{{ asset('static_m/img/icon_edit.png') }}" class="adsE"/>
                        <img data-url="{{ route('user_addresses.destroy', ['address' => $address->id]) }}"
                             src="{{ asset('static_m/img/icon_delete.png') }}" class="adsD add_del"/>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        <div class="btnBox">
            <a href="{{ route('mobile.user_addresses.create') }}" class="doneBtn">@lang('basic.address.The new address')</a>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".adsE").on("click", function () {
            // 跳转地址编辑页面（需要传参）
            window.location.href = $(this).attr("data-url");
        });
        $(".adsList").on("click", ".add_del",function () {
            var url = $(this).attr("data-url");
            layer.open({
                anim: 'up',
                content: "@lang('basic.address.Are you sure you want to delete this address')?",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function(index){
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
			      layer.close(index);
			    }
            });
        });
    </script>
@endsection
