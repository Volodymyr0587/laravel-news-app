<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'photo', 'content', 'active', 'tags',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // A method for checking unique tags
    public function checkUniqueTags()
    {
        // get all the tags in the database
        $allTags = Article::pluck('tags')->flatten()->unique()->toArray();
        // dd($allTags);
        // get the tags of the current article
        $tags = explode(' ', $this->tags);
        // dd($tags);

        // compare the tags of the current article with all tags in the database
        $uniqueTags = array_diff($tags, $allTags);
        dd($uniqueTags);

        // If there are unique tags, we issue a message
        if (!empty($uniqueTags)) {
            return $uniqueTags;
        }

        // If all tags are unique, then returns an empty array
        return [];
    }

    // A method for adding links to other articles
    public function addLinks()
    {
        //  get all the tags in the database
        $allTags = Article::pluck('tags')->flatten();
        // dd($allTags);
        // get the text of the current article
        $content = $this->content;

        // go through all the tags in the database
        foreach ($allTags as $tag) {
            // If the tag is present in the text of the current article
            if (str_contains($content, $tag)) {
                // add a link to an article with this tag
                $content = str_replace($tag, '<a href="/articles/' . $this->id . '">' . $tag . '</a>', $content);
            }
        }

        // install the new text of the article
        $this->content = $content;
        $this->save();
    }

    // A method for removing links to other articles
    public function removeLinks()
    {
        // get all the tags in the database
        $allTags = Article::pluck('tags')->flatten();

        // get the text of the current article
        $content = $this->content;

        // go through all the tags in the database
        foreach ($allTags as $tag) {
            // replace the link with an ordinary word
            $content = str_replace('<a href="/articles/' . $this->id . '">' . $tag . '</a>', $tag, $content);
        }

        //  install the new text of the article
        $this->content = $content;
        $this->save();
    }
}
