@extends('layouts.mobile')

@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <h1>重置新密码页面</h1>

    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <li> {{ $error }}</li>
        @endforeach
    @endif

    <form method="POST" action="{{ route('mobile.reset.override.store') }}">
        {{ csrf_field() }}


        <label>Password</label>

        <input type="password" name="password">

        <label>Confirm Password</label>

        <input type="password" name="password_confirmation">

        <button type="submit">
            submit
        </button>
    </form>

    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
