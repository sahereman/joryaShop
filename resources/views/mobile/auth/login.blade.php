@extends('layouts.mobile')

@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}

    <h1>手机站登录页</h1>

    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <li> {{ $error }}</li>
        @endforeach
    @endif

    <form method="POST" action="{{ route('mobile.login') }}">
        {{ csrf_field() }}

        <label for="username">User Name / E-Mail Address</label>

        <input id="username" type="text" name="username" value="{{ old('username') }}">


        <label for="password">Password</label>

        <input id="password" type="password" name="password">

        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label>
        <button type="submit">
            Login
        </button>

        <a href="{{ route('password.request') }}">
            Forgot Your Password?
        </a>
    </form>



    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
