
@extends('layouts.app')

@section('title', 'Редактирование статьи')

@section('content')
    <h1>Редактировать статью</h1>

    @if ($errors->any())
        <div class="form-errors">
            <strong>Проверь введённые данные:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.update', $article) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="title">Заголовок</label>
        <input
            id="title"
            type="text"
            name="title"
            value="{{ old('title', $article->title) }}"
            maxlength="255"
            required
        >

        <label for="description">Краткое описание</label>
        <textarea
            id="description"
            name="description"
            maxlength="1000"
            rows="3"
            required
        >{{ old('description', $article->description) }}</textarea>

        <label for="content">Полный текст</label>
        <textarea
            id="content"
            name="content"
            rows="8"
            required
        >{{ old('content', $article->content) }}</textarea>



        <button type="submit">Сохранить изменения</button>
        <a href="{{ route('articles.show', $article) }}">Отмена</a>
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
            padding: 15px;
            color: #a32121;
            background: #fff0f0;
            border-radius: 8px;
        }
    </style>
@endsection
