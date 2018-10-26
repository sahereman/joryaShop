@extends('layouts.error')

@section('title', 'Error')

@section('message')

    @if($exception->getMessage() != '')
        {{$exception->getMessage()}}
    @else
        Too many requests..
    @endif
    <br/>
    <br/>
    <br/>
    <a href="{{back()->getTargetUrl()}}"><<< 返回上一页 </a>
@stop
