<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;

class ViewController extends Controller
{
    // Views non richiedono autenticazione

    /**
     * Incrementa le visualizzazioni
     */
    public function increment(Request $request)
    {

        $request->validate([
            'viewable_type' => 'required|string',
            'viewable_id' => 'required|numeric',
        ]);

        // Verifica se le visualizzazioni sono abilitate
        if (!SystemSetting::get('social_enable_views', true)) {
            return response()->json([
                'success' => false,
                'message' => 'Le visualizzazioni sono disabilitate'
            ], 403);
        }

        // Verifica se il tipo di contenuto è tracciabile
        $viewableContent = SystemSetting::get('social_viewable_content', ['video', 'photo', 'poem', 'article', 'event']);
        $contentType = $request->viewable_type; // Usa direttamente il tipo ricevuto

        if (!in_array($contentType, $viewableContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Questo tipo di contenuto non può essere tracciato'
            ], 403);
        }

        // Ottieni il contenuto
        $content = $this->getContent($request->viewable_type, $request->viewable_id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Contenuto non trovato'
            ], 404);
        }

        $user = Auth::user(); // Può essere null per utenti non autenticati
        $wasViewed = $user ? $content->isViewedBy($user) : false;
        $result = $content->incrementViewIfNotOwner($user);

        if (!$result) {
            // Se il contenuto è già stato visualizzato, restituisci successo con messaggio informativo
            if ($wasViewed) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contenuto già visualizzato',
                    'view_count' => $content->view_count
                ], 200);
            }

            // Solo per errori reali, restituisci 400
            return response()->json([
                'success' => false,
                'message' => 'Impossibile incrementare le visualizzazioni'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'view_count' => $content->view_count,
            'message' => 'Visualizzazione registrata'
        ]);
    }

    /**
     * Ottieni contenuti visualizzati dall'utente
     */
    public function getViewedContent(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Autenticazione richiesta'
            ], 401);
        }

        $type = $request->get('type', 'all');
        $perPage = $request->get('per_page', 12);

        $query = $user->viewedContent();

        if ($type !== 'all') {
            $contentType = $this->getContentTypeFromClass($type);
            $query->where('viewable_type', $type);
        }

        $content = $query->with(['viewable.user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Ottieni statistiche delle visualizzazioni
     */
    public function getViewStats(Request $request)
    {
        $request->validate([
            'viewable_type' => 'required|string',
            'viewable_id' => 'required|integer',
        ]);

        $content = $this->getContent($request->viewable_type, $request->viewable_id);

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
                'view_count' => $content->view_count,
                'is_viewed_by_user' => $content->isViewedBy($user),
                'recent_views' => $content->views()
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
        ];

        return $types[$class] ?? 'unknown';
    }
}
