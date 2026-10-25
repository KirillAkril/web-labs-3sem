<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
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