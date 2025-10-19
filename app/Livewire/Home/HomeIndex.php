<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Carousel;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Article;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeIndex extends Component
{
    // Properties per toggle content
    public $poetryContentType = 'new'; // 'new' o 'popular'
    public $articlesContentType = 'new'; // 'new' o 'popular'

    public function mount()
    {
        // Inizializzazione se necessario
    }

    public function render()
    {
        return view('livewire.home.home-index', [
            'carousels' => $this->getCarousels(),
            'recentVideos' => $this->getRecentVideos(),
            'popularVideos' => $this->getPopularVideos(),
            'recentEvents' => $this->getRecentEvents(),
            'newUsers' => $this->getNewUsers(),
            'recentPoems' => $this->getRecentPoems(),
            'popularPoems' => $this->getPopularPoems(),
            'recentArticles' => $this->getRecentArticles(),
            'popularArticles' => $this->getPopularArticles(),
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
                ->withCount('views')
                ->withCount('likes')
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getPopularVideos()
    {
        try {
            return Video::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->withCount([
                    'likes',
                    'comments' => function($query) {
                        $query->where('status', 'approved');
                    },
                    'snaps',
                    'views'
                ])
                ->get()
                ->map(function($video) {
                    $video->total_interactions = ($video->likes_count ?? 0) +
                                               ($video->comments_count ?? 0) +
                                               ($video->snaps_count ?? 0) +
                                               ($video->views_count ?? 0);
                    return $video;
                })
                ->sortByDesc('total_interactions')
                ->take(6);
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getRecentEvents()
    {
        try {
            return Event::where('status', 'published')
                ->where('start_datetime', '>=', now())
                ->orderBy('start_datetime', 'asc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getNewUsers()
    {
        try {
            return User::where('created_at', '>=', now()->subDays(7))
                ->where('is_active', true)
                ->withCount('followers')
                ->withCount('following')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getRecentPoems()
    {
        try {
            return Poem::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->withCount('views')
                ->withCount('likes')
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getPopularPoems()
    {
        try {
            return Poem::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->withCount('views')
                ->withCount('likes')
                ->withCount('comments')
                ->get()
                ->sortByDesc(function($poem) {
                    return $poem->views_count + $poem->likes_count + $poem->comments_count;
                })
                ->take(4);
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getRecentArticles()
    {
        try {
            return Article::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->withCount('views')
                ->withCount('likes')
                ->withCount('comments')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getPopularArticles()
    {
        try {
            return Article::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->withCount('views')
                ->withCount('likes')
                ->withCount('comments')
                ->get()
                ->sortByDesc(function($article) {
                    return $article->views_count + $article->likes_count + $article->comments_count;
                })
                ->take(4);
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
                'total_views' => DB::table('unified_views')->where('viewable_type', 'App\\Models\\Video')->count(),
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