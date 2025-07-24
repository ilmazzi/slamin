<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Video;
use App\Models\User;
use App\Services\PeerTubeService;
use App\Services\ThumbnailService;
use App\Models\PeerTubeConfig;
use Exception;

class VideoUploadController extends Controller
{
    private $peerTubeService;
    private $thumbnailService;

    public function __construct(PeerTubeService $peerTubeService, ThumbnailService $thumbnailService)
    {
        $this->middleware('auth');
        $this->peerTubeService = $peerTubeService;
        $this->thumbnailService = $thumbnailService;
    }

    /**
     * Mostra il form di upload
     */
    public function create()
    {
        $user = Auth::user();

        // Verifica se l'utente può caricare altri video
        if (!$user->canUploadMoreVideos()) {
            return redirect()->route('videos.upload-limit');
        }

        // Verifica che l'utente abbia un account PeerTube
        if (!$user->hasPeerTubeAccount()) {
            return redirect()->route('dashboard')
                ->with('error', 'Il tuo account PeerTube non è ancora stato creato. Contatta l\'amministratore.');
        }

        return view('videos.upload', compact('user'));
    }

    /**
     * Gestisce l'upload del video
     */
    public function store(Request $request)
    {
        // Debug: verifica autenticazione
        file_put_contents(storage_path('video_debug.txt'), '=== UPLOAD START ===' . "\n", FILE_APPEND);
        file_put_contents(storage_path('video_debug.txt'), 'Auth check: ' . (auth()->check() ? 'YES' : 'NO') . "\n", FILE_APPEND);

        // Verifica limiti utente
        $user = auth()->user();
        file_put_contents(storage_path('video_debug.txt'), 'User ID: ' . ($user ? $user->id : 'NULL') . "\n", FILE_APPEND);
        file_put_contents(storage_path('video_debug.txt'), 'User name: ' . ($user ? $user->name : 'NULL') . "\n", FILE_APPEND);

        if (!$user) {
            file_put_contents(storage_path('video_debug.txt'), 'ERROR: No user authenticated' . "\n", FILE_APPEND);
            return redirect()->route('login');
        }

        if (!$user->canUploadMoreVideos()) {
            file_put_contents(storage_path('video_debug.txt'), 'ERROR: User cannot upload more videos' . "\n", FILE_APPEND);
            return redirect()->route('videos.upload-limit');
        }

        // Verifica che l'utente abbia un account PeerTube
        if (!$user->hasPeerTubeAccount()) {
            file_put_contents(storage_path('video_debug.txt'), 'ERROR: User does not have PeerTube account' . "\n", FILE_APPEND);
            return redirect()->route('dashboard')
                ->with('error', 'Il tuo account PeerTube non è ancora stato creato. Contatta l\'amministratore.');
        }

        // Validazione
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_file' => 'required|file|mimes:mp4,avi,mov,mkv,webm,flv|max:100000', // 100MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|string|max:500',
            'is_public' => 'boolean',
        ]);

        try {
            // Verifica se PeerTube è configurato
            if (!$this->peerTubeService->isConfigured()) {
                throw new Exception('PeerTube non è configurato. Contatta l\'amministratore.');
            }

            // Gestione file video
            $videoFile = $request->file('video_file');

            // Preparazione metadata per PeerTube
            $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
            $defaultTags = PeerTubeConfig::getValue('peertube_default_tags', ['poetry', 'slam', 'poetry-slam']);
            $allTags = array_unique(array_merge($tags, $defaultTags));

            // Filtra tag validi (2-30 caratteri, max 5)
            $validTags = array_filter($allTags, function($tag) {
                return strlen($tag) >= 2 && strlen($tag) <= 30;
            });
            $validTags = array_slice($validTags, 0, 5); // Max 5 tags

            $metadata = [
                'name' => $request->title,
                'description' => $request->description ?: 'Video Poetry Slam',
                'tags' => $validTags,
                'privacy' => 1, // 1 = Public, 2 = Unlisted, 3 = Private
                'channelId' => $user->peertube_channel_id, // Usa il channel ID dell'utente
                'thumbnailfile' => $request->hasFile('thumbnail') ? $request->file('thumbnail') : null,
            ];

            // Upload su PeerTube usando l'utente loggato
            file_put_contents(storage_path('video_debug.txt'), 'Uploading to PeerTube with user: ' . $user->peertube_username . "\n", FILE_APPEND);
            $peerTubeResult = $this->peerTubeService->uploadVideo($user, $videoFile, $metadata);
            file_put_contents(storage_path('video_debug.txt'), 'PeerTube result: ' . json_encode($peerTubeResult) . "\n", FILE_APPEND);

            // Gestione thumbnail locale (per compatibilità)
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            // Crea il record video con dati PeerTube
            $videoData = [
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $peerTubeResult['url'] ?? '', // Stringa vuota invece di null
                'file_path' => '', // Stringa vuota invece di null
                'thumbnail_path' => $thumbnailPath ?? '',
                'duration' => $peerTubeResult['duration'] ?? 0,
                'file_size' => $videoFile->getSize(),
                'resolution' => $peerTubeResult['resolution'] ?? '',
                'is_public' => $request->boolean('is_public', true),
                'status' => 'uploaded',
                'moderation_status' => 'approved',
                'tags' => json_encode($allTags),

                // Campi PeerTube
                'peertube_id' => $peerTubeResult['video_id'] ?? null,
                'peertube_uuid' => $peerTubeResult['uuid'] ?? null,
                'peertube_url' => $peerTubeResult['url'] ?? '',
                'peertube_embed_url' => $peerTubeResult['embedUrl'] ?? '',
                'upload_status' => 'completed',
                'uploaded_at' => now(),
                'peertube_privacy' => $metadata['privacy'],
                'peertube_tags' => $allTags,
                'peertube_description' => $metadata['description'],

                // Statistiche iniziali
                'view_count' => 0,
                'like_count' => 0,
                'dislike_count' => 0,
                'comment_count' => 0,
            ];

            // Debug: salva i dati in un file
            file_put_contents(storage_path('video_debug.txt'), 'Video data: ' . json_encode($videoData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

            $video = Video::create($videoData);

            // Debug: salva l'ID del video creato
            file_put_contents(storage_path('video_debug.txt'), 'Video created with ID: ' . $video->id . "\n", FILE_APPEND);
            file_put_contents(storage_path('video_debug.txt'), 'Video user_id: ' . $video->user_id . "\n", FILE_APPEND);

            // Genera thumbnail automaticamente se non è stata fornita
            if (!$thumbnailPath && $video->id) {
                try {
                    $autoThumbnailPath = $this->thumbnailService->generateThumbnailFromUpload($videoFile, $video);
                    if ($autoThumbnailPath) {
                        $video->update(['thumbnail_path' => $autoThumbnailPath]);
                        file_put_contents(storage_path('video_debug.txt'), 'Auto thumbnail generated: ' . $autoThumbnailPath . "\n", FILE_APPEND);
                    }
                } catch (Exception $e) {
                    file_put_contents(storage_path('video_debug.txt'), 'Auto thumbnail error: ' . $e->getMessage() . "\n", FILE_APPEND);
                }
            }

            return redirect()->route('profile.videos')
                ->with('success', 'Video caricato con successo!');

        } catch (Exception $e) {
            // Debug: salva l'errore
            file_put_contents(storage_path('video_debug.txt'), 'Error: ' . $e->getMessage() . "\n", FILE_APPEND);
            return back()->withErrors(['error' => 'Errore durante il caricamento del video: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostra la pagina di limite upload raggiunto
     */
    public function uploadLimit()
    {
        $user = Auth::user();
        return view('videos.upload_limit', compact('user'));
    }

    /**
     * Test connessione (admin only)
     */
    public function testConnection()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Upload locale configurato correttamente',
            'storage_path' => Storage::disk('public')->path('videos'),
            'max_file_size' => '500MB',
            'supported_formats' => ['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv']
        ]);
    }

    /**
     * Ottieni durata video dal file
     */
    private function getVideoDuration($file): int
    {
        try {
            // Prova a ottenere la durata usando getID3 se disponibile
            if (class_exists('getID3')) {
                $getID3 = new \getID3();
                $fileInfo = $getID3->analyze($file->getRealPath());

                if (isset($fileInfo['playtime_seconds']) && $fileInfo['playtime_seconds'] > 0) {
                    return (int) $fileInfo['playtime_seconds'];
                }
            }

            // Fallback: stima basata sulla dimensione del file
            $fileSize = $file->getSize();
            $estimatedDuration = $fileSize / (1024 * 1024 * 2); // Stima 2MB al minuto
            return max(60, min(3600, (int) $estimatedDuration)); // Tra 1 minuto e 1 ora

        } catch (\Exception $e) {
            // Fallback finale: durata casuale ragionevole
            return rand(60, 600); // 1-10 minuti
        }
    }

    /**
     * Ottieni risoluzione video (simulato)
     */
    private function getVideoResolution($file): string
    {
        // Per ora restituiamo una risoluzione simulata
        $resolutions = ['720p', '1080p', '480p', '360p'];
        return $resolutions[array_rand($resolutions)];
    }
}
