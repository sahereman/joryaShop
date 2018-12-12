@extends('layouts.error')

@section('title', 'Unprocessable Entity')


@section('message')

    {{--@if($exception->getMessage() != '')
        {{$exception->getMessage()}}
    @else
        Unprocessable Entity.
    @endif
    <br/>
    <br/>
    <br/>
    <a href="{{back()->getTargetUrl()}}"><<< 返回上一页 </a>--}}
    <div class="error_box">
    	<img src="{{ asset('defaults/default_mobile_404.png') }}">
    	<p class="content">网络请求失败</p>
    	<p class="content">请检查您的网络</p>
    	<p class="btnBox">
    		<a href="{{back()->getTargetUrl()}}">返回上一页</a>
    	</p>
    </div>
@stop