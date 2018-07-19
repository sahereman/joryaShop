@extends('layouts.app.blade.php')
@section('title', '首页标题')

@section('content')

    @include('layouts._header')

    <h1>首页</h1>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        console.log('单独页面JS写这里')
    </script>
@endsection