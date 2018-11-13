@extends('layouts.app')
@section('title', '找回密码')

@section('content')
    <div class="reset_psw">
        <div class="m-wrapper">
            <div class="reset_content">
                <p class="reset_title">
                    <img src="{{ asset('img/reset_psw.png') }}">
                    找回密码
                </p>
                <div class="status">
                    <span class="status_tip first_step active">1</span>
                    <div>
                        <span class="status_tip second_step">2</span>
                    </div>
                    <div>
                        <span class="status_tip">3</span>
                    </div>
                    <p>
                        <span class="first_step active">确认账号</span>
                        <span class="second_step">输入验证码</span>
                        <span>密码重置</span>
                    </p>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('reset.send_sms_code') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <div class="">
                                <label class="reset_email">
                                    <span>手机号</span>
                                    <img src="{{ asset('img/sanjiao.png') }}">
                                    <select class="choose_tel_area" name="country_code">
                                        @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                            <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="areaCode_choosed"></span>
                                    <input id="email" type="phone" name="phone" value="{{ old('phone') }}" required
                                           placeholder="请输入手机号">
                                </label>
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="step_btn">
                            <button type="submit" class="btn btn-primary">下一步</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            var countdown = 60;
            var _generate_code;
            //var myReg = /^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;
            var myReg = /^\d+$/;
            $("#resetCode_get").on("click", function () {
                if ($("#email").val() == "") {
                    $(".error_block").show();
                } else {
                    if (myReg.test($("#email").val())) {
                        $(".error_block").hide();
                        var disabled = $("#resetCode_get").attr("disabled");
                        _generate_code = $("#resetCode_get");
                        countdown = 60;
                        if (disabled) {
                            return false;
                        }
                        settime();
                    } else {
                        $(".error_block").show();
                    }

                }
            })
            function settime() {
                if (countdown == 0) {
                    _generate_code.attr("disabled", false);
                    _generate_code.css({
                        backgroundColor: "transparent",
                        color: "#7ca442",
                        cursor: "pointer",
                        borderColor: "#7ca442"
                    });
                    _generate_code.val("获取验证码");
                    countdown = 60;
                    return false;
                } else {
                    _generate_code.attr("disabled", true);
                    _generate_code.css({
                        backgroundColor: "#f5f7f4",
                        color: "#d1d3cf",
                        cursor: "not-allowed",
                        borderColor: "#f5f7f4"
                    });
                    _generate_code.val("" + countdown + "s");
                    countdown--;
                }
                setTimeout(function () {
                    settime();
                }, 1000);
            }

            //选择区号
            $(".choose_tel_area").on("change", function () {
                $(".areaCode_choosed").html($(this).val());
                $(".reset_email input").addClass("active");
            })
        });
    </script>
@endsection
