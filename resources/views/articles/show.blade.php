
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

        @can('update', $article)
            <p>
                <a href="{{ route('articles.edit', $article) }}">
                    Редактировать статью
                </a>
            </p>
        @endcan

        @can('delete', $article)
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
        @endcan
    </article>

    <hr>

    <section class="comments">
        <h2>Комментарии</h2>

        @forelse ($article->comments()->with('user')->latest()->get() as $comment)
            <div class="comment">
                <p>
                    <strong>{{ $comment->user->name }}</strong>
                    <span class="date">
                        {{ $comment->created_at->format('d.m.Y H:i') }}
                    </span>
                </p>

                <p>{{ $comment->content }}</p>

                @can('delete', $comment)
                    <form
                        action="{{ route('comments.destroy', $comment) }}"
                        method="POST"
                        onsubmit="return confirm('Удалить комментарий?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-button">
                            Удалить комментарий
                        </button>
                    </form>
                @endcan
            </div>
        @empty
            <p>Комментариев пока нет.</p>
        @endforelse

        @auth
            @can('create', [\App\Models\Comment::class, $article])
                <h3>Добавить комментарий</h3>

                <form
                    action="{{ route('comments.store', $article) }}"
                    method="POST"
                >
                    @csrf

                    <div>
                        <label for="content">Текст комментария:</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="4"
                            maxlength="2000"
                            required
                        >{{ old('content') }}</textarea>

                        @error('content')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit">Отправить комментарий</button>
                </form>
            @endcan
        @else
            <p>Чтобы оставить комментарий, войдите в аккаунт.</p>
        @endauth
    </section>

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

        .comments {
            margin-top: 30px;
        }

        .comment {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        textarea {
            display: block;
            width: 100%;
            max-width: 600px;
            margin: 8px 0 12px;
            padding: 10px;
            box-sizing: border-box;
        }

        .delete-button {
            padding: 10px 15px;
            color: white;
            background: #b83232;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }

        .error {
            color: #b83232;
        }
    </style>
@endsection
