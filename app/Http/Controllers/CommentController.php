<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Комментарий успешно добавлен!');
    }

    // Удаление комментария
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $article = $comment->article;

        $comment->delete();

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Комментарий удалён.');
    }
}
