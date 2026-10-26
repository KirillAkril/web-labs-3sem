<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    use AuthorizesRequests;

    // Добавление комментария к статье

    public function store(Request $request, Article $article)
    {
        $this->authorize('create', [Comment::class, $article]);

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
        $this->authorize('moderate', Comment::class);

        $comments = Comment::with(['user', 'article'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(10);

        return view('comments.moderation', compact('comments'));
    }

    // Принятие комментария
    public function approve(Comment $comment)
    {
        $this->authorize('moderate', Comment::class);

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
        $this->authorize('moderate', Comment::class);

        $comment->delete();

        return redirect()
            ->route('comments.moderation')
            ->with('success', 'Комментарий отклонён и удалён.');
    }

    // Удаление комментария модератором
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $article = $comment->article;

        $comment->delete();
        Cache::forget('article_' . $article->id);
        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Комментарий удалён.');
    }
}
