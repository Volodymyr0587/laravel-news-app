<?php

namespace App\Http\Controllers;
use App\Models\Article;


class ArticleIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $articles = Article::with('tags')->orderBy('created_at', 'desc')->paginate(4);
        return view('articles.index', compact('articles'));
    }
}
