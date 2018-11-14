@extends('layouts.mobile')

@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <h1>个人中心</h1>


    <a href="{{ route('mobile.root') }}">首页</a> /

    <a href="{{ route('mobile.users.edit',Auth::id()) }}">编辑个人信息</a> /

    <a href="{{ route('mobile.users.password',Auth::id()) }}">修改密码</a> /

    <a href="{{ route('mobile.users.setting',Auth::id()) }}">设置</a> /

    <a href="{{ route('mobile.user_addresses.index') }}">收货地址 列表</a> /
    <a href="{{ route('mobile.user_addresses.create') }}">收货地址 新增</a> /


    <a href="{{ route('mobile.orders.index') }}">我的订单 列表</a> /
    <a href="{{ route('mobile.orders.show',\App\Models\Order::where('user_id',Auth::id())->first()) }}"> 我的订单 详情</a> /

    <a href="{{ route('mobile.orders.create_comment',\App\Models\Order::where('user_id',Auth::id())->where('status','completed')->first()) }}"> 创建评价</a> /
    <a href="{{ route('mobile.orders.show_comment',\App\Models\Order::where('user_id',Auth::id())->where('status','completed')->first()) }}"> 查看评价</a> /


    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
