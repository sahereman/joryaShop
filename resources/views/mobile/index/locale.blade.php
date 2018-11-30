@extends('layouts.mobile')
@section('title', '切换语言')
@section('content')
    <div class="headerBar">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>切换语言</span>
    </div>
    <div class="langBox">
        <div class="langItem">
            <div class="langLeft">
                <img src="{{ asset('static_m/img/chinese.png') }}"/>
                <span>中文</span>
            </div>
            <div class="langRight">
                <input type="radio" name="lang" id="" value=""/>
                <span></span>
            </div>
        </div>
        <div class="langItem">
            <div class="langLeft">
                <img src="{{ asset('static_m/img/English.png') }}"/>
                <span>English</span>
            </div>
            <div class="langRight">
                <input type="radio" name="lang" id="" value=""/>
                <span></span>
            </div>
        </div>
    </div>
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
