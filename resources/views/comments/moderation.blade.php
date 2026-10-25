
@extends('layouts.app')

@section('title', 'Модерация комментариев')

@section('content')
    <h1>Модерация комментариев</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @forelse ($comments as $comment)
        <article class="moderation-comment">
            <h3>{{ $comment->article->title }}</h3>

            <p>
                Автор: {{ $comment->user->name }}
            </p>

            <p>
                Дата: {{ $comment->created_at->format('d.m.Y H:i') }}
            </p>

            <p>{{ $comment->content }}</p>

            <div class="actions">
                <form
                    action="{{ route('comments.approve', $comment) }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="approve">
                        Принять
                    </button>
                </form>

                <form
                    action="{{ route('comments.reject', $comment) }}"
                    method="POST"
                    onsubmit="return confirm('Отклонить и удалить комментарий?')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="reject">
                        Отклонить
                    </button>
                </form>
            </div>
        </article>
    @empty
        <p>Нет комментариев, ожидающих модерации.</p>
    @endforelse

    {{ $comments->links() }}

    <style>
        .moderation-comment {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .approve,
        .reject {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
        }

        .approve {
            background: #27864b;
        }

        .reject {
            background: #b83232;
        }

        .success {
            color: #27864b;
        }
    </style>
@endsection
