<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\User;
use App\Models\VideoSnap;
use App\Models\Photo;
use App\Models\VideoComment;
use App\Models\VideoLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        // Query base per video
        $videosQuery = Video::with(['user', 'likes', 'comments', 'snaps'])
            ->where('is_public', true)
            ->where('moderation_status', 'approved');

        // Video più popolare (somma di like, commenti, snap e views usando withCount per efficienza)
        $mostPopularVideo = $videosQuery->withCount([
            'likes',
            'comments' => function($query) {
                $query->where('status', 'approved');
            },
            'snaps',
            'views'
        ])->get()->map(function($video) {
            $video->total_interactions = ($video->likes_count ?? 0) +
                                       ($video->comments_count ?? 0) +
                                       ($video->snaps_count ?? 0) +
                                       ($video->views_count ?? 0);
            return $video;
        })->sortByDesc('total_interactions')->first();

        // Video popolari (ordinati per interazioni totali usando withCount per efficienza)
        $popularVideos = $videosQuery->withCount([
            'likes',
            'comments' => function($query) {
                $query->where('status', 'approved');
            },
            'snaps',
            'views'
        ])->get()->map(function($video) {
            $video->total_interactions = ($video->likes_count ?? 0) +
                                       ($video->comments_count ?? 0) +
                                       ($video->snaps_count ?? 0) +
                                       ($video->views_count ?? 0);
            return $video;
        })->sortByDesc('total_interactions')->take(6);

        // Debug temporaneo per verificare i video popolari
        \Log::info('Popular videos count: ' . $popularVideos->count());
        foreach($popularVideos as $video) {
            \Log::info('Popular video: ' . $video->title . ' - Interactions: ' . $video->total_interactions . ' - Thumbnail: ' . ($video->thumbnail_url ?? 'NULL'));
        }
        
        // Video nuovi (ordinati per data di creazione)
        $newVideos = $videosQuery->orderBy('created_at', 'desc')->take(6)->get();
        
        // Debug per verificare i video nuovi
        \Log::info('New videos count: ' . $newVideos->count());
        foreach($newVideos as $video) {
            \Log::info('New video: ' . $video->title);
        }

        // Query base per foto
        $photosQuery = Photo::with(['user', 'likes', 'comments'])
            ->where('status', 'approved');

        // Foto più popolare (somma di like, commenti e views usando withCount per efficienza)
        $mostPopularPhoto = $photosQuery->withCount([
            'likes',
            'comments' => function($query) {
                $query->where('status', 'approved');
            },
            'views'
        ])->get()->map(function($photo) {
            $photo->total_interactions = ($photo->likes_count ?? 0) +
                                       ($photo->comments_count ?? 0) +
                                       ($photo->views_count ?? 0);
            return $photo;
        })->sortByDesc('total_interactions')->first();

        // Foto popolari (ordinati per interazioni totali usando withCount per efficienza)
        $popularPhotos = $photosQuery->withCount([
            'likes',
            'comments' => function($query) {
                $query->where('status', 'approved');
            },
            'views'
        ])->get()->map(function($photo) {
            $photo->total_interactions = ($photo->likes_count ?? 0) +
                                       ($photo->comments_count ?? 0) +
                                       ($photo->views_count ?? 0);
            return $photo;
        })->sortByDesc('total_interactions')->take(6);

        // Foto nuove (ordinate per data di creazione)
        $newPhotos = $photosQuery->orderBy('created_at', 'desc')->take(6)->get();

        return view('media.index', compact('mostPopularVideo', 'popularVideos', 'newVideos', 'mostPopularPhoto', 'popularPhotos', 'newPhotos'));
    }

    private function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'popular':
                return $query->withCount(['likes', 'comments'])
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('comments_count', 'desc');
            case 'oldest':
                return $query->orderBy('created_at', 'asc');
            default: // latest
                return $query->orderBy('created_at', 'desc');
        }
    }

    public function like(Request $request)
    {
        $request->validate([
            'type' => 'required|in:video,photo',
            'id' => 'required|integer'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Devi essere autenticato'], 401);
        }

        if ($request->type === 'video') {
            // Per i video, usa VideoLike
            $video = Video::findOrFail($request->id);
            $existingLike = VideoLike::where('video_id', $video->id)
                ->where('user_id', $user->id)
                ->where('type', 'like')
                ->first();

            if ($existingLike) {
                $existingLike->delete();
                $action = 'unliked';
            } else {
                VideoLike::create([
                    'video_id' => $video->id,
                    'user_id' => $user->id,
                    'type' => 'like'
                ]);
                $action = 'liked';
            }

            $likesCount = VideoLike::where('video_id', $video->id)
                ->where('type', 'like')
                ->count();
        } else {
            // Per le foto, usa il sistema di like delle foto
            $photo = Photo::findOrFail($request->id);
            $existingLike = $photo->likes()->where('user_id', $user->id)->first();

            if ($existingLike) {
                $existingLike->delete();
                $action = 'unliked';
            } else {
                $photo->likes()->create([
                    'user_id' => $user->id
                ]);
                $action = 'liked';
            }

            $likesCount = $photo->likes()->count();
        }

        return response()->json([
            'action' => $action,
            'likes_count' => $likesCount
        ]);
    }

    public function comment(Request $request)
    {
        $request->validate([
            'type' => 'required|in:video,photo',
            'id' => 'required|integer',
            'content' => 'required|string|max:500'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Devi essere autenticato'], 401);
        }

        if ($request->type === 'video') {
            // Per i video, usa VideoComment
            $video = Video::findOrFail($request->id);
            $comment = VideoComment::create([
                'video_id' => $video->id,
                'user_id' => $user->id,
                'content' => $request->content,
                'status' => 'approved'
            ]);

            $comment->load('user');
            $commentsCount = VideoComment::where('video_id', $video->id)
                ->where('status', 'approved')
                ->count();
        } else {
            // Per le foto, usa il sistema di commenti delle foto
            $photo = Photo::findOrFail($request->id);
            $comment = $photo->comments()->create([
                'user_id' => $user->id,
                'content' => $request->content,
                'status' => 'approved'
            ]);

            $comment->load('user');
            $commentsCount = $photo->comments()->where('status', 'approved')->count();
        }

        return response()->json([
            'comment' => $comment,
            'comments_count' => $commentsCount
        ]);
    }

    /**
     * Ricerca media (video e foto)
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $type = $request->get('type', '');
        $sort = $request->get('sort', 'recent');
        $limit = 12; // Limite risultati per pagina

        $results = collect();

        // Ricerca video
        if ($type === '' || $type === 'video') {
            $videoQuery = Video::with(['user', 'likes', 'comments'])
                ->where('is_public', true)
                ->where('moderation_status', 'approved');

            if ($query) {
                $videoQuery->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
            }

            // Applica ordinamento
            switch ($sort) {
                case 'popular':
                    $videoQuery->withCount(['likes', 'snaps'])
                        ->withCount(['comments' => function($query) {
                            $query->where('status', 'approved');
                        }])
                        ->orderBy('likes_count', 'desc')
                        ->orderBy('comments_count', 'desc')
                        ->orderBy('snaps_count', 'desc');
                    break;
                case 'views':
                    $videoQuery->orderBy('view_count', 'desc');
                    break;
                case 'likes':
                    $videoQuery->withCount('likes')->orderBy('likes_count', 'desc');
                    break;
                default: // recent
                    $videoQuery->withCount(['comments' => function($query) {
                        $query->where('status', 'approved');
                    }]);
                    $videoQuery->orderBy('created_at', 'desc');
            }

            $videos = $videoQuery->take($limit)->get();

            // Aggiungi tipo e formatta per il frontend
            $videos->each(function($video) {
                $video->type = 'video';
                $video->thumbnail_url = $video->thumbnail_url ?? null;
                $video->views = $video->view_count ?? $video->views ?? 0;
            });

            $results = $results->merge($videos);
        }

        // Ricerca foto
        if ($type === '' || $type === 'photo') {
            $photoQuery = Photo::with(['user', 'likes', 'comments'])
                ->where('moderation_status', 'approved');

            if ($query) {
                $photoQuery->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
            }

            // Applica ordinamento
            switch ($sort) {
                case 'popular':
                    $photoQuery->withCount('likes')
                        ->withCount(['comments' => function($query) {
                            $query->where('status', 'approved');
                        }])
                        ->orderBy('likes_count', 'desc')
                        ->orderBy('comments_count', 'desc');
                    break;
                case 'views':
                    $photoQuery->orderBy('view_count', 'desc');
                    break;
                case 'likes':
                    $photoQuery->withCount('likes')->orderBy('likes_count', 'desc');
                    break;
                default: // recent
                    $photoQuery->withCount(['comments' => function($query) {
                        $query->where('status', 'approved');
                    }]);
                    $photoQuery->orderBy('created_at', 'desc');
            }

            $photos = $photoQuery->take($limit)->get();

            // Aggiungi tipo e formatta per il frontend
            $photos->each(function($photo) {
                $photo->type = 'photo';
                $photo->image_url = $photo->image_url ?? null;
                $photo->views = $photo->view_count ?? $photo->views ?? 0;
            });

            $results = $results->merge($photos);
        }

        // Riordina i risultati se sono misti
        if ($type === '') {
            switch ($sort) {
                case 'recent':
                    $results = $results->sortByDesc('created_at');
                    break;
                case 'popular':
                    $results = $results->sortByDesc(function($item) {
                        return ($item->like_count ?? 0) + ($item->comment_count ?? 0);
                    });
                    break;
                case 'views':
                    $results = $results->sortByDesc('views');
                    break;
                case 'likes':
                    $results = $results->sortByDesc('like_count');
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results->take($limit)->values(),
            'total' => $results->count(),
            'query' => $query,
            'type' => $type,
            'sort' => $sort
        ]);
    }
}
