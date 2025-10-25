<?php

namespace App\Livewire\Articles;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\ArticleLayout;
use Illuminate\Support\Facades\Auth;

class ArticleIndex extends Component
{
    use WithPagination;

    // Search & Filters
    public $search = '';
    public $category = '';
    public $tag = '';
    public $sort = 'newest';
    public $showAllArticles = false;

    // Layout
    public $layoutArticles = [];
    public $featuredArticles = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'tag' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function mount()
    {
        $this->loadLayoutArticles();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'category', 'tag', 'sort'])) {
            $this->resetPage();
        }
    }

    public function toggleShowAll()
    {
        $this->showAllArticles = !$this->showAllArticles;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->tag = '';
        $this->sort = 'newest';
        $this->resetPage();
    }

    private function loadLayoutArticles()
    {
        $layouts = ArticleLayout::with(['article.user', 'article.category'])
            ->where('is_active', true)
            ->orderBy('position')
            ->get()
            ->groupBy('type');
        
        // Converti in array mantenendo la struttura corretta
        $this->layoutArticles = [];
        foreach ($layouts as $type => $items) {
            $this->layoutArticles[$type] = $items->map(function($item) {
                return ['article' => $item->article];
            })->toArray();
        }

        $this->featuredArticles = Article::with(['user', 'category'])
            ->published()
            ->where('featured', true)
            ->withCount(['likes', 'comments'])
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();
    }

    public function getArticlesProperty()
    {
        return $this->getAllArticles();
    }

    private function getAllArticles()
    {
        $query = Article::with(['user', 'category', 'tags'])
            ->published()
            ->withCount(['likes', 'comments']);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        // Filter by category
        if ($this->category) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->category);
            });
        }

        // Filter by tag
        if ($this->tag) {
            $query->whereHas('tags', function($q) {
                $q->where('slug', $this->tag);
            });
        }

        // Sort
        switch ($this->sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('published_at', 'desc');
        }

        return $query->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return ArticleCategory::withCount('articles')
            ->orderBy('name')
            ->get();
    }

    public function getTagsProperty()
    {
        return ArticleTag::withCount('articles')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.articles.article-index')
            ->extends('layout.master')
            ->section('main-content');
    }
}
