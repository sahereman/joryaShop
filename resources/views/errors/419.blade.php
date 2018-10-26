@extends('layouts.error')

@section('title', 'Page Expired')

@section('message')

    @if($exception->getMessage() != '')
        {{$exception->getMessage()}}
    @else
        The page has expired due to inactivity.
        <br/>
        <br/>
        Please refresh and try again.
    @endif
    <br/>
    <br/>
    <br/>
    <a href="{{back()->getTargetUrl()}}"><<< 返回上一页 </a>
@stop