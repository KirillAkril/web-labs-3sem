
<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/about', 'about')->name('about');

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
