@extends('layouts.app.blade.php')

@section('content')
    <center>
        <h1>错误发生后跳转到此页面</h1>
        <h2>{{ $msg }}</h2>
        <a class="btn btn-primary" href="{{ route('root') }}">返回首页</a>
    </center>
@endsection