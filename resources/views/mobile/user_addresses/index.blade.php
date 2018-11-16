@extends('layouts.mobile')
@section('title', '地址列表')
@section('content')
	<div class="headerBar">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"/>
		<span>地址管理</span>
	</div>
	<div class="adsBox">
		@if (false)
		<!--暂无收获地址时-->
			<div class="notAds">
				<img src="{{ asset('static_m/img/Noreceiptaddress.png') }}"/>
				<span>您还没有收获地址</span>
			</div>
		@else
		<!--有收获地址数据时-->
			<div class="adsList">
				<div class="adsItem">
					<div class="adsName">
						<span>胡八一</span>
						<span class="defaultAds">默认</span>
					</div>
					<div class="adsDetail">
						<span class="adsP">152****4545</span>
						<span class="adsD">北京市朝阳街道石门街道</span>
					</div>
					<div class="adsEdit">
						<img src="{{ asset('static_m/img/icon_edit.png') }}" class="adsE"/>
						<img src="{{ asset('static_m/img/icon_delete.png') }}" class="adsD"/>
					</div>
				</div>
				<div class="adsItem">
					<div class="adsName">
						<span>胡八一</span>
					</div>
					<div class="adsDetail">
						<span class="adsP">152****4545</span>
						<span class="adsD">北京市朝阳街道石门街道北京市朝阳街道石门街道</span>
					</div>
					<div class="adsEdit">
						<img src="{{ asset('static_m/img/icon_edit.png') }}" class="adsE"/>
						<img src="{{ asset('static_m/img/icon_delete.png') }}" class="adsD"/>
					</div>
				</div>
			</div>
		@endif
		<div class="btnBox">
			<a href="{{ route('mobile.user_addresses.create') }}" class="doneBtn">新建地址</a>
		</div>
	</div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".adsE").on("click",function(){
//      	跳转地址编辑页面（需要传参）
        	{{--window.location.href = "{{ route('mobile.user_addresses.edit') }}";--}}
        });
        $(".adsD").on("click",function(){
        	layer.open({
			  anim: 'up'
			  ,content: '确定要删除这条地址吗？'
			  ,btn: ['确认', '取消']
			});
        });
    </script>
@endsection
