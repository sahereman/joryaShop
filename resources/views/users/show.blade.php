@extends('layouts.app')

@section('content')

    @include('layouts._header')


    <h1>个人设置</h1>

    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        <label>Avatar</label>

        <img src="{{$user->avatar_url}}" width="80">
        <input type="file" name="avatar" value="{{$user->avatar}}">
        <br>

        <label>Name</label>

        <input type="text" name="name" value="{{ $user->name }}">

        <label>E-Mail Address</label>

        <input type="email" name="email" value="{{ $user->email }}">

        <label>Password</label>

        <input type="password" name="password" value="{{ $user->password }}">

        <label>Confirm Password</label>

        <input type="password" name="password_confirmation" value="{{ $user->password }}">

        <button type="submit">
            Submit
        </button>
    </form>
@endsection
