<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Article $article): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role?->name === 'moderator';
    }

    public function update(User $user, Article $article): bool
    {
        return $user->role?->name === 'moderator';
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->role?->name === 'moderator';
    }

    public function restore(User $user, Article $article): bool
    {
        return $user->role?->name === 'moderator';
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->role?->name === 'moderator';
    }
}
