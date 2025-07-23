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
        // Filtri
        $type = $request->get('type', 'all'); // all, videos, photos
        $sort = $request->get('sort', 'latest'); // latest, popular, oldest
        
        // Query base per video
        $videosQuery = Video::with(['user', 'likes', 'comments'])
            ->where('is_public', true)
            ->where('moderation_status', 'approved');
            
        // Query per foto reali
        $photosQuery = Photo::with(['user'])
            ->where('status', 'approved');
        
        // Applica filtri
        if ($type === 'videos') {
            $videos = $this->applySorting($videosQuery, $sort)->paginate(12);
            $photos = collect();
        } elseif ($type === 'photos') {
            $videos = collect();
            $photos = $this->applySorting($photosQuery, $sort)->paginate(12);
        } else {
            // Combina video e foto - MOSTRA TUTTI senza paginazione
            $videos = $this->applySorting($videosQuery, $sort)->get();
            $photos = $this->applySorting($photosQuery, $sort)->get();
            
            // Combina e ordina
            $allMedia = $videos->map(function($video) {
                return [
                    'type' => 'video',
                    'item' => $video,
                    'created_at' => $video->created_at,
                    'likes_count' => $video->likes->count(),
                    'comments_count' => $video->comments->count(),
                    'user' => $video->user
                ];
            })->concat($photos->map(function($photo) {
                return [
                    'type' => 'photo',
                    'item' => $photo,
                    'created_at' => $photo->created_at,
                    'likes_count' => $photo->like_count,
                    'comments_count' => 0, // Per ora non implementiamo commenti per le foto
                    'user' => $photo->user
                ];
            }));
            
            // Ordina per data o popolarità
            if ($sort === 'popular') {
                $allMedia = $allMedia->sortByDesc('likes_count');
            } else {
                $allMedia = $allMedia->sortByDesc('created_at');
            }
            
            // MOSTRA TUTTI i media senza paginazione
            $videos = collect();
            $photos = collect();
        }
        
        // Statistiche
        $stats = [
            'total_videos' => Video::where('is_public', true)->where('moderation_status', 'approved')->count(),
            'total_photos' => Photo::where('status', 'approved')->count(),
            'total_likes' => VideoLike::count() + Photo::sum('like_count'),
            'total_comments' => VideoComment::count(),
        ];
        
        return view('media.index', compact('videos', 'photos', 'allMedia', 'stats', 'type', 'sort'));
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
            // Per le foto (snap), per ora non implementiamo i like
            // perché VideoLike è specifico per i video
            return response()->json(['error' => 'Like non disponibili per le foto'], 400);
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
            // Per le foto (snap), per ora non implementiamo i commenti
            // perché VideoComment è specifico per i video
            return response()->json(['error' => 'Commenti non disponibili per le foto'], 400);
        }
        
        return response()->json([
            'comment' => $comment,
            'comments_count' => $commentsCount
        ]);
    }
} 