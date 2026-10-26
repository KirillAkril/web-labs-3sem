<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])
    ->name('home');

Route::get('/gallery/{id}', [MainController::class, 'gallery'])
    ->whereNumber('id')
    ->name('gallery');

Route::view('/about', 'about')
    ->name('about');

Route::view('/contacts', 'contacts', [
    'contacts' => [
        [
            'name' => 'Электронная почта',
            'value' => 'example@mail.com',
        ],
        [
            'name' => 'Телефон',
            'value' => '+7 (999) 990-67-67',
        ],
        [
            'name' => 'Адрес',
            'value' => 'Ул. Пушкина, д. 67',
        ],
    ],
])->name('contacts');

Route::get('/signin', [AuthController::class, 'create'])
    ->name('signin.form');

Route::post('/signin', [AuthController::class, 'registration'])
    ->name('signin');

Route::get('/login', [AuthController::class, 'loginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::resource('articles', ArticleController::class)
    ->except(['index', 'show'])
    ->middleware('auth:sanctum');

Route::resource('articles', ArticleController::class)
    ->only(['index', 'show']);

Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('comments.store');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('comments.destroy');

Route::get('/comments/moderation', [CommentController::class, 'moderation'])
    ->middleware('auth:sanctum')
    ->name('comments.moderation');

Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve'])
    ->middleware('auth:sanctum')
    ->name('comments.approve');

Route::delete('/comments/{comment}/reject', [CommentController::class, 'reject'])
    ->middleware('auth:sanctum')
    ->name('comments.reject');

Route::get('/notifications/unread', function (\Illuminate\Http\Request $request) {
    $notifications = $request->user()
        ->unreadNotifications()
        ->latest()
        ->get();

    return response()->json([
        'count' => $notifications->count(),
        'notifications' => $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Новая статья',
                'url' => route('notifications.show', $notification->id),
                'created_at' => $notification->created_at->format('d.m.Y H:i'),
            ];
        }),
    ]);
})->middleware('auth')->name('notifications.unread');
Route::get('/notifications/{id}', [
    \App\Http\Controllers\NotificationController::class,
    'show',
])->middleware('auth:sanctum')->name('notifications.show');