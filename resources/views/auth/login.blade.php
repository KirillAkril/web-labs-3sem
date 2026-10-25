
@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="registration">
        <h1>Вход в аккаунт</h1>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >

            <label for="password">Пароль</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >

            <button type="submit">Войти</button>
        </form>
    </div>
@endsection
