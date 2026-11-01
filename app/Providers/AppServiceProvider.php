<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        // Просмотр списка статей
        Gate::define('view-articles', function (User $user): bool {
            return $user->role?->name === 'reader'
                || $user->role?->name === 'moderator';
        });

        // Просмотр отдельной статьи
        Gate::define('view-article', function (User $user): bool {
            return $user->role?->name === 'reader'
                || $user->role?->name === 'moderator';
        });

        // Добавление комментариев
        Gate::define('create-comment', function (User $user): bool {
            return $user->role?->name === 'reader'
                || $user->role?->name === 'moderator';
        });

        // Редактирование комментариев
        Gate::define('update-comment', function (User $user, \App\Models\Comment $comment): bool {
            return $user->role?->name === 'moderator'
                || $comment->user_id === $user->id;
        });

        // Удаление комментариев
        Gate::define('delete-comment', function (User $user, \App\Models\Comment $comment): bool {
            return $user->role?->name === 'moderator'
                || $comment->user_id === $user->id;
        });

        // Модерация комментариев
        Gate::define('moderate', function (User $user): bool {
            return $user->role?->name === 'moderator';
        });
    }
}
