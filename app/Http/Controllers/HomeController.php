<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Event;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Carousel attivo
        $carousels = Carousel::active()->ordered()->get();

        // Video più popolare (più interazioni totali)
        $mostPopularVideo = Video::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->get()
            ->sortByDesc(function($video) {
                // Calcola il punteggio totale delle interazioni
                return $video->view_count + $video->like_count + $video->comment_count + $video->snaps()->count();
            })
            ->first();

        // Eventi più recenti
        $recentEvents = Event::where('status', 'published')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->limit(4)
            ->get();

        // Nuovi utenti registrati
        $newUsers = User::withCount(['videos' => function($query) {
                $query->where('moderation_status', 'approved');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Poesie recenti per sezione Poesia
        $recentPoems = Poem::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Poesie popolari per sezione Poesia
        $popularPoems = Poem::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->orderBy('view_count', 'desc')
            ->limit(4)
            ->get();

        // Articoli recenti (placeholder - da implementare quando avrai il modello Article)
        $recentArticles = collect([]);
        $popularArticles = collect([]);

        // Statistiche generali
        $stats = [
            'total_videos' => Video::where('moderation_status', 'approved')->count(),
            'total_events' => Event::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_views' => Video::sum('view_count'),
        ];

        return view('home', compact('carousels', 'mostPopularVideo', 'recentEvents', 'newUsers', 'recentPoems', 'popularPoems', 'recentArticles', 'popularArticles', 'stats'));
    }

    /**
     * Display about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('contact');
    }
}
