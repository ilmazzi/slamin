<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\VideoComment;
use App\Models\VideoLike;
use App\Models\VideoSnap;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // Added for PeerTube direct URL

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la pagina di riproduzione del video e incrementa le visualizzazioni
     */
    public function show(Video $video)
    {
        // Incrementa le visualizzazioni solo se l'utente non è il proprietario
        $video->incrementViewsIfNotOwner();

        // Carica i commenti approvati con l'utente
        $comments = $video->approvedComments()->with('user')->orderBy('created_at', 'desc')->get();

        // Carica gli snap approvati (prima i più recenti, poi i più popolari)
        $snaps = $video->approvedSnaps()->with('user')->orderBy('created_at', 'desc')->get();



        // Verifica se l'utente ha già messo like
        $userLike = null;
        if (Auth::check()) {
            $userLike = $video->likes()->where('user_id', Auth::id())->first();
        }

        return view('videos.show', compact('video', 'comments', 'snaps', 'userLike'));
    }

    /**
     * Incrementa le visualizzazioni via AJAX
     */
    public function incrementViews(Request $request, Video $video)
    {
        // Debug: salva in un file
        file_put_contents(storage_path('views_debug.txt'), '=== INCREMENT VIEWS ===' . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'Video ID: ' . $video->id . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'User ID: ' . (auth()->id() ?? 'NULL') . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'Video User ID: ' . $video->user_id . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'Current views: ' . $video->view_count . "\n", FILE_APPEND);

        // Incrementa le visualizzazioni solo se l'utente non è il proprietario
        $incremented = $video->incrementViewsIfNotOwner();
        if ($incremented) {
            file_put_contents(storage_path('views_debug.txt'), 'Views incremented! (User is not owner)' . "\n", FILE_APPEND);
        } else {
            file_put_contents(storage_path('views_debug.txt'), 'Views NOT incremented! (User is owner)' . "\n", FILE_APPEND);
        }

        // Ricarica il video per ottenere il conteggio aggiornato
        $video->refresh();
        file_put_contents(storage_path('views_debug.txt'), 'Final views: ' . $video->view_count . "\n", FILE_APPEND);

        // Restituisci sempre JSON, anche se le views non sono state incrementate
        return response()->json([
            'success' => true,
            'view_count' => $video->view_count,
            'incremented' => $incremented
        ]);
    }



    /**
     * Download del video
     */
    public function download(Video $video)
    {
        // Se il video è su PeerTube, reindirizza all'URL PeerTube
        if ($video->isUploadedToPeerTube() && $video->peertube_url) {
            return redirect($video->peertube_url);
        }

        // Altrimenti, scarica dal file locale
        if ($video->file_path && Storage::exists($video->file_path)) {
            return Storage::download($video->file_path, $video->title . '.mp4');
        }

        return back()->withErrors(['error' => 'Video non disponibile per il download.']);
    }

    /**
     * Ottiene l'URL diretto del video PeerTube
     */
    public function getPeerTubeDirectUrl(Video $video)
    {
        if (!$video->isUploadedToPeerTube() || !$video->peertube_uuid) {
            return response()->json(['error' => 'Video non disponibile su PeerTube'], 404);
        }

        try {
            $baseUrl = \App\Models\PeerTubeConfig::getValue('peertube_url', 'https://video.slamin.it');

            // Usa l'endpoint corretto dell'API PeerTube per ottenere l'URL diretto
            // Secondo la documentazione: /api/v1/videos/{id}/download
            $downloadUrl = $baseUrl . '/api/v1/videos/' . $video->peertube_uuid . '/download';

            // Ottieni token di accesso se necessario
            $peerTubeService = new \App\Services\PeerTubeService();
            $token = $peerTubeService->getAccessToken();

            // Verifica se il video è accessibile
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token
                ])
                ->get($baseUrl . '/api/v1/videos/' . $video->peertube_uuid);

            if ($response->successful()) {
                $data = $response->json();

                // Ottieni informazioni sui file disponibili
                $files = [];
                if (isset($data['files']) && is_array($data['files'])) {
                    foreach ($data['files'] as $file) {
                        if (isset($file['fileUrl'])) {
                            $files[] = [
                                'url' => $file['fileUrl'],
                                'resolution' => $file['resolution']['label'] ?? 'unknown',
                                'size' => $file['size'] ?? 0,
                                'type' => 'direct'
                            ];
                        }
                    }
                }

                // Se non ci sono file diretti, usa l'URL di download
                if (empty($files)) {
                    $files[] = [
                        'url' => $downloadUrl,
                        'resolution' => 'best',
                        'size' => 0,
                        'type' => 'download'
                    ];
                }

                return response()->json([
                    'success' => true,
                    'files' => $files,
                    'video_info' => [
                        'title' => $data['name'] ?? $video->title,
                        'duration' => $data['duration'] ?? $video->duration,
                        'description' => $data['description'] ?? $video->description
                    ]
                ]);
            }

            return response()->json(['error' => 'Impossibile ottenere informazioni del video'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore durante il recupero del video: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Aggiunge un commento al video
     */
    public function addComment(Request $request, Video $video)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:video_comments,id',
            'timestamp' => 'nullable|integer|min:0',
        ]);

        $comment = VideoComment::create([
            'video_id' => $video->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'timestamp' => $request->timestamp,
            'status' => 'approved', // Per ora approviamo automaticamente
        ]);

        // Incrementa il contatore commenti del video
        $video->increment('comment_count');

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user'),
            'comment_count' => $video->comment_count
        ]);
    }

    /**
     * Aggiunge/rimuove un like al video
     */
    public function toggleLike(Request $request, Video $video)
    {
        $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $type = $request->type;
        $userId = Auth::id();

        // Cerca se esiste già un like dell'utente
        $existingLike = $video->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            if ($existingLike->type === $type) {
                // Rimuovi il like se è dello stesso tipo
                $existingLike->delete();
                $video->decrement($type . '_count');
                $action = 'removed';
            } else {
                // Cambia il tipo del like
                $video->decrement($existingLike->type . '_count');
                $existingLike->update(['type' => $type]);
                $video->increment($type . '_count');
                $action = 'changed';
            }
        } else {
            // Aggiungi un nuovo like
            VideoLike::create([
                'video_id' => $video->id,
                'user_id' => $userId,
                'type' => $type,
            ]);
            $video->increment($type . '_count');
            $action = 'added';
        }

        $video->refresh();

        return response()->json([
            'success' => true,
            'action' => $action,
            'like_count' => $video->like_count,
            'dislike_count' => $video->dislike_count,
            'user_like_type' => $video->likes()->where('user_id', $userId)->value('type'),
        ]);
    }

    /**
     * Aggiunge uno snap al video
     */
    public function addSnap(Request $request, Video $video)
    {
        $request->validate([
            'timestamp' => 'required|integer|min:0',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $snap = VideoSnap::create([
            'video_id' => $video->id,
            'user_id' => Auth::id(),
            'timestamp' => $request->timestamp,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'approved', // Per ora approviamo automaticamente
        ]);

        return response()->json([
            'success' => true,
            'snap' => $snap->load('user'),
        ]);
    }

    /**
     * Aggiunge/rimuove un like a uno snap
     */
    public function toggleSnapLike(Request $request, VideoSnap $snap)
    {
        $userId = Auth::id();

        // Per ora implementiamo solo l'incremento del contatore like dello snap
        $snap->incrementLikeCount();

        return response()->json([
            'success' => true,
            'like_count' => $snap->like_count,
        ]);
    }

    /**
     * Elimina un commento
     */
    public function deleteComment(VideoComment $comment)
    {
        // Verifica che l'utente possa eliminare il commento
        if (Auth::id() !== $comment->user_id && !Auth::user()->isModerator()) {
            abort(403, 'Non autorizzato');
        }

        $video = $comment->video;
        $comment->delete();

        // Decrementa il contatore commenti del video
        $video->decrement('comment_count');

        return response()->json([
            'success' => true,
            'comment_count' => $video->comment_count
        ]);
    }

    /**
     * Elimina uno snap
     */
    public function deleteSnap(VideoSnap $snap)
    {
        // Verifica che l'utente possa eliminare lo snap
        if (Auth::id() !== $snap->user_id && !Auth::user()->isModerator()) {
            abort(403, 'Non autorizzato');
        }

        $snap->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
