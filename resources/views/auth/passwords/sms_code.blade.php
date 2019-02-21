@extends('layouts.app')
@section('title', App::isLocale('zh-CN') ? '找回密码' : 'Retrieve password')
@section('content')
    <div class="reset_psw">
        <div class="m-wrapper">
            <div class="reset_content">
                <p class="reset_title">
                    <img src="{{ asset('img/reset_psw.png') }}">
                    @lang('app.Retrieve password')
                </p>
                <div class="status clear">
                	<div>
                		<p>
	                		<span class="status_tip step_num first_step active">1</span>
	                	</p>
	                	<p>
	                		<span class="first_step step_text active">@lang('app.Confirm Account Number')</span>
	                	</p>
                	</div>
                    <div class="step_line">
                    	<p>
                    		<span class="status_tip step_num second_step">2</span>
                    	</p>
                        <p>
                        	<span class="second_step step_text">@lang('app.Enter the verification code')</span>
                        </p>
                    </div>
                    <div class="step_line">
                    	<p>
                    		<span class="status_tip step_num">3</span>
                    	</p>
                    	<p>
                    		<span class="step_text">@lang('app.Password reset')</span>
                    	</p>
                    </div>
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
                                    <span class="sel_click">@lang('app.Mobile phone number')</span>
                                    <img  class="sel_click" src="{{ asset('img/sanjiao.png') }}">
                                    <select class="choose_tel_area" name="country_code">
                                        @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                            <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="areaCode_choosed"></span>
                                    <input id="email" type="phone" name="phone" value="{{ old('phone') }}" required
                                           placeholder="@lang('app.Please select a country first')">
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
                            <button type="submit" class="btn btn-primary">@lang('app.Next')</button>
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
                    _generate_code.val("@lang('app.get verification code')");
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
            //提示选择区号
            $("#email").focus(function(){
            	if($(".areaCode_choosed").html()==""||$(".areaCode_choosed").html()==null){
            		layer.msg("@lang('app.Please select a country first')");
            		$(this).blur();
            	}
            })
            //点击事件绑定
            $('.choose_tel_area').width($('span.sel_click').width()+20);
            //选择区号
            $(".choose_tel_area").on("change", function () {
                $(".areaCode_choosed").html($(this).val());
                $(".reset_email input").addClass("active");
                $("#email").prop('placeholder',"@lang('app.Please enter phone number')");
            })
        });
    </script>
@endsection
