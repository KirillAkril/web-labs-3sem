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
use App\Events\NewArticleEvent;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    // Список статей с пагинацией


    public function index(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));

        $articleIds = Cache::remember(
            'articles_page_ids_' . $page,
            60,
            function () use ($page) {
                return Article::latest()
                    ->paginate(6, ['id'], 'page', $page)
                    ->toArray();
            }
        );

        $paginatorData = $articleIds;

        $ids = collect($paginatorData['data'])->pluck('id')->all();

        $articles = Article::whereIn('id', $ids)
            ->orderByRaw('CASE id ' .
                implode(' ', array_map(
                    fn($id, $index) => "WHEN {$id} THEN {$index}",
                    $ids,
                    array_keys($ids)
                )) .
                ' END')
            ->get();

        $articles = new \Illuminate\Pagination\LengthAwarePaginator(
            $articles,
            $paginatorData['total'],
            $paginatorData['per_page'],
            $paginatorData['current_page'],
            [
                'path' => route('articles.index'),
                'pageName' => 'page',
            ]
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
        // Отправляем событие о создании статьи через Pusher
        event(new NewArticleEvent(
            $article->id,
            $article->title
        ));
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
        $articleData = Cache::rememberForever(
            'article_data_' . $article->id,
            function () use ($article) {
                return Article::findOrFail($article->id)->getAttributes();
            }
        );

        $article = new Article();

        $article->setRawAttributes($articleData, true);
        $article->exists = true;

        $article->setRelation(
            'comments',
            \App\Models\Comment::with('user')
                ->where('article_id', $article->id)
                ->where('is_approved', true)
                ->latest()
                ->get()
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
