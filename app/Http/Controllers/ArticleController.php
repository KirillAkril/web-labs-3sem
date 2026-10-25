<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    // Список статей с пагинацией
    public function index()
    {
        $articles = Article::latest()->paginate(6);

        return view('articles.index', compact('articles'));
    }

    // Форма создания статьи
    public function create()
    {
        $this->authorize('create', Article::class);

        return view('articles.create');
    }

    // Сохранение новой статьи
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
        ]);

        Article::create($validated);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья успешно создана!');
    }

    // Просмотр одной статьи
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    // Форма редактирования
    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    // Обновление статьи
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
        ]);

        $article->update($validated);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья успешно обновлена!');
    }

    // Удаление статьи
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья удалена.');
    }
}
