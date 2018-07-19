@extends('layouts.app')
@section('title', '操作成功')

@section('content')

    <center>
        <h1>操作成功后跳转到此页面</h1>
        <h2>{{ $msg }}</h2>
        <a class="btn btn-primary" href="{{ route('root') }}">返回首页</a>
    </center>
@endsection