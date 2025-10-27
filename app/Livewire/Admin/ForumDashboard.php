<?php

namespace App\Livewire\Admin;

use App\Models\Subreddit;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumVote;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ForumDashboard extends Component
{
    public $stats = [];
    public $recentPosts = [];
    public $topSubreddits = [];
    public $recentActivity = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentPosts();
        $this->loadTopSubreddits();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_subreddits' => Subreddit::count(),
            'active_subreddits' => Subreddit::where('is_active', true)->count(),
            'total_posts' => ForumPost::count(),
            'posts_today' => ForumPost::whereDate('created_at', today())->count(),
            'total_votes' => ForumVote::count(),
            'unique_contributors' => DB::table('forum_posts')
                ->distinct('user_id')
                ->count('user_id'),
            'pending_posts' => ForumPost::whereNull('approved_at')->count(),
        ];
    }

    public function loadRecentPosts()
    {
        $this->recentPosts = ForumPost::with(['user', 'subreddit'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function loadTopSubreddits()
    {
        $this->topSubreddits = Subreddit::orderBy('subscribers_count', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.forum-dashboard')
            ->extends('layout.master')
            ->section('main-content');
    }
}
