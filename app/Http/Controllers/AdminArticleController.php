<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
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
        // dd('NEW ARTICLE');
        // $tags = Tag::all();
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
            // Перевірка та оновлення інших статей
            // foreach (explode(' ', $validatedData['tags']) as $tag) {
            //     $existingArticle = Article::whereHas('tags', function ($query) use ($tag) {
            //         $query->where('name', $tag);
            //     })->get();
            //     // dd($existingArticle);
            //     foreach ($existingArticle as $existing) {
            //         if (!in_array($tag, explode(' ', $existing->tags))) {
            //             $existing->tags()->attach(Tag::find($tag));
            //         }
            //     }
            // }
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



            // $this->processTags($article, explode(" ", $data['tags']));
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
        $article->tags()->detach($article->tags);
        Storage::delete($article->photo);

        $article->delete();

        return to_route('admin.index');
    }
}
