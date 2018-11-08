@extends('layouts.mobile')

@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}

    <h1>手机站注册页面</h1>

    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <li> {{ $error }}</li>
        @endforeach
    @endif

    <form method="POST" action="{{ route('mobile.register') }}">
        {{ csrf_field() }}

        <label>Name</label>

        <input type="text" name="name" value="{{ old('name') }}">

        <label>country_code</label>

        <select name="country_code">
            @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
            @endforeach
        </select>

        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}">


        <label>code 手机验证码</label>
        <input type="text" name="code" value="">

        <label>Password</label>

        <input type="password" name="password">

        <button type="submit">
            Register
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
