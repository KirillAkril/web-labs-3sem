<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();

        return view('articles.index', [
            'articles' => $articles,
        ]);
    }
}
