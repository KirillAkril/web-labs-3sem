<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    // Читатель и модератор могут добавлять комментарии
    public function create(User $user, Article $article): bool
    {
        return in_array(
            $user->role?->name,
            ['reader', 'moderator'],
            true
        );
    }

    // Модератор может удалять любые комментарии
    public function delete(User $user, Comment $comment): bool
    {
        return $user->role?->name === 'moderator';
    }

    // Модератор может изменять любые комментарии
    public function update(User $user, Comment $comment): bool
    {
        return $user->role?->name === 'moderator';
    }
    public function moderate(User $user): bool
    {
        return $user->role?->name === 'moderator';
    }
}
