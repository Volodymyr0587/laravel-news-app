<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        if ($request->hasFile('photo')) {

            $data = $request->validated();
            $data['photo'] = Storage::putFile('articles', $request->file('photo'));
            $data['user_id'] = auth()->id();
            $data['tags'] = preg_replace('/\s+/', ' ', trim(strtolower($request->tags)));
            $data['active'] = $request->active ? 1 : 0;

            $article = Article::create($data);

            // $article->tags()->attach($request->tags);

            // Перевіряємо, чи є унікальні теги
            $uniqueTags = $article->checkUniqueTags();
            // dd($uniqueTags);
            // Якщо є унікальні теги, то видаємо повідомлення
            if (!empty($uniqueTags)) {
                return to_route('admin.index')->with('tag_message', $uniqueTags);
            }

            // Додаємо посилання на інші статті
            $article->addLinks();
            $article->removeLinks();
            $article->save();

            return to_route('admin.index');
        } else {
            return back();
        }
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
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            Storage::delete($article->photo);
            $data['photo'] = Storage::putFile('atricles', $request->file('photo'));
        }

        $data['active'] = $request->active ? 1 : 0;

        $article->update($data);


        // $article->tags()->attach($request->tags);

        // Перевіряємо, чи є унікальні теги
        $uniqueTags = $article->checkUniqueTags();
        // dd($uniqueTags);
        // Якщо є унікальні теги, то видаємо повідомлення
        if (!empty($uniqueTags)) {
            return to_route('admin.index')->with('tag_message', $uniqueTags);
        }

        // Додаємо посилання на інші статті
        $article->addLinks();
        $article->removeLinks();


        return to_route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Storage::delete($article->photo);
        $article->tags()->detach();
        $article->delete();

        return to_route('admin.index');
    }
}
