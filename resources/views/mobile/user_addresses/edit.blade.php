@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Address editing' : '地址编辑')
@section('content')
    <div class="headerBar">
    	@if(!is_wechat_browser())
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('basic.address.Edit Shipping Address')</span>
        @endif
    </div>
    <div class="addAdsBox">
        <form action="{{ route('user_addresses.update', ['address' => $address->id]) }}"  method="post" class="addAdsForm">
        	{{ csrf_field() }}
        	{{ method_field('PUT') }}
            <div class="addAdsItem">
                <label class="must">@lang('basic.address.The consignee')</label>
                <input type="text" name="name" id="" value="{{ $address->name }}"/>
            </div>
            <div class="addAdsItem">
                <label class="must">@lang('basic.address.Cellphone number')</label>
                <input type="text" name="phone" id="" value="{{ $address->phone }}"/>
            </div>
            <div class="addAdsItem" style="border:none;">
                <label class="must">@lang('basic.address.Detailed address')</label>
                {{--<input type="text" name="address" id="" value="{{ $address->address }}"/>--}}
                <textarea name="address" value="">{{ $address->address }}</textarea>
            </div>
            <div class="defaultBox">
                <label>@lang('basic.address.Set as default address')</label>
                <input type="hidden" name="is_default" class="setas_default" value="{{ $address->is_default }}">
                @if($address->is_default)
                    <img src="{{ asset('static_m/img/icon_ON.png') }}" class="switchBtn"/>
                @else
                    <img src="{{ asset('static_m/img/icon_OFF.png') }}" class="switchBtn"/>
                @endif
            </div>
            <button type="submit" class="doneBtn">@lang('basic.users.Save')</button>
        </form>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".switchBtn").on("click", function () {
            if ($(this).attr("src") == "{{ asset('static_m/img/icon_OFF.png') }}") {
                $(this).attr("src", "{{ asset('static_m/img/icon_ON.png') }}");
                $(".setas_default").val("1");
            } else {
                $(this).attr("src", "{{ asset('static_m/img/icon_OFF.png') }}");
                $(".setas_default").val("0");
            }
        });
    </script>
@endsection
