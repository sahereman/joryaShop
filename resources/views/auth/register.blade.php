@extends('layouts.app')

@section('content')

    @include('layouts._header')

    @include('common.error')

    <h1>register</h1>

    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <label>Name</label>

        <input type="text" name="name" value="{{ old('name') }}">

        <label>E-Mail Address</label>

        <input type="email" name="email" value="{{ old('email') }}">

        <label>Password</label>

        <input type="password" name="password">

        <label>Confirm Password</label>

        <input type="password" name="password_confirmation">

        <button type="submit">
            Register
        </button>
    </form>
@endsection
