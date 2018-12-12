@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'SMS Reset' : '短信重置')
@section('content')
    <div class="regMain">
        <div class="logoImgBox">
            <img src="{{ asset('static_m/img/logo.png') }}"/>
        </div>
        <form method="POST" action="{{ route('mobile.reset.sms.store') }}" class="formBox">
            {{ csrf_field() }}
            <div class="phoneBox">
                <img src="{{ asset('static_m/img/icon_phone.png') }}" class="fImg"/>
                <div class="triangle"></div>
                <select name="country_code" class="selCountry" id="myselect">
                    @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                        <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                    @endforeach
                </select>
                <span class="valSpan"></span>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="@lang('app.Please select a country first')" class="phoneIpt"
                       maxlength="11">
                <div class="tipBox">
                    @if ($errors->has('phone'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span class="tipSpan"> {{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>
            <div class="codeBox">
                <img src="{{ asset('static_m/img/icon_yzm.png') }}" class="fImg"/>
                <input type="text" name="code" value="" placeholder="@lang('app.please enter verification code')" class="codeIpt" maxlength="6">
                <div class="getYBox">
                    <span class="getY">@lang('app.get verification code')</span>
                    <span class="cutTime"></span>
                </div>
            </div>
            <button type="submit" class="subBtn">
                @lang('app.Next')
            </button>
        </form>
        <div class="downBox">
            ——— @lang('app.Jorya Limited') ———
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".getY").on("click", function () {
            var phoneVal = $(".phoneIpt").val();
            var countryCode = $(".valSpan").html();
            if (phoneVal == "") {
                //未填手机号
                layer.open({
                    content: "@lang('app.Please fill in your mobile phone number')"
                    , time: 2
                    , skin: 'msg'
                });

            } else {
                //调取获取动态验证码接口(TODO)
                $.ajax({
                    url: "{{route('register.send_sms_code')}}",    //请求的url地址
                    type: "POST",   //请求方式
                    dataType: "json",   //返回格式为json
                    data: {
                        "_token": "{{csrf_token()}}",
                        "country_code": countryCode,
                        "phone": phoneVal
                    },
                    success: function (response, status, xhr) {
                        //请求成功时处理
                        // console.log(response);
                        layer.open({
                            content: "@lang('app.Sent successfully')"
                            , time: 2
                            , skin: 'msg'
                        });
                        $(this).css("display", "none");
                        $(".cutTime").css("display", "inline-block");
                        //触发倒计时
                        settime();
                    },
                    error: function (xhr, errorText, errorStatus) {
                        //请求出错处理
                        if (xhr.status === 422) {
                            // http 状态码为 422 代表用户输入校验失败
                            layer.open({
                                content: xhr.responseJSON.errors.phone[0]
                                , time: 2
                                , skin: 'msg'
                            });
                        } else {
                            layer.open({
                                content: "@lang('app.System error')"
                                , time: 2
                                , skin: 'msg'
                            });
                        }
                    }
                });

            }
        });
        //短信发送倒计时器
        var countdown = 10;
        var settime = function () {
            if (countdown < 0) {
                $(".getY").css("display", "inline-block");
                $(".cutTime").css("display", "none");
                countdown = 10;
                return;
            } else {
                $(".getY").css("display", "none");
                $(".cutTime").css("display", "inline-block");
                $(".cutTime").html(countdown + "s");
                countdown--;
            }
            setTimeout(function () {
                        settime()
                    }
                    , 1000)
        }
        $("#myselect").change(function () {
            var opt = $("#myselect").val();
            $(".valSpan").html(opt);
            $(".phoneIpt").attr("placeholder", "@lang('app.Please enter phone number')");
        });
        $(".phoneIpt").on("focus", function () {
            var countryCode = $(".valSpan").html();
            if (countryCode == "") {
                $(this).blur();
                layer.open({
                    content: "@lang('app.Please select a country first')"
                    , time: 2
                    , skin: 'msg'
                });
            }

        });
    </script>
@endsection
