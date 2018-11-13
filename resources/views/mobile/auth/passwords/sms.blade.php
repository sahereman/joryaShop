@extends('layouts.mobile')
@section('title', '短信重置')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}

    {{--@if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <li> {{ $error }}</li>
        @endforeach
    @endif--}}
    
    <div class="regMain">
		<div class="logoImgBox">
			<img src="{{ asset('static_m/img/logo.png') }}" />
		</div>
		<form method="POST" action="{{ route('mobile.reset.sms.store') }}" class="formBox">
        	{{ csrf_field() }}
			<div class="phoneBox">
				<img src="{{ asset('static_m/img/icon_phone.png') }}" class="fImg" />
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
				<img src="{{ asset('static_m/img/icon_yzm.png') }}" class="fImg" />
	        	<input type="text" name="code" value="" placeholder="请输入动态验证码" class="codeIpt" maxlength="4">
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
        	——— 卓雅美业有限公司  ———
        </div>
   </div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".getY").on("click",function(){
        	$(this).css("display","none");
        	$(".cutTime").css("display","inline-block");
        	settime();
        });
        //短信发送倒计时器
		var countdown = 10;
		var settime = function () {
		  if (countdown < 0) {
		  	$(".getY").css("display","inline-block");
		    $(".cutTime").css("display","none");
		    countdown = 10;
		    return;
		  } else {
		    $(".getY").css("display","none");
		    $(".cutTime").css("display","inline-block");
		    $(".cutTime").html(countdown+"s");
		    countdown--;
		  }
		  setTimeout(function () {
		    settime()
		  }
		    , 1000)
		}
		$("#myselect").change(function(){
		    var opt=$("#myselect").val();
		    $(".valSpan").html(opt);
		    $(".phoneIpt").attr("placeholder","请输入手机号");
		});
        
    </script>
@endsection
