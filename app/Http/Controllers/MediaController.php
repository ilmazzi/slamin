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

        // Video più popolare (somma di like, commenti, snap e views)
        $mostPopularVideo = $videosQuery->get()->map(function($video) {
            $video->total_interactions = ($video->like_count ?? 0) +
                                       ($video->comment_count ?? 0) +
                                       ($video->snap_count ?? 0) +
                                       ($video->view_count ?? $video->views ?? 0);
            return $video;
        })->sortByDesc('total_interactions')->first();

        // Video popolari (ordinati per interazioni totali)
        $popularVideos = $videosQuery->get()->map(function($video) {
            $video->total_interactions = ($video->like_count ?? 0) +
                                       ($video->comment_count ?? 0) +
                                       ($video->snap_count ?? 0) +
                                       ($video->view_count ?? $video->views ?? 0);
            return $video;
        })->sortByDesc('total_interactions')->take(6);

        // Video nuovi (ordinati per data di creazione)
        $newVideos = $videosQuery->orderBy('created_at', 'desc')->take(6)->get();

        // Query base per foto
        $photosQuery = Photo::with(['user', 'likes', 'comments'])
            ->where('moderation_status', 'approved');

        // Foto più popolare (somma di like, commenti, snap e views)
        $mostPopularPhoto = $photosQuery->get()->map(function($photo) {
            $photo->total_interactions = ($photo->like_count ?? 0) +
                                       ($photo->comment_count ?? 0) +
                                       ($photo->snap_count ?? 0) +
                                       ($photo->view_count ?? $photo->views ?? 0);
            return $photo;
        })->sortByDesc('total_interactions')->first();

        // Foto popolari (ordinati per interazioni totali)
        $popularPhotos = $photosQuery->get()->map(function($photo) {
            $photo->total_interactions = ($photo->like_count ?? 0) +
                                       ($photo->comment_count ?? 0) +
                                       ($photo->snap_count ?? 0) +
                                       ($photo->view_count ?? $photo->views ?? 0);
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
