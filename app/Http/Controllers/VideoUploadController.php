<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Video;
use App\Models\User;
use Exception;

class VideoUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            // Gestione file video
            $videoFile = $request->file('video_file');
            $videoPath = $videoFile->store('videos', 'public');

            // Gestione thumbnail
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            // Estrazione metadati video
            $duration = null;
            $fileSize = $videoFile->getSize();
            $resolution = null;

            // Crea il record video
            $videoData = [
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => Storage::url($videoPath), // Campo richiesto dalla migrazione originale
                'file_path' => $videoPath,
                'thumbnail_path' => $thumbnailPath,
                'duration' => $duration,
                'file_size' => $fileSize,
                'resolution' => $resolution,
                'is_public' => $request->boolean('is_public', true),
                'status' => 'uploaded',
                'moderation_status' => 'approved', // Approvazione automatica
                'tags' => $request->tags ? json_encode(explode(',', $request->tags)) : null,

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
     * Ottieni durata video (simulato)
     */
    private function getVideoDuration($file): int
    {
        // Per ora restituiamo un valore simulato
        // In futuro potremmo usare ffmpeg per ottenere la durata reale
        return rand(60, 600); // 1-10 minuti
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
