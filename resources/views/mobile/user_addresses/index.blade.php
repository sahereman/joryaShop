@extends('layouts.mobile')
@section('title', '地址列表')
@section('content')
    <div class="headerBar">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>地址管理</span>
    </div>
    <div class="adsBox">
        @if($addresses->isEmpty())
                <!--暂无收获地址时-->
        <div class="notAds">
            <img src="{{ asset('static_m/img/Noreceiptaddress.png') }}"/>
            <span>您还没有收获地址</span>
        </div>
        @else
                <!--有收获地址数据时-->
        <div class="adsList">
            @foreach($addresses as $address)
                <div class="adsItem">
                    <div class="adsName">
                        <span>{{ $address->name }}</span>
                        @if($address->is_default)
                            <span class="defaultAds">默认</span>
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
                             src="{{ asset('static_m/img/icon_delete.png') }}" class="adsD"/>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        <div class="btnBox">
            <a href="{{ route('mobile.user_addresses.create') }}" class="doneBtn">新建地址</a>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".adsE").on("click", function () {
            // 跳转地址编辑页面（需要传参）
            {{--window.location.href = "{{ route('mobile.user_addresses.edit') }}";--}}
        });
        $(".adsD").on("click", function () {
            layer.open({
                anim: 'up',
                content: '确定要删除这条地址吗？',
                btn: ['确认', '取消'],
            });
        });
    </script>
@endsection
