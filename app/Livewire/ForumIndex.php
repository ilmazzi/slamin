<?php

namespace App\Livewire;

use App\Models\Subreddit;
use App\Models\ForumPost;
use Livewire\Component;
use Livewire\WithPagination;

class ForumIndex extends Component
{
    use WithPagination;

    public $sortBy = 'hot'; // hot, new, top
    public $timeframe = 'all'; // all, today, week, month, year
    public $search = '';

    protected $queryString = [
        'sortBy' => ['except' => 'hot'],
        'timeframe' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function setTimeframe($time)
    {
        $this->timeframe = $time;
        $this->resetPage();
    }

    public function render()
    {
        // Get popular subreddits
        $popularSubreddits = Subreddit::active()
            ->public()
            ->popular()
            ->take(10)
            ->get();

        // Get posts based on sort
        $query = ForumPost::with(['subreddit', 'user'])
            ->whereHas('subreddit', function ($q) {
                $q->active()->public();
            })
            ->approved();

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'new':
                $query->new();
                break;
            case 'top':
                $query->top($this->timeframe);
                break;
            case 'hot':
            default:
                $query->hot();
                break;
        }

        $posts = $query->paginate(20);

        return view('livewire.forum-index', [
            'posts' => $posts,
            'popularSubreddits' => $popularSubreddits,
        ]);
    }
}
