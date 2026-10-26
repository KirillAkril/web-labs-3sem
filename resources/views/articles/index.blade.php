@extends('layouts.app')

@section('title', 'Новости')

@section('content')
<h1>Новости и статьи</h1>
@can('create', \App\Models\Article::class)
    <p>
        <a href="{{ route('articles.create') }}">+ Добавить статью</a>
    </p>
@endcan

@if (session('success'))
<p class="success-message">{{ session('success') }}</p>
@endif
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
            alt="{{ $article->title }}">
        @endif


        <div class="article-actions">
            <a href="{{ route('articles.show', $article) }}">
                Открыть
            </a>

            @can('update', $article)
            <a href="{{ route('articles.edit', $article) }}">
                Редактировать
            </a>
            @endcan

            @can('delete', $article)
            <form
                action="{{ route('articles.destroy', $article) }}"
                method="POST"
                onsubmit="return confirm('Точно удалить эту статью?')">
                @csrf
                @method('DELETE')

                <button type="submit">Удалить</button>
            </form>
            @endcan
        </div>

    </article>
    @endforeach
</div>
@endif
<div class="pagination">
    {{ $articles->links() }}
</div>
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

    .pagination {
        margin-top: 28px;
        display: flex;
        justify-content: center;
    }

    .pagination nav {
        display: flex;
        justify-content: center;
    }

    /* Исправление пагинации Laravel */
    .pagination nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination nav>div:first-child {
        display: none;
    }

    .pagination nav>div:last-child {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination nav a,
    .pagination nav span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        min-height: 40px;
        padding: 8px 12px;
        border: 1px solid #e1e7ed;
        border-radius: 8px;
        background: #fff;
        color: #243447;
        text-decoration: none;
        font-size: 14px;
        line-height: 1.2;
    }

    .pagination nav a:hover {
        background: #eaf4f4;
    }

    .pagination nav svg {
        width: 16px;
        height: 16px;
        max-width: 16px;
        max-height: 16px;
    }

    .pagination nav p {
        margin: 0;
        font-size: 14px;
    }

    /* Строка с количеством статей — под пагинацией */
    .pagination nav {
        flex-direction: column;
    }

    .pagination nav>div:last-child {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .pagination nav>div:last-child>div:first-child {
        order: 2;
        text-align: center;
    }

    .pagination nav>div:last-child>div:last-child {
        order: 1;
    }

    .article-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-top: 18px;
    }

    .article-actions a,
    .article-actions button {
        padding: 9px 12px;
        border: 0;
        border-radius: 7px;
        background: #243447;
        color: white;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
    }

    .article-actions form {
        margin: 0;
    }

    .article-actions button {
        background: #b83232;
    }

    @media (max-width: 700px) {
        .news-list {
            grid-template-columns: 1fr;
        }


    }
</style>
@endsection