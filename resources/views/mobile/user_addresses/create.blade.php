@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '新增地址' : 'New Addresses') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="headerBar">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>@lang('basic.address.New Revenue Address')</span>
        @endif
    </div>
    <div class="addAdsBox">
        <form method="POST" action="{{ route('user_addresses.store') }}" enctype="multipart/form-data" id="creat-form"
              class="addAdsForm">
            {{ csrf_field() }}
            <div class="addAdsItem">
                <label class="must">@lang('basic.address.The consignee')</label>
                <input type="text" name="name" id="" value=""
                       placeholder="@lang('basic.address.Please fill in the consignee')"/>
            </div>
            <div class="addAdsItem">
                <label class="must">@lang('basic.address.Cellphone number')</label>
                <input type="text" name="phone" id="" value=""
                       placeholder="@lang('basic.address.Please fill in your mobile phone number')"/>
            </div>
            <div class="addAdsItem">
                <label class="must">Country or region</label>
                <input type="text" name="country" id="" value=""
                       placeholder="Please fill in your Country or region"/>
            </div>
            <div class="addAdsItem">
                <label class="must">City</label>
                <input type="text" name="city" id="" value=""
                       placeholder="Please fill in your City"/>
            </div>
            <div class="addAdsItem">
                <label class="must">State/Province/Region</label>
                <input type="text" name="province" id="" value=""
                       placeholder="Please fill in your State/Province/Region"/>
            </div>
            <div class="addAdsItem" style="border:none;">
                <label class="must">@lang('basic.address.Detailed address')</label>
                {{--<input type="text" name="address" id="" value="" placeholder="@lang('basic.address.Detailed_address')"/>--}}
                <textarea name="address" placeholder="@lang('basic.address.Detailed_address')"></textarea>
            </div>
            <div class="defaultBox">
                <label>@lang('basic.address.Set as default address')</label>
                <input type="hidden" name="is_default" class="setas_default">
                <img src="{{ asset('static_m/img/icon_OFF.png') }}" class="switchBtn"/>
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
