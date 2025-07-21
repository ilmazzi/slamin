<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\Video;
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

        // Poeti più attivi (più video caricati)
        $topPoets = User::withCount(['videos' => function($query) {
                $query->where('moderation_status', 'approved');
            }])
            ->whereHas('videos', function($query) {
                $query->where('moderation_status', 'approved');
            })
            ->orderBy('videos_count', 'desc')
            ->limit(6)
            ->get();

        // Statistiche generali
        $stats = [
            'total_videos' => Video::where('moderation_status', 'approved')->count(),
            'total_events' => Event::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_views' => Video::sum('view_count'),
        ];

        return view('home', compact('carousels', 'mostPopularVideo', 'recentEvents', 'topPoets', 'stats'));
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
