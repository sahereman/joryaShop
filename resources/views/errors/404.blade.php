@extends('layouts.error')

@section('title', 'Page Not Found')

@section('message')

    @if($exception->getMessage() != '')
        {{$exception->getMessage()}}
    @else
        Sorry, the page you are looking for could not be found.
    @endif
    <br/>
    <br/>
    <br/>
    <a href="{{back()->getTargetUrl()}}"><<< 返回上一页 </a>
@stop