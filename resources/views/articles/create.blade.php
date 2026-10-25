
@extends('layouts.app')

@section('title', 'Создание статьи')

@section('content')
    <h1>Создать статью</h1>

    @if ($errors->any())
        <div class="form-errors">
            <strong>Проверьте введённые данные:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.store') }}" method="POST">
        @csrf

        <label for="title">Заголовок</label>
        <input
            id="title"
            type="text"
            name="title"
            value="{{ old('title') }}"
            required
            maxlength="255"
        >

        <label for="description">Краткое описание</label>
        <textarea
            id="description"
            name="description"
            required
            maxlength="1000"
            rows="3"
        >{{ old('description') }}</textarea>

        <label for="content">Полный текст</label>
        <textarea
            id="content"
            name="content"
            required
            rows="8"
        >{{ old('content') }}</textarea>


        <button type="submit">Сохранить статью</button>
        <a href="{{ route('articles.index') }}">Отмена</a>
    </form>

    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 700px;
        }

        form input,
        form textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            font: inherit;
        }

        form button {
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            background: #243447;
            color: white;
            cursor: pointer;
        }

        .form-errors {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #fff0f0;
            color: #a32121;
        }
    </style>
@endsection
