<?php

namespace App\Http\Middleware;

use App\Models\ArticleView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackArticleView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->isMethod('GET')
            && $request->route('article') !== null
            && $response->getStatusCode() === 200
        ) {
            $article = $request->route('article');

            ArticleView::create([
                'article_id' => $article->id,
                'url' => $request->fullUrl(),
            ]);
        }

        return $response;
    }
}
