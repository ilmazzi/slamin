<?php

namespace App\Livewire\Forum;

use App\Models\Subreddit;
use App\Models\ForumPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SubredditShow extends Component
{
    use WithPagination;

    public Subreddit $subreddit;
    public $sortBy = 'hot';
    public $timeframe = 'all';

    protected $queryString = [
        'sortBy' => ['except' => 'hot'],
        'timeframe' => ['except' => 'all'],
    ];

    public function mount(Subreddit $subreddit)
    {
        $this->subreddit = $subreddit;
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

    public function subscribe()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($this->subreddit->isSubscribedBy($user)) {
            $this->subreddit->subscribers()->detach($user->id);
            $this->subreddit->decrement('subscribers_count');
            
            $this->dispatch('swal:toast', [
                'icon' => 'info',
                'title' => 'Iscrizione annullata',
            ]);
        } else {
            $this->subreddit->subscribers()->attach($user->id);
            $this->subreddit->increment('subscribers_count');
            
            $this->dispatch('swal:toast', [
                'icon' => 'success',
                'title' => 'Iscritto a r/' . $this->subreddit->name,
            ]);
        }

        $this->subreddit->refresh();
    }

    public function render()
    {
        $query = ForumPost::where('subreddit_id', $this->subreddit->id)
            ->with(['user'])
            ->approved();

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

        $isSubscribed = Auth::check() && $this->subreddit->isSubscribedBy(Auth::user());
        $isModerator = Auth::check() && $this->subreddit->isModerator(Auth::user());

        return view('livewire.forum.subreddit-show', [
            'posts' => $posts,
            'isSubscribed' => $isSubscribed,
            'isModerator' => $isModerator,
        ])->extends('layout.master')->section('main-content');
    }
}
