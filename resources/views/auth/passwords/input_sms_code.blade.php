@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Retrieve password' : '找回密码')
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
	                		<span class="status_tip step_num first_step">1</span>
	                	</p>
	                	<p>
	                		<span class="first_step step_text">@lang('app.Confirm Account Number')</span>
	                	</p>
                	</div>
                    <div class="step_line">
                    	<p>
                    		<span class="status_tip step_num second_step active">2</span>
                    	</p>
                        <p>
                        	<span class="second_step step_text active">@lang('app.Enter the verification code')</span>
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
                    <form class="form-horizontal" method="POST" action="{{ route('reset.verify_sms_code') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            {{--<div class="">
                                <label class="reset_email">
                                    <span>@lang('app.Mobile phone number')</span>
                                    <img src="{{ asset('img/sanjiao.png') }}">
                                    <span class="areaCode_choosed">{{ old('country_code') }}</span>
                                    <input id="email" type="phone" name="phone" value="{{ old('phone') }}" readonly
                                           class='active' required placeholder="@lang('app.Please enter phone number')">
                                </label>
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                                <label class="reset_code">
                                    <span>@lang('app.Authentication Code')</span>
                                    <input id="code" type="text" name="code" value="{{ old('code') }}"
                                           placeholder="@lang('app.please enter verification code')" required>
                                    <input type="button" id="resetCode_get" data-url="{{ route('reset.resend_sms_code') }}" value="@lang('app.get verification code')">
                                </label>
                                @if ($errors->has('code'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>--}}
                            <ul class="reset_info_content">
                            	<li>
                            		<label class="reset_email">
                                    <span>@lang('app.Mobile phone number')</span>
                                    <img src="{{ asset('img/sanjiao.png') }}">
                                    <input type="hidden" class="choose_tel_area" name="country_code" value="{{ old('country_code') }}">
                                    <span class="areaCode_choosed">{{ old('country_code') }}</span>
                                    <input id="email" type="phone" name="phone" value="{{ old('phone') }}" readonly
                                           class='active' required placeholder="@lang('app.Please enter phone number')">
	                                </label>
	                                @if ($errors->has('phone'))
	                                    <span class="help-block">
	                                    <img src="{{ asset('img/error_fork.png') }}">
	                                    <strong>{{ $errors->first('phone') }}</strong>
	                                    </span>
	                                @endif
                            	</li>
                            	<li>
                            		<label class="reset_code">
                                    <span>@lang('app.Authentication Code')</span>
                                    <input id="code" type="text" name="code" value="{{ old('code') }}"
                                           placeholder="@lang('app.please enter verification code')" required>
                                    <input type="button" id="resetCode_get" data-url="{{ route('reset.resend_sms_code') }}" value="@lang('app.get verification code')">
	                                </label>
	                                @if ($errors->has('code'))
	                                    <span class="help-block">
	                                    <img src="{{ asset('img/error_fork.png') }}">
	                                    <strong>{{ $errors->first('code') }}</strong>
	                                    </span>
	                                @endif
                            	</li>
                            </ul>
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
//          var myReg = /^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;
            var myReg = /^\d+$/;
            $("#resetCode_get").on("click", function () {
            	var url = $(this).attr("data-url");
                $(".error_block").hide();
                var disabled = $("#resetCode_get").attr("disabled");
                _generate_code = $("#resetCode_get");
                if (disabled) {
                    return false;
                }
                var data = {
                    phone: $("#email").val(),
                    country_code: $(".areaCode_choosed").html(),
                    _toke: "{{ csrf_token() }}"
                }
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        settime();
                    },
                    error: function (err) {
                        console.log(err);
                        if(err.status==500){
							$("#resetCode_get").prop("disabled",false);
							$("#resetCode_get").click();
						}
                    }
                });
            });
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
            if (window.performance) {
			  console.info("window.performance works fine on this browser");
			}
			  if (performance.navigation.type == 1) {
			    $("#email").val(GetCookie('phone_num'));
			    $(".areaCode_choosed").html(GetCookie('countru_code'))
//			    location.hash='/app/homepage'
			  } else {
			    SetCookie("phone_num",$("#email").val());
			    SetCookie("countru_code",$(".areaCode_choosed").html());
			  }

			function SetCookie(name,value) {
			    var key='';
			    var Days = 2;
			    var exp = new Date();
			    var domain = "";
			    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
			    if (key == null || key == "") {
			        document.cookie = name + "=" + encodeURI(value) + ";expires=" + exp.toGMTString() + ";path=/;domain=" + domain + ";";
			    }
			    else {
			        var nameValue = GetCookie(name);
			        if (nameValue == "") {
			            document.cookie = name + "=" + key + "=" + encodeURI(value) + ";expires=" + exp.toGMTString() + ";path=/;domain=" + domain + ";";
			        }
			        else {
			            var keyValue = getCookie(name, key);
			            if (keyValue != "") {
			                nameValue = nameValue.replace(key + "=" + keyValue, key + "=" + encodeURI(value));
			                document.cookie = name + "=" + nameValue + ";expires=" + exp.toGMTString() + ";path=/;domain=" + domain + ";";
			            }
			            else {
			                document.cookie = name + "=" + nameValue + "&" + key + "=" + encodeURI(value) + ";expires=" + exp.toGMTString() + ";path=/;" + domain + ";";
			            }
			        }
			    }
			}
			
			function GetCookie(name) {
			    var nameValue = "";
			    var key="";
			    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
			    if (arr = document.cookie.match(reg)) {
			        nameValue = decodeURI(arr[2]);
			    }
			    if (key != null && key != "") {
			        reg = new RegExp("(^| |&)" + key + "=([^(;|&|=)]*)(&|$)");
			        if (arr = nameValue.match(reg)) {
			            return decodeURI(arr[2]);
			        }
			        else return "";
			    }
			    else {
			        return nameValue;
			    }
			}

            
            
            
        });
    </script>
@endsection
