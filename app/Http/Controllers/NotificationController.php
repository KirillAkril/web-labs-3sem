<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show(Request $request, string $id)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $articleId = $notification->data['article_id'] ?? null;

        $notification->markAsRead();

        if (!$articleId) {
            return redirect()->route('articles.index');
        }

        return redirect()->route('articles.show', $articleId);
    }
}
