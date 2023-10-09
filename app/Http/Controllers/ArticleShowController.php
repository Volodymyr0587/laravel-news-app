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
        $tags = explode(' ', $article->tags);
        // dd($tags);
        $realatedArticles = Article::whereHas('tags', function ($query) use ($tags) {
            foreach ($tags as $tag) {
                $query->orWhere('tags', 'LIKE', '%' .$tag . '%');
            }
        })->where('id', '<>', $id)->get();

        return view('articles.articleShow', compact('article', 'tags', 'realatedArticles'));
    }
}
