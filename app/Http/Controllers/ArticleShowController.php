<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.articleShow', compact('article'));
    }
}
