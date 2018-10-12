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
                    <span class="status_tip first_step">1</span>
                    <div>
                        <span class="status_tip active">2</span>
                    </div>
                    <div>
                        <span class="status_tip">3</span>
                    </div>
                    <p>
                        <span class="first_step">确认账号</span>
                        <span class="active">输入验证码</span>
                        <span>密码重置</span>
                    </p>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('reset.verify_email_code') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="">
                                <label class="reset_email">
                                    <span>邮箱</span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" readonly
                                           required placeholder="请输入邮箱">
                                </label>
                                <label class="reset_code">
                                    <span>验证码</span>
                                    <input id="code" type="text" name="code" value="{{ old('code') }}"
                                           placeholder="请输入验证码" required>
                                    <input type="button" id="resetCode_get" value="获取验证码">
                                </label>
                                @if ($errors->has('code'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('code') }}</strong>
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
            $("#resetCode_get").on("click", function () {
                $(".error_block").hide();
                var disabled = $("#resetCode_get").attr("disabled");
                _generate_code = $("#resetCode_get");
                if (disabled) {
                    return false;
                }
                var data = {
                	email: $("#email").val(),
                	_toke: "{{ csrf_token() }}"
                }
                console.log(data);
                $.ajax({
                	type:"post",
                	url:"{{ route('reset.send_email_code') }}",
                	data: data,
                	success:function(json){          
						console.log(json);          
						settime();        
					},        
					error:function(err){          
						console.log(err);        
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
        });
    </script>
@endsection


            <!--<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token or old('token') }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                                @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Reset Password
                </button>
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>-->
    <!--</div>
</div>-->
    {{--@endsection--}}
