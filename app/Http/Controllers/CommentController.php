<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\SystemSetting;
use App\Models\Notification;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Crea un nuovo commento
     */
    public function store(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:unified_comments,id',
        ]);

        // Verifica se i commenti sono abilitati
        if (!SystemSetting::get('social_enable_comments', true)) {
            return response()->json([
                'success' => false,
                'message' => 'I commenti sono disabilitati'
            ], 403);
        }

        // Verifica se il tipo di contenuto è commentabile
        $commentableContent = SystemSetting::get('social_commentable_content', ['video', 'photo', 'poem', 'article', 'event']);
        $contentType = $request->commentable_type; // Usa direttamente il tipo ricevuto
        
        if (!in_array($contentType, $commentableContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Questo tipo di contenuto non può essere commentato'
            ], 403);
        }

        // Ottieni il contenuto
        $content = $this->getContent($request->commentable_type, $request->commentable_id);
        
        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Contenuto non trovato'
            ], 404);
        }

        $user = Auth::user();
        $comment = $content->addComment($request->content, $user, $request->parent_id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Impossibile aggiungere il commento'
            ], 500);
        }

        // Invia notifica se abilitata
        if (SystemSetting::get('social_enable_notifications', true)) {
            $notificationTypes = SystemSetting::get('social_notification_types', ['like', 'comment', 'snap']);
            
            if (in_array('comment', $notificationTypes)) {
                $this->sendCommentNotification($content, $user, $comment);
            }
        }

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'comment_count' => $content->comment_count,
            'message' => 'Commento aggiunto con successo'
        ]);
    }

    /**
     * Aggiorna un commento
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Verifica che l'utente possa modificare il commento
        if ($comment->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai i permessi per modificare questo commento'
            ], 403);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'message' => 'Commento aggiornato con successo'
        ]);
    }

    /**
     * Elimina un commento
     */
    public function destroy(Comment $comment)
    {
        $user = Auth::user();

        // Verifica che l'utente possa eliminare il commento
        if ($comment->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai i permessi per eliminare questo commento'
            ], 403);
        }

        $commentable = $comment->commentable;
        $comment->delete();

        return response()->json([
            'success' => true,
            'comment_count' => $commentable ? $commentable->comment_count : 0,
            'message' => 'Commento eliminato con successo'
        ]);
    }

    /**
     * Approva un commento (solo admin)
     */
    public function approve(Comment $comment)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai i permessi per approvare commenti'
            ], 403);
        }

        $comment->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Commento approvato con successo'
        ]);
    }

    /**
     * Rifiuta un commento (solo admin)
     */
    public function reject(Comment $comment)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai i permessi per rifiutare commenti'
            ], 403);
        }

        $comment->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Commento rifiutato con successo'
        ]);
    }

    /**
     * Ottieni i commenti di un contenuto
     */
    public function getComments(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
        ]);

        $content = $this->getContent($request->commentable_type, $request->commentable_id);
        
        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Contenuto non trovato'
            ], 404);
        }

        $perPage = $request->get('per_page', 10);
        $comments = $content->approvedComments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id') // Solo commenti principali
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Ottieni le risposte di un commento
     */
    public function getReplies(Comment $comment)
    {
        $replies = $comment->replies()
            ->with('user')
            ->approved()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $replies
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
            'article' => \App\Models\Carousel::class, // Per ora usiamo Carousel come articoli
            'event' => \App\Models\Event::class,
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
            \App\Models\Carousel::class => 'article',
            \App\Models\Event::class => 'event',
        ];

        return $types[$class] ?? 'unknown';
    }

    /**
     * Invia notifica di commento
     */
    private function sendCommentNotification($content, $commenter, $comment)
    {
        // Verifica che il contenuto abbia un autore
        if (!method_exists($content, 'user') || !$content->user) {
            return;
        }

        // Non inviare notifica se l'utente sta commentando il proprio contenuto
        if ($content->user_id === $commenter->id) {
            return;
        }

        // Crea la notifica
        Notification::create([
            'user_id' => $content->user_id,
            'type' => 'content_commented',
            'title' => 'Nuovo commento ricevuto',
            'message' => $commenter->name . ' ha commentato il tuo ' . $this->getContentTypeName($content),
            'data' => [
                'commenter_id' => $commenter->id,
                'commenter_name' => $commenter->name,
                'content_type' => $this->getContentTypeFromClass(get_class($content)),
                'content_id' => $content->id,
                'content_title' => $this->getContentTitle($content),
                'comment_id' => $comment->id,
                'comment_preview' => substr($comment->content, 0, 100),
            ],
            'action_url' => $this->getContentUrl($content),
            'action_text' => 'Visualizza',
            'priority' => 'normal',
        ]);
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
            \App\Models\Carousel::class => 'articolo',
            \App\Models\Event::class => 'evento',
        ];

        return $types[get_class($content)] ?? 'contenuto';
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
                return route('home') . '#article-' . $content->id;
            case 'event':
                return route('events.show', $content);
            default:
                return route('home');
        }
    }
}
