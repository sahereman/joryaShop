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
				<p >
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
                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="">
                        	<label class="reset_email">
                        		<span>邮箱</span>
                        		<input id="email" type="email"  name="email" value="{{ old('email') }}"  required placeholder="请输入邮箱">
                        	</label>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                	<img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="step_btn">
						<button type="submit"  class="btn btn-primary">下一步</button>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptsAfterJs')
<script type="text/javascript">
	$(function() {
		var countdown=60;    
	    var _generate_code;
	    var myReg=/^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;
		$("#resetCode_get").on("click",function(){
			if($("#email").val()==""){
				$(".error_block").show();
			}else {
				if(myReg.test($("#email").val())){
					$(".error_block").hide();
					var disabled = $("#resetCode_get").attr("disabled");  
					_generate_code = $("#resetCode_get");
					countdown=60;
					if(disabled){        
						return false;      
					}      
					settime();	
				}else {
					$(".error_block").show();
				}
				
			}
		})
		function settime() {         
			if (countdown == 0) {        
				_generate_code.attr("disabled",false);
				_generate_code.css({backgroundColor:"transparent",color:"#7ca442",cursor:"pointer",borderColor:"#7ca442"});
				_generate_code.val("获取验证码");        
				countdown = 60;        
				return false;      
			} else {        
				_generate_code.attr("disabled", true);
				_generate_code.css({backgroundColor:"#f5f7f4",color:"#d1d3cf",cursor:"not-allowed",borderColor:"#f5f7f4"});
				_generate_code.val("" + countdown + "s");        
				countdown--;      
			}      
			setTimeout(function() {        
				settime();      
			},1000);    
		}
	});
</script>
@endsection
