@extends('layouts.error')

@section('title', 'Unprocessable Entity')


@section('message')

    @if($exception->getMessage() != '')
        {{$exception->getMessage()}}
    @else
        Unprocessable Entity.
    @endif
    <br/>
    <br/>
    <br/>
    <a href="{{back()->getTargetUrl()}}"><<< 返回上一页 </a>
@stop