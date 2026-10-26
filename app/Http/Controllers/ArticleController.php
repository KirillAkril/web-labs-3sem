<?php

namespace App\Http\Controllers;

use App\Jobs\VeryLongJob;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Mail\NewArticleMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewArticleNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    // Список статей с пагинацией
    public function index(Request $request)
    {
        $page = $request->query('page', 1);

        $articles = Cache::remember(
            'articles_page_' . $page,
            60,
            function () {
                return Article::latest()->paginate(6);
            }
        );

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

        // Сохраняем новую статью в базе данных
        $article = Article::create($validated);
        Cache::forget('articles_page_1');
        // Добавляем отправку письма в очередь
        VeryLongJob::dispatch($article);
        // Находим всех пользователей с ролью читателя
        $readers = User::whereHas('role', function ($query) {
            $query->where('name', 'reader');
        })->get();

        // Отправляем читателям уведомления о новой статье
        Notification::send(
            $readers,
            new NewArticleNotification($article)
        );
        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья успешно создана!');
    }

    // Просмотр одной статьи
    public function show(Article $article)
    {
        $article = Cache::rememberForever(
            'article_' . $article->id,
            function () use ($article) {
                return Article::with([
                    'comments' => function ($query) {
                        $query->with('user')
                            ->where('is_approved', true)
                            ->latest();
                    },
                ])->findOrFail($article->id);
            }
        );

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
        Cache::flush();
        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья успешно обновлена!');
    }

    // Удаление статьи
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();
        Cache::flush();
        return redirect()
            ->route('articles.index')
            ->with('success', 'Статья удалена.');
    }
}
