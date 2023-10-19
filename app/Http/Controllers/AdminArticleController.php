<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;


class AdminArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $articles = Article::with('tags')->orderBy('created_at', 'desc')->get();

        return view('admin.index', compact('articles'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create'); //, compact('tags'));
    }

    public function store(StoreArticleRequest $request)
    {
        if ($request->hasFile('photo')) {
            $validatedData = $request->validated();
            $validatedData['photo'] = Storage::putFile('articles', $request->file('photo'));
            $validatedData['user_id'] = auth()->id();
            $validatedData['active'] = $request->active ? 1 : 0;

            $article = Article::create($validatedData);

            foreach (explode(' ', $validatedData['tags']) as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName, 'slug' => $tagName]);
                $article->tags()->attach($tag->id);
            }

            $tags = $article->tags->pluck('name')->all();
            // dd($tags);

            $relatedArticles = Article::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })->whereNotIn('id', [$article->id])->get();
            // dd($relatedArticles);

            foreach ($relatedArticles as $relatedArticle) {
                $content = $relatedArticle->content;

                foreach ($tags as $tag) {
                    $tagLink = route('articleShow', $article->id);
                    // $content = preg_replace($pattern, "<a href='$tagLink' class='text-blue-500 underline'>$tag</a>", $content);
                    $content = str_replace($tag, "<a href='$tagLink' class='text-blue-500 underline'>$tag</a>", $content);

                }
                $relatedArticle->content = $content;

                $relatedArticle->save();

                $article->content = $relatedArticle->content;

            }
            return to_route('admin.index');
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $tags = Tag::all();
        return view('admin.edit', compact('article', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        if ($request->hasFile('photo')) {

            $data = $request->validated();
            $data['photo'] = Storage::putFile('articles', $request->file('photo'));
            $data['user_id'] = auth()->id();
            $data['tags'] = preg_replace('/\s+/', ' ', trim(strtolower($request->tags)));
            $data['active'] = $request->active ? 1 : 0;

            $article->update($data);

            return to_route('admin.index');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->tags()->sync([]);

        Storage::delete($article->photo);

        $article->delete();

        return to_route('admin.index');
    }
}
