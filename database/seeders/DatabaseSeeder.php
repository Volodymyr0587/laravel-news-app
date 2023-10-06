<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Tag::create(['name' => 'Laravel PHP VueJS', 'slug' => 'laravel']);
        Article::create(['title' => 'First News', 'photo' => 'path/to/photo', 'content' => 'News Content ...', 'active' => 1, 'tags' => 'tag1 tag2']);
        Article::create(['title' => 'Second News', 'photo' => 'path/to/photo', 'content' => 'News Content ...', 'active' => 0, 'tags' => 'tag2 tag3']);
        Article::create(['title' => 'Third News', 'photo' => 'path/to/photo', 'content' => 'News Content ...', 'active' => 1, 'tags' => 'tag3 tag4']);
        Article::create(['title' => 'Fourth News', 'photo' => 'path/to/photo', 'content' => 'News Content ...', 'active' => 0, 'tags' => 'tag2 tag1']);

    }
}
