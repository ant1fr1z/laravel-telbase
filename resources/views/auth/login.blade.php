@extends('layouts.main')

@section('title')
    БД -> Автентифікація
@endsection

@section('content')
    <!-- resources/views/auth/login.blade.php -->

    <form method="POST" action="/auth/login">
        {!! csrf_field() !!}

        <div>
            Email
            <input type="name" name="name" value="{{ old('name') }}">
        </div>
        <div>
            Password
            <input type="password" name="password" id="password">
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>
@endsection