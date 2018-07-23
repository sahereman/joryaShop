@extends('layouts.app')
@section('title', '首页标题')

@section('content')

    @include('layouts._header')

    <center>
        <h1>首页</h1>
    </center>

    <center>
            <h4><a href="{{route('success')}}">成功提示页示例</a></h4>
            <h4><a href="{{route('error')}}">错误提示页示例</a></h4>
    </center>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        console.log('单独页面JS写这里')
    </script>
@endsection