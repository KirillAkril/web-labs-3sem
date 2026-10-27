<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    // Список статей с пагинацией
    public function index()
    {
        $articles = Article::latest()->paginate(6);

        return response()->json($articles);
    }

    // Просмотр одной статьи
    public function show(Article $article)
    {
        $article->setRelation(
            'comments',
            Comment::with('user')
                ->where('article_id', $article->id)
                ->where('is_approved', true)
                ->latest()
                ->get()
        );

        return response()->json($article);
    }

    // Создание статьи
    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
        ]);

        $article = Article::create($validated);

        Cache::flush();

        VeryLongJob::dispatch($article);

        $readers = User::whereHas('role', function ($query) {
            $query->where('name', 'reader');
        })->get();

        Notification::send(
            $readers,
            new NewArticleNotification($article)
        );

        return response()->json([
            'message' => 'Article created successfully.',
            'article' => $article,
        ], 201);
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

        Cache::flush();

        return response()->json([
            'message' => 'Article updated successfully.',
            'article' => $article->fresh(),
        ]);
    }

    // Удаление статьи
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        Cache::flush();

        return response()->json([
            'message' => 'Article deleted successfully.',
        ]);
    }
}
