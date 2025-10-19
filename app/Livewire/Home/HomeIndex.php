<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Carousel;
use App\Models\Video;
use App\Models\Event;
use App\Models\User;

class HomeIndex extends Component
{
    // Properties per toggle content
    public $poetryContentType = 'new';
    public $articlesContentType = 'new';

    public function mount()
    {
        // Inizializzazione se necessario
    }

    public function render()
    {
        return view('livewire.home.home-index', [
            'carousels' => $this->getCarousels(),
            'recentVideos' => $this->getRecentVideos(),
            'recentEvents' => $this->getRecentEvents(),
            'stats' => $this->getStats(),
        ]);
    }

    // Toggle methods
    public function togglePoetryContent($type)
    {
        $this->poetryContentType = $type;
    }

    public function toggleArticlesContent($type)
    {
        $this->articlesContentType = $type;
    }

    // Computed properties per le query del database
    public function getCarousels()
    {
        try {
            return Carousel::active()->ordered()->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getRecentVideos()
    {
        try {
            return Video::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getRecentEvents()
    {
        try {
            return Event::where('status', 'published')
                ->where('start_datetime', '>=', now())
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getStats()
    {
        try {
            return [
                'total_videos' => Video::where('moderation_status', 'approved')->count(),
                'total_events' => Event::where('status', 'published')->count(),
                'total_users' => User::count(),
                'total_views' => 0, // Semplificato per ora
            ];
        } catch (\Exception $e) {
            return [
                'total_videos' => 0,
                'total_events' => 0,
                'total_users' => 0,
                'total_views' => 0,
            ];
        }
    }
}