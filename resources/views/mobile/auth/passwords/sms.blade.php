@extends('layouts.mobile')
@section('title', '短信重置')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}

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
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="请先选择国家" class="phoneIpt" maxlength="11">
                <div class="tipBox">
                    @if ($errors->has('phone'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span class="tipSpan"> {{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>
            <div class="codeBox">
                <img src="{{ asset('static_m/img/icon_yzm.png') }}" class="fImg"/>
                <input type="text" name="code" value="" placeholder="请输入动态验证码" class="codeIpt" maxlength="6">
                <div class="getYBox">
                    <span class="getY">获取动态验证码</span>
                    <span class="cutTime"></span>
                </div>
            </div>
            <button type="submit" class="subBtn">
                下一步
            </button>
        </form>
        <div class="downBox">
            ——— 卓雅美业有限公司 ———
        </div>
    </div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".getY").on("click", function () {
            var phoneVal = $(".phoneIpt").val();
            if (phoneVal == "") {
                //未填手机号
                layer.open({
                    content: '请填写手机号'
                    , time: 2
                    , skin: 'msg'
                });

            } else {
                $(this).css("display", "none");
                $(".cutTime").css("display", "inline-block");
                //触发倒计时
                settime();
                //调取获取动态验证码接口(TODO)

                $.ajax({
                    url: "{{route('reset.resend_sms_code')}}",    //请求的url地址
                    type: "POST",   //请求方式
                    dataType: "json",   //返回格式为json
                    data: {
                        "_token": "{{csrf_token()}}",
                        "country_code": "86",
                        "phone": "18600982820"
                    },
                    success: function (response, status, xhr) {
                        //请求成功时处理
                        console.log(response);
                    },
                    error: function (xhr, errorText, errorStatus) {
                        //请求出错处理
                        if (xhr.status === 422) {
                            // http 状态码为 422 代表用户输入校验失败
                            console.log(xhr.responseJSON);
                        } else {
                            console.log('系统错误');
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
            $(".phoneIpt").attr("placeholder", "请输入手机号");
        });
        $(".phoneIpt").on("focus", function () {
            var countryCode = $(".valSpan").html();
            if (countryCode == "") {
                $(this).blur();
                layer.open({
                    content: '请先选择国家'
                    , time: 2
                    , skin: 'msg'
                });
            }

        });
    </script>
@endsection
