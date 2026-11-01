<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function create(User $user, Article $article): bool
    {
        return in_array(
            $user->role?->name,
            ['reader', 'moderator'],
            true
        );
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->role?->name === 'moderator'
            || $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->role?->name === 'moderator'
            || $comment->user_id === $user->id;
    }

    public function moderate(User $user): bool
    {
        return $user->role?->name === 'moderator';
    }
}