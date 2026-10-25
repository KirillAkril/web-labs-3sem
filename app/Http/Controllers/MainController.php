<?php
namespace App\Http\Controllers;
class MainController extends Controller
{
    public function index()
    {
        $articles = json_decode(
            file_get_contents(public_path('articles.json')),
            true
        );

        return view('home', [
            'articles' => $articles
        ]);
    }

    public function gallery($id)
    {
        $articles = json_decode(
            file_get_contents(public_path('articles.json')),
            true
        );

        if (!isset($articles[$id])) {
            abort(404);
        }

        return view('gallery', [
            'article' => $articles[$id]
        ]);
    }
}
