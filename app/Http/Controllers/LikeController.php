<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;
use App\Models\Notification;
use App\Services\ActivityService;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle del like (aggiunge/rimuove)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string',
            'likeable_id' => 'required|integer',
        ]);

        // Verifica se i like sono abilitati
        if (!SystemSetting::get('social_enable_likes', true)) {
            return response()->json([
                'success' => false,
                'message' => 'I like sono disabilitati'
            ], 403);
        }

        // Verifica se il tipo di contenuto è likeabile
        $likeableContent = SystemSetting::get('social_likeable_content', ['video', 'photo', 'poem', 'article', 'event', 'comment']);
        $contentType = $request->likeable_type; // Usa direttamente il tipo ricevuto

        if (!in_array($contentType, $likeableContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Questo tipo di contenuto non può essere likato'
            ], 403);
        }

        // Ottieni il contenuto
        $content = $this->getContent($request->likeable_type, $request->likeable_id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Contenuto non trovato'
            ], 404);
        }

        $user = Auth::user();
        $wasLiked = $content->isLikedBy($user);
        $result = $content->toggleLike($user);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'operazione'
            ], 500);
        }

        // Log activity
        if (!$wasLiked) {
            // User liked the content
            ActivityService::logLike($user, $content, $request);
        }

        // Invia notifica se abilitata
        if (SystemSetting::get('social_enable_notifications', true)) {
            $notificationTypes = SystemSetting::get('social_notification_types', ['like', 'comment', 'snap']);

            if (in_array('like', $notificationTypes) && !$wasLiked) {
                $this->sendLikeNotification($content, $user);
            }
        }

        return response()->json([
            'success' => true,
            'liked' => !$wasLiked,
            'like_count' => $content->like_count,
            'message' => $wasLiked ? 'Like rimosso' : 'Like aggiunto'
        ]);
    }

    /**
     * Ottieni contenuti likati dall'utente
     */
    public function getLikedContent(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        $perPage = $request->get('per_page', 12);

        $query = $user->likedContent();

        if ($type !== 'all') {
            $contentType = $this->getContentTypeFromClass($type);
            $query->where('likeable_type', $type);
        }

        $content = $query->with(['likeable.user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Ottieni statistiche dei like
     */
    public function getLikeStats(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string',
            'likeable_id' => 'required|integer',
        ]);

        $content = $this->getContent($request->likeable_type, $request->likeable_id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Contenuto non trovato'
            ], 404);
        }

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'like_count' => $content->like_count,
                'is_liked_by_user' => $content->isLikedBy($user),
                'recent_likes' => $content->likes()
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            ]
        ]);
    }

    /**
     * Ottieni il contenuto dal tipo e ID
     */
    private function getContent(string $type, int $id)
    {
        $modelClass = $this->getModelClass($type);

        if (!$modelClass) {
            return null;
        }

        return $modelClass::find($id);
    }

    /**
     * Ottieni la classe del modello dal tipo
     */
    private function getModelClass(string $type): ?string
    {
        $models = [
            'video' => \App\Models\Video::class,
            'photo' => \App\Models\Photo::class,
            'poem' => \App\Models\Poem::class,
            'article' => \App\Models\Article::class,
            'event' => \App\Models\Event::class,
            'comment' => \App\Models\UnifiedComment::class,
        ];

        return $models[$type] ?? null;
    }

    /**
     * Ottieni il tipo di contenuto dalla classe
     */
    private function getContentTypeFromClass(string $class): string
    {
        $types = [
            \App\Models\Video::class => 'video',
            \App\Models\Photo::class => 'photo',
            \App\Models\Poem::class => 'poem',
            \App\Models\Article::class => 'article',
            \App\Models\Event::class => 'event',
            \App\Models\UnifiedComment::class => 'comment',
        ];

        return $types[$class] ?? 'unknown';
    }

    /**
     * Ottieni il nome del tipo di contenuto
     */
    private function getContentTypeName($content): string
    {
        $types = [
            \App\Models\Video::class => 'video',
            \App\Models\Photo::class => 'foto',
            \App\Models\Poem::class => 'poesia',
            \App\Models\Article::class => 'articolo',
            \App\Models\Event::class => 'evento',
            \App\Models\UnifiedComment::class => 'commento',
        ];

        return $types[get_class($content)] ?? 'contenuto';
    }

    /**
     * Invia notifica di like
     */
    private function sendLikeNotification($content, $liker)
    {
        // Ottieni l'utente proprietario del contenuto
        $contentOwner = $this->getContentOwner($content);

        if (!$contentOwner) {
            \Log::info('Like notification: content owner not found', [
                'content_type' => get_class($content),
                'content_id' => $content->id
            ]);
            return;
        }

        // Non inviare notifica se l'utente sta likando il proprio contenuto
        if ($contentOwner->id === $liker->id) {
            \Log::info('Like notification: user liking own content', [
                'user_id' => $liker->id,
                'content_type' => get_class($content),
                'content_id' => $content->id
            ]);
            return;
        }

        // Crea la notifica
        $notification = Notification::create([
            'user_id' => $contentOwner->id,
            'type' => 'content_liked',
            'title' => 'Nuovo like ricevuto',
            'message' => $liker->name . ' ha messo like al tuo ' . $this->getContentTypeName($content),
            'data' => [
                'liker_id' => $liker->id,
                'liker_name' => $liker->name,
                'content_type' => $this->getContentTypeFromClass(get_class($content)),
                'content_id' => $content->id,
                'content_title' => $this->getContentTitle($content),
            ],
            'action_url' => $this->getContentUrl($content),
            'action_text' => 'Visualizza',
            'priority' => 'normal',
        ]);

        \Log::info('Like notification created', [
            'notification_id' => $notification->id,
            'recipient_id' => $contentOwner->id,
            'liker_id' => $liker->id,
            'content_type' => get_class($content),
            'content_id' => $content->id
        ]);
    }

    /**
     * Ottieni il proprietario del contenuto
     */
    private function getContentOwner($content)
    {
        // Per gli eventi, usa organizer
        if ($content instanceof \App\Models\Event) {
            return $content->organizer;
        }

        // Per altri contenuti, usa user
        if (method_exists($content, 'user')) {
            return $content->user;
        }

        // Fallback per contenuti con user_id diretto
        if (isset($content->user_id)) {
            return \App\Models\User::find($content->user_id);
        }

        return null;
    }

    /**
     * Ottieni il titolo del contenuto
     */
    private function getContentTitle($content): string
    {
        $methods = ['title', 'name', 'subject'];

        foreach ($methods as $method) {
            if (method_exists($content, $method)) {
                return $content->$method;
            }
        }

        return 'Contenuto #' . $content->id;
    }

    /**
     * Ottieni l'URL del contenuto
     */
    private function getContentUrl($content): string
    {
        $type = $this->getContentTypeFromClass(get_class($content));

        switch ($type) {
            case 'video':
                return route('videos.show', $content);
            case 'photo':
                return route('media.index') . '#photo-' . $content->id;
            case 'poem':
                return route('poems.show', $content);
            case 'article':
                return route('articles.show', $content);
            case 'event':
                return route('events.show', $content);
            case 'comment':
                return $this->getCommentParentUrl($content);
            default:
                return route('home');
        }
    }

    /**
     * Ottieni l'URL del contenuto padre di un commento
     */
    private function getCommentParentUrl($comment): string
    {
        if (!$comment->commentable) {
            return route('home');
        }

        return $this->getContentUrl($comment->commentable);
    }
}
