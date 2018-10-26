@extends('layouts.error')

@section('title', 'Service Unavailable')

@section('message')

    @if($exception->getMessage() != '')
        {{$exception->getMessage()}}
    @else
        Be right back.
    @endif
    <br/>
    <br/>
    <br/>
    <a href="{{back()->getTargetUrl()}}"><<< 返回上一页 </a>
@stop