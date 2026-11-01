@extends('layouts.app')

@section('title', 'Редактирование комментария')

@section('content')
    <h1>Редактирование комментария</h1>

    @if ($errors->any())
        <div class="form-errors">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('comments.update', $comment) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="content">Текст комментария:</label>

        <textarea
            id="content"
            name="content"
            rows="5"
            maxlength="2000"
            required>{{ old('content', $comment->content) }}</textarea>

        <button type="submit">Сохранить изменения</button>

        <a href="{{ route('articles.show', $comment->article_id) }}">
            Отмена
        </a>
    </form>

    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 700px;
        }

        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            font: inherit;
        }

        button {
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