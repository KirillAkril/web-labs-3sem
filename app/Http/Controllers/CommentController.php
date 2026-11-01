<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{

    // Добавление комментария к статье

    public function store(Request $request, Article $article)
    {
        Gate::authorize('create-comment');

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $isModerator = $request->user()->role?->name === 'moderator';

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'is_approved' => $isModerator,
        ]);
        Cache::forget('article_' . $article->id);
        $message = $isModerator
            ? 'Комментарий опубликован.'
            : 'Комментарий отправлен и ожидает модерации.';

        return redirect()
            ->route('articles.show', $article)
            ->with('success', $message);
    }

    // Список комментариев, ожидающих модерации
    public function moderation()
    {
        Gate::authorize('moderate');

        $comments = Comment::with(['user', 'article'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(10);

        return view('comments.moderation', compact('comments'));
    }

    // Принятие комментария
    public function approve(Comment $comment)
    {
        Gate::authorize('moderate');

        $articleId = $comment->article_id;

        $comment->update(['is_approved' => true]);

        Cache::forget('article_' . $articleId);

        return redirect()
            ->route('comments.moderation')
            ->with('success', 'Комментарий одобрен.');
    }

    // Отклонение комментария
    public function reject(Comment $comment)
    {
        Gate::authorize('moderate');

        $comment->delete();

        return redirect()
            ->route('comments.moderation')
            ->with('success', 'Комментарий отклонён и удалён.');
    }
    public function edit(Comment $comment)
    {
        Gate::authorize('update-comment', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update-comment', $comment);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        Cache::forget('article_' . $comment->article_id);

        return redirect()
            ->route('articles.show', $comment->article_id)
            ->with('success', 'Комментарий успешно изменён.');
    }
    // Удаление комментария модератором
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete-comment', $comment);

        $article = $comment->article;

        $comment->delete();
        Cache::forget('article_' . $article->id);
        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Комментарий удалён.');
    }
}
