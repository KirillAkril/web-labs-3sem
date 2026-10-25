
@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="articles-header">
        <span class="section-label">НАША КОЛЛЕКЦИЯ</span>
        <h1>Интересные статьи</h1>
        <p>Подборка материалов, которые стоит посмотреть и прочитать.</p>
    </div>

    <div class="articles-grid">
        @foreach ($articles as $index => $article)
            <article class="article-card">
                <a
                    class="article-image-link"
                    href="{{ route('gallery', ['id' => $index]) }}"
                >
                    <img
                        src="{{ asset($article['preview_image']) }}"
                        alt="{{ $article['Название'] }}"
                        class="article-image"
                    >

                    <span class="image-label">Посмотреть</span>
                </a>

                <div class="article-content">
                    <p class="article-date">
                        {{ $article['Дата'] }}
                    </p>

                    <h2>{{ $article['Название'] }}</h2>

                    <p class="article-description">
                        {{ $article['Краткое описание'] }}
                    </p>

                    <a
                        class="article-button"
                        href="{{ route('gallery', ['id' => $index]) }}"
                    >
                        Читать статью
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <style>
        .articles-header {
            margin-bottom: 30px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e4e8ee;
        }

        .section-label {
            display: inline-block;
            margin-bottom: 10px;
            color: #477b83;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .articles-header h1 {
            margin: 0 0 12px;
            color: #243447;
            font-size: clamp(28px, 4vw, 38px);
        }

        .articles-header > p {
            margin: 0;
            color: #687586;
            line-height: 1.6;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 26px;
            align-items: stretch;
        }

        .article-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e1e7ed;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(36, 52, 71, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(36, 52, 71, 0.12);
        }

        .article-image-link {
            position: relative;
            display: block;
            overflow: hidden;
            background: #e9eef2;
        }

        .article-image {
            display: block;
            width: 100%;
            height: 230px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .article-card:hover .article-image {
            transform: scale(1.04);
        }

        .image-label {
            position: absolute;
            right: 12px;
            bottom: 12px;
            padding: 7px 11px;
            color: #ffffff;
            background: rgba(36, 52, 71, 0.88);
            border-radius: 6px;
            font-size: 12px;
        }

        .article-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            align-items: flex-start;
            padding: 22px;
        }

        .article-date {
            margin: 0 0 10px;
            color: #718096;
            font-size: 13px;
        }

        .article-content h2 {
            margin: 0 0 12px;
            color: #243447;
            font-size: 21px;
            line-height: 1.4;
        }

        .article-description {
            margin: 0 0 22px;
            color: #566273;
            font-size: 15px;
            line-height: 1.7;
        }

        .article-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: auto;
            padding: 11px 15px;
            color: #ffffff;
            background: #2f6670;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .article-button:hover {
            color: #ffffff;
            background: #244e57;
        }

        @media (max-width: 700px) {
            .articles-grid {
                grid-template-columns: 1fr;
            }

            .article-image {
                height: 220px;
            }

            .article-content {
                padding: 18px;
            }
        }
    </style>
@endsection
