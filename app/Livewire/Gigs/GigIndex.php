<?php

namespace App\Livewire\Gigs;

use App\Models\Gig;
use App\Models\Event;
use App\Models\Group;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class GigIndex extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $category = '';
    
    #[Url]
    public $type = '';
    
    #[Url]
    public $language = '';
    
    #[Url]
    public $location = '';
    
    #[Url]
    public $event_id = '';
    
    #[Url]
    public $group_id = '';
    
    #[Url]
    public $show_featured = false;
    
    #[Url]
    public $show_urgent = false;
    
    #[Url]
    public $show_remote = false;
    
    #[Url]
    public $sort_by = 'created_at';
    
    #[Url]
    public $sort_direction = 'desc';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedLanguage()
    {
        $this->resetPage();
    }

    public function updatedLocation()
    {
        $this->resetPage();
    }

    public function updatedEventId()
    {
        $this->resetPage();
    }

    public function updatedGroupId()
    {
        $this->resetPage();
    }

    public function updatedShowFeatured()
    {
        $this->resetPage();
    }

    public function updatedShowUrgent()
    {
        $this->resetPage();
    }

    public function updatedShowRemote()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'category',
            'type',
            'language',
            'location',
            'event_id',
            'group_id',
            'show_featured',
            'show_urgent',
            'show_remote'
        ]);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }
    }

    public function getGigsProperty()
    {
        $query = Gig::query()
            ->with(['user', 'event', 'group', 'applications'])
            ->where(function($q) {
                $q->where('is_closed', false)
                  ->where('deadline', '>', now());
            });

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->language) {
            $query->where('language', $this->language);
        }

        if ($this->location) {
            $query->where('location', 'like', '%' . $this->location . '%');
        }

        if ($this->event_id) {
            $query->where('event_id', $this->event_id);
        }

        if ($this->group_id) {
            $query->where('group_id', $this->group_id);
        }

        if ($this->show_featured) {
            $query->where('is_featured', true);
        }

        if ($this->show_urgent) {
            $query->where('is_urgent', true);
        }

        if ($this->show_remote) {
            $query->where('is_remote', true);
        }

        // Sort
        $query->orderBy($this->sort_by, $this->sort_direction);

        return $query->paginate(12);
    }

    public function getStatsProperty()
    {
        return [
            'total_gigs' => Gig::count(),
            'open_gigs_count' => Gig::open()->count(),
            'urgent_gigs_count' => Gig::urgent()->count(),
            'featured_gigs_count' => Gig::featured()->count(),
        ];
    }

    public function getEventsProperty()
    {
        return Event::where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->take(20)
            ->get();
    }

    public function getGroupsProperty()
    {
        if (!auth()->check()) {
            return collect();
        }
        
        return auth()->user()->groups()
            ->orderBy('name')
            ->take(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.gigs.gig-index', [
            'gigs' => $this->gigs,
            'stats' => $this->stats,
            'events' => $this->events,
            'groups' => $this->groups,
        ])->extends('layout.master')->section('main-content');
    }
}

