@extends('layouts.mobile')
@section('title', '设置')
@section('title', App::isLocale('en') ? 'Set up' : '设置')
@section('content')
    <div class="headerBar">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('basic.users.Set up')</span>
    </div>
    <div class="setBox">
        <div class="setMain">
            <a href="{{ route('mobile.users.edit', ['user' => $user->id]) }}">
                <div class="setItem">
                    <span>@lang('basic.users.Personal information')</span>
                    <div>
                        <img src="{{ asset('static_m/img/icon_Headportrait3.png') }}" class="userImg"/>
                        <img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
                    </div>
                </div>
            </a>
            <a href="{{ route('mobile.users.edit', ['user' => $user->id]) }}">
                <div class="setItem">
                    <span>@lang('basic.users.Mailbox changes')</span>
                    <div>
                        <img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
                    </div>
                </div>
            </a>
            <a href="{{ route('mobile.users.password', ['user' => $user->id]) }}">
                <div class="setItem">
                    <span>@lang('basic.users.Change_Password')</span>
                    <div>
                        <img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
                    </div>
                </div>
            </a>
            <a href="{{ route('mobile.user_addresses.index', ['user' => $user->id]) }}">
                <div class="setItem">
                    <span>@lang('basic.users.Receiving_address')</span>
                    <div>
                        <img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
                    </div>
                </div>
            </a>
            <a href="{{ route('mobile.users.edit', ['user' => $user->id]) }}">
                <div class="setItem setItemF">
                    <span>Facebook</span>
                    <div>
                        <img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
                    </div>
                </div>
            </a>
        </div>
        <button class="exitLog">@lang('basic.users.Exit Login')</button>
        <form id="logout-form" action="{{ route('mobile.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".backImg").on("click", function () {
            window.location.href = "{{route('mobile.users.home')}}";
        });
        //退出登录
        $('.exitLog').on("click", function () {
            $("#logout-form").submit();
        })
    </script>
@endsection
