
@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="registration">
        <h1>Регистрация пользователя</h1>

        <p>Заполните форму, чтобы отправить данные.</p>

        @if ($errors->any())
            <div class="errors">
                <strong>Проверьте введённые данные:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('signin') }}" method="POST">
            @csrf

            <label for="name">Имя</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >

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

            <button type="submit">Зарегистрироваться</button>
        </form>

        <style>
            .registration {
                max-width: 500px;
                margin: 0 auto;
            }

            .registration h1 {
                color: #243447;
            }

            .registration label {
                display: block;
                margin: 18px 0 7px;
                font-weight: bold;
            }

            .registration input {
                width: 100%;
                padding: 12px;
                border: 1px solid #ccd4dd;
                border-radius: 7px;
                font-size: 16px;
            }

            .registration button {
                margin-top: 24px;
                padding: 12px 18px;
                color: white;
                background: #2f6670;
                border: none;
                border-radius: 7px;
                cursor: pointer;
            }

            .registration button:hover {
                background: #244e57;
            }

            .errors {
                padding: 12px;
                color: #842029;
                background: #f8d7da;
                border-radius: 7px;
            }
        </style>
    </div>
@endsection
