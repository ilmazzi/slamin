<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Article;

class ArticlesSection extends Component
{
    public $contentType = 'new'; // 'new' o 'popular'

    public function toggleContent($type)
    {
        $this->contentType = $type;
    }

    public function render()
    {
        if ($this->contentType === 'popular') {
            $articles = Article::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->withCount(['views', 'likes', 'comments'])
                ->orderBy('views_count', 'desc')
                ->limit(6)
                ->get();
        } else {
            $articles = Article::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->withCount(['views', 'likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }
        
        return view('livewire.home.articles-section', [
            'articles' => $articles
        ]);
    }
}
