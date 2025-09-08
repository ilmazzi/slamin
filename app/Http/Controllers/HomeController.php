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
        // Carousel attivo
        $carousels = Carousel::active()->ordered()->get();


        // Video recenti per carosello
        $recentVideos = Video::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->withCount('views')
            ->withCount('likes')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Video popolari per carosello
        $popularVideos = Video::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->withCount('views')
            ->withCount('likes')
            ->withCount('comments')
            ->get()
            ->sortByDesc(function($video) {
                // Calcola il punteggio totale delle interazioni usando il sistema unificato
                return $video->views_count + $video->likes_count + $video->comments_count + $video->snaps()->count();
            })
            ->take(6);

        // Eventi più recenti con conteggio partecipanti
        $recentEvents = Event::where('status', 'published')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->limit(4)
            ->get();

        // Nuovi utenti registrati con statistiche complete
        $newUsers = User::withCount([
                'videos' => function($query) {
                    $query->where('moderation_status', 'approved');
                },
                'poems' => function($query) {
                    $query->where('moderation_status', 'approved');
                },
                'articles' => function($query) {
                    $query->where('moderation_status', 'approved');
                }
            ])
            ->withCount('followers')
            ->withCount('following')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Calcola le interazioni totali per ogni utente usando il sistema unificato
        $newUsers->each(function($user) {
            // Visualizzazioni ricevute su poesie, articoli e video (sistema unificato)
            $poemViews = \DB::table('unified_views')
                ->join('poems', function($join) use ($user) {
                    $join->on('unified_views.viewable_id', '=', 'poems.id')
                         ->where('unified_views.viewable_type', '=', 'App\\Models\\Poem')
                         ->where('poems.user_id', '=', $user->id);
                })
                ->count();

            $articleViews = \DB::table('unified_views')
                ->join('articles', function($join) use ($user) {
                    $join->on('unified_views.viewable_id', '=', 'articles.id')
                         ->where('unified_views.viewable_type', '=', 'App\\Models\\Article')
                         ->where('articles.user_id', '=', $user->id);
                })
                ->count();

            $videoViews = \DB::table('unified_views')
                ->join('videos', function($join) use ($user) {
                    $join->on('unified_views.viewable_id', '=', 'videos.id')
                         ->where('unified_views.viewable_type', '=', 'App\\Models\\Video')
                         ->where('videos.user_id', '=', $user->id);
                })
                ->count();

            // Like ricevuti su poesie, articoli e video (sistema unificato)
            $poemLikes = \DB::table('unified_likes')
                ->join('poems', function($join) use ($user) {
                    $join->on('unified_likes.likeable_id', '=', 'poems.id')
                         ->where('unified_likes.likeable_type', '=', 'App\\Models\\Poem')
                         ->where('poems.user_id', '=', $user->id);
                })
                ->count();

            $articleLikes = \DB::table('unified_likes')
                ->join('articles', function($join) use ($user) {
                    $join->on('unified_likes.likeable_id', '=', 'articles.id')
                         ->where('unified_likes.likeable_type', '=', 'App\\Models\\Article')
                         ->where('articles.user_id', '=', $user->id);
                })
                ->count();

            $videoLikes = \DB::table('unified_likes')
                ->join('videos', function($join) use ($user) {
                    $join->on('unified_likes.likeable_id', '=', 'videos.id')
                         ->where('unified_likes.likeable_type', '=', 'App\\Models\\Video')
                         ->where('videos.user_id', '=', $user->id);
                })
                ->count();

            // Commenti ricevuti su poesie, articoli e video (sistema unificato)
            $poemComments = \DB::table('unified_comments')
                ->join('poems', function($join) use ($user) {
                    $join->on('unified_comments.commentable_id', '=', 'poems.id')
                         ->where('unified_comments.commentable_type', '=', 'App\\Models\\Poem')
                         ->where('poems.user_id', '=', $user->id);
                })
                ->count();

            $articleComments = \DB::table('unified_comments')
                ->join('articles', function($join) use ($user) {
                    $join->on('unified_comments.commentable_id', '=', 'articles.id')
                         ->where('unified_comments.commentable_type', '=', 'App\\Models\\Article')
                         ->where('articles.user_id', '=', $user->id);
                })
                ->count();

            $videoComments = \DB::table('unified_comments')
                ->join('videos', function($join) use ($user) {
                    $join->on('unified_comments.commentable_id', '=', 'videos.id')
                         ->where('unified_comments.commentable_type', '=', 'App\\Models\\Video')
                         ->where('videos.user_id', '=', $user->id);
                })
                ->count();

            // Snap ricevuti sui video (sistema esistente)
            $videoSnaps = $user->videos()->withCount('snaps')->get()->sum('snaps_count');

            // Totale interazioni
            $user->total_interactions = $poemViews + $articleViews + $videoViews +
                                      $poemLikes + $articleLikes + $videoLikes +
                                      $poemComments + $articleComments + $videoComments +
                                      $videoSnaps;
        });

        // Aggiungi lo stato follow per l'utente autenticato
        if (auth()->check()) {
            $newUsers->each(function($user) {
                $user->is_followed_by_current_user = auth()->user()->isFollowing($user);
            });
        }

        // Poesie recenti per sezione Poesia
        $recentPoems = Poem::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->withCount('views')
            ->withCount('likes')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Poesie popolari per sezione Poesia
        $popularPoems = Poem::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->with('user')
            ->withCount('views')
            ->withCount('likes')
            ->withCount('comments')
            ->get()
            ->sortByDesc(function($poem) {
                // Calcola il punteggio totale delle interazioni usando il sistema unificato
                return $poem->views_count + $poem->likes_count + $poem->comments_count;
            })
            ->take(4);

        // Articoli recenti per sezione Articoli
        $recentArticles = Article::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->where('status', 'published')
            ->with('user')
            ->withCount('views')
            ->withCount('likes')
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        // Articoli popolari per sezione Articoli
        $popularArticles = Article::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->where('status', 'published')
            ->with('user')
            ->withCount('views')
            ->withCount('likes')
            ->withCount('comments')
            ->get()
            ->sortByDesc(function($article) {
                // Calcola il punteggio totale delle interazioni usando il sistema unificato
                return $article->views_count + $article->likes_count + $article->comments_count;
            })
            ->take(4);

        // Statistiche generali
        $stats = [
            'total_videos' => Video::where('moderation_status', 'approved')->count(),
            'total_events' => Event::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_views' => \DB::table('unified_views')->where('viewable_type', 'App\\Models\\Video')->count(),
        ];

        return view('home', compact('carousels', 'recentVideos', 'popularVideos', 'recentEvents', 'newUsers', 'recentPoems', 'popularPoems', 'recentArticles', 'popularArticles', 'stats'));
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
