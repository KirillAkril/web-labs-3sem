
@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <p><a href="{{ route('articles.index') }}">← Назад к статьям</a></p>

    <article>
        <h1>{{ $article->title }}</h1>

        <p class="date">
            Создано: {{ $article->created_at->format('d.m.Y H:i') }}
        </p>

        <p>{{ $article->description }}</p>

        @if ($article->image)
            <img
                src="{{ asset($article->image) }}"
                alt="{{ $article->title }}"
                style="max-width: 100%; height: auto;"
            >
        @endif

        <div class="article-content">
            {{ $article->content }}
        </div>

        <p>
            <a href="{{ route('articles.edit', $article) }}">
                Редактировать статью
            </a>
        </p>

        <form
            action="{{ route('articles.destroy', $article) }}"
            method="POST"
            onsubmit="return confirm('Точно удалить эту статью?')"
        >
            @csrf
            @method('DELETE')

            <button type="submit" class="delete-button">
                Удалить статью
            </button>
        </form>
    </article>

    <style>
        .date {
            color: #718096;
            font-size: 14px;
        }

        .article-content {
            margin: 25px 0;
            line-height: 1.8;
            white-space: pre-line;
        }

        .delete-button {
            padding: 10px 15px;
            color: white;
            background: #b83232;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }
    </style>
@endsection
