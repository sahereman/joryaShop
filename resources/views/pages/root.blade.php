@extends('layouts.app')
@section('title', '首页标题')

@section('content')

    @include('layouts._header')

    <center>
        <h1>首页</h1>
    </center>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        console.log('单独页面JS写这里')
    </script>
@endsection