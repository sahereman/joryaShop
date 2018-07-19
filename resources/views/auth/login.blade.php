@extends('layouts.app')

@section('content')

    @include('layouts._header')


    @include('common.error')

    <h1>login</h1>

    <form  method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <label for="email">E-Mail Address</label>

        <input id="email" type="email" name="email" value="{{ old('email') }}">


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

@endsection
