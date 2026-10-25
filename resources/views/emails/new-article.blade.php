<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая статья</title>
</head>
<body>
    <h1>Добавлена новая статья</h1>

    <h2>{{ $article->title }}</h2>

    <p>{{ $article->description }}</p>

    <hr>

    <p>{{ $article->content }}</p>

    <p>
        Дата создания:
        {{ $article->created_at?->format('d.m.Y H:i') ?? 'Не указана' }}
    </p>
</body>
</html>
