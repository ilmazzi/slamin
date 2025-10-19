<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Article;
use App\Models\Event;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        try {
            // Carousel attivo
            $carousels = Carousel::active()->ordered()->get();

            // Video recenti per carosello
            $recentVideos = Video::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            // Eventi più recenti con conteggio partecipanti
            $recentEvents = Event::where('status', 'published')
                ->where('start_datetime', '>=', now())
                ->orderBy('start_datetime', 'asc')
                ->limit(4)
                ->get();

            // Nuovi utenti registrati con statistiche complete
            $newUsers = User::where('created_at', '>=', now()->subDays(7))
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            // Poesie recenti per sezione Poesie
            $recentPoems = Poem::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Poesie popolari per sezione Poesie
            $popularPoems = Poem::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Articoli recenti per sezione Articoli
            $recentArticles = Article::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Articoli popolari per sezione Articoli
            $popularArticles = Article::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->where('status', 'published')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Statistiche generali
            $stats = [
                'total_videos' => Video::where('moderation_status', 'approved')->count(),
                'total_events' => Event::where('status', 'published')->count(),
                'total_users' => User::count(),
                'total_views' => 0, // Semplificato per ora
            ];

            return view('home', compact('carousels', 'recentVideos', 'recentEvents', 'newUsers', 'recentPoems', 'popularPoems', 'recentArticles', 'popularArticles', 'stats'));
        } catch (\Exception $e) {
            // Fallback in caso di errore
            return view('home', [
                'carousels' => collect([]),
                'recentVideos' => collect([]),
                'recentEvents' => collect([]),
                'newUsers' => collect([]),
                'recentPoems' => collect([]),
                'popularPoems' => collect([]),
                'recentArticles' => collect([]),
                'popularArticles' => collect([]),
                'stats' => [
                    'total_videos' => 0,
                    'total_events' => 0,
                    'total_users' => 0,
                    'total_views' => 0,
                ]
            ]);
        }
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