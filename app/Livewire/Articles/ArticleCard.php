<?php

namespace App\Livewire\Articles;

use Livewire\Component;
use App\Models\Article;

class ArticleCard extends Component
{
    public Article $article;

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.articles.article-card');
    }
}
