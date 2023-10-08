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

    // public function store(StoreArticleRequest $request)
    // {
    //     if ($request->hasFile('photo')) {

    //         $data = $request->validated();
    //         $data['photo'] = Storage::putFile('articles', $request->file('photo'));
    //         $data['user_id'] = auth()->id();
    //         $data['tags'] = preg_replace('/\s+/', ' ', trim(strtolower($request->tags)));
    //         $data['active'] = $request->active ? 1 : 0;

    //         $article = Article::create($data);

    //         $tags = explode(' ', $data['tags']);

    //         // Перевіряємо чи існують теги в інших статтях
    //         $uniqueTags = array_filter($tags, function ($tag) use ($article) {
    //             return !$article->tags()->where('name', $tag)->exists();
    //         });
    //         // dd($uniqueTags);

    //         // Додаємо теги до статті
    //         foreach ($uniqueTags as $tag) {
    //             $tag = Tag::where('name', $tag)->first();
    //             if ($tag) {
    //                 $article->tags()->attach($tag->id);
    //             }

    //         }

    //         // Оновлюємо всі статті, де зустрічаються ці теги
    //         $this->updateArticles($tags);

    //         return to_route('admin.index');
    //     }

    //     return back();
    // }


    public function store(StoreArticleRequest $request)
    {
        DB::beginTransaction();

        if ($request->hasFile('photo')) {

            $data = $request->validated();
            $data['photo'] = Storage::putFile('articles', $request->file('photo'));
            $data['user_id'] = auth()->id();
            $data['tags'] = preg_replace('/\s+/', ' ', trim(strtolower($request->tags)));
            $data['active'] = $request->active ? 1 : 0;

            $article = Article::create($data);


            $tags = explode(',', $request->input('tags'));

            // Перевіряємо чи існують теги в інших статтях
            $uniqueTags = array_filter($tags, function ($tag) use ($article) {
                return !$article->tags()->where('name', $tag)->exists();
            });
            // Проверяем, существуют ли эти теги в таблице `tags`
            $existingTags = Tag::whereIn('name', $tags)->pluck('id')->toArray();

            // Добавляем теги к статье
            foreach ($uniqueTags as $tag) {
                if (!in_array($tag, $existingTags)) {
                    $tag = Tag::create(['name' => $tag, 'slug' => strtolower($tag)]);
                    $existingTags[] = $tag->id;
                }

                $article->tags()->attach($existingTags);
            }

            // Обновляем все статьи, где встречаются эти теги
            $this->updateArticles($tags);

            DB::commit();

            return to_route('admin.index');
        }
        return back();
    }

    // Оновлюємо всі статті, де зустрічаються ці теги
    private function updateArticles($tags)
    {
        $articles = Article::all();

        foreach ($articles as $article) {
            // Перевіряємо, чи зустрічаються в тексті статті ці теги
            $articlesTags = $this->getArticleTags($article->content, $tags);
            // dd($articlesTags);
            // Додаємо посилання на статті в тексті
            foreach ($articlesTags as $tag) {
                $article->content = str_replace($tag, '<a href="/articles/' . $tag . '">' . $tag . '</a>', $article->content);
            }

            // Зберігаємо статтю
            $article->save();
        }
    }

    // Отримуємо теги з текста статті
    private function getArticleTags($content, $tags)
    {
        $articleTags = [];

        foreach ($tags as $tag) {
            if (str_contains($content, $tag)) {
                $tag = Tag::where('name', $tag)->first();
                if ($tag) {
                    $articleTags[] = $tag;
                }
            }
        }

        return $articleTags;
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
        Storage::delete($article->photo);
        $article->tags()->detach($article->tags->pluck('id'));
        $article->delete();

        return to_route('admin.index');
    }
}
