<?php

namespace App\Livewire\Articles;

use Livewire\Component;
use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Support\Facades\Auth;

class ArticleShow extends Component
{
    public Article $article;
    public $relatedArticles = [];

    public function mount(Article $article)
    {
        $this->article = $article;
        $this->loadRelatedArticles();
        $this->incrementViews();
    }

    private function loadRelatedArticles()
    {
        $this->relatedArticles = Article::with(['user', 'category'])
            ->published()
            ->where('id', '!=', $this->article->id)
            ->where(function($query) {
                // Same category
                $query->where('category_id', $this->article->category_id)
                      // Or same tags
                      ->orWhereHas('tags', function($q) {
                          $q->whereIn('article_tag_id', $this->article->tags->pluck('id'));
                      });
            })
            ->withCount(['likes', 'comments'])
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();
    }

    private function incrementViews()
    {
        if (!Auth::check() || Auth::id() !== $this->article->user_id) {
            $this->article->increment('views_count');
        }
    }

    public function render()
    {
        return view('livewire.articles.article-show')
            ->extends('layout.master')
            ->section('main-content');
    }
}
