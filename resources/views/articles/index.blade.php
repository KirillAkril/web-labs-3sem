
@extends('layouts.app')

@section('title', 'Новости')

@section('content')
    <h1>Новости и статьи</h1>
    <p>Материалы загружаются из базы данных.</p>

    @if ($articles->isEmpty())
        <p>Пока статей нет.</p>
    @else
        <div class="news-list">
            @foreach ($articles as $article)
                <article class="news-card">
                    <p class="news-date">
                        {{ $article->created_at->format('d.m.Y') }}
                    </p>

                    <h2>{{ $article->title }}</h2>

                    <p>{{ $article->description }}</p>

                    @if ($article->image)
                        <img
                            src="{{ asset($article->image) }}"
                            alt="{{ $article->title }}"
                        >
                    @endif

                    <details>
                        <summary>Читать полностью</summary>
                        <p>{{ $article->content }}</p>
                    </details>
                </article>
            @endforeach
        </div>
    @endif

    <style>
        .news-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            margin-top: 25px;
        }

        .news-card {
            padding: 24px;
            background: #fff;
            border: 1px solid #e1e7ed;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(36, 52, 71, .06);
        }

        .news-card h2 {
            color: #243447;
        }

        .news-date {
            color: #718096;
            font-size: 13px;
        }

        .news-card img {
            display: block;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .news-card details {
            margin-top: 15px;
        }

        .news-card summary {
            color: #2f6670;
            cursor: pointer;
            font-weight: bold;
        }

        @media (max-width: 700px) {
            .news-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
