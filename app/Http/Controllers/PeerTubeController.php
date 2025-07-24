<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class PeerTubeController extends Controller
{
    private $peerTubeService;

    public function __construct(PeerTubeService $peerTubeService)
    {
        $this->peerTubeService = $peerTubeService;
        $this->middleware('auth')->except(['testConnection']);
    }

    /**
     * Test connessione PeerTube
     */
    public function testConnection()
    {
        try {
            $isConfigured = $this->peerTubeService->isConfigured();
            $isConnected = $this->peerTubeService->testConnection();

            return response()->json([
                'configured' => $isConfigured,
                'connected' => $isConnected,
                'message' => $isConfigured && $isConnected ? 'Connessione OK' : 'Errore di connessione',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'configured' => false,
                'connected' => false,
                'message' => 'Errore: ' . $e->getMessage(),
            ]);
        }
    }



    /**
     * Mostra form upload video
     */
    public function showUploadVideo()
    {
        $user = Auth::user();
        
        if (!$user->hasPeerTubeAccount()) {
            return redirect()->route('dashboard')
                ->with('error', 'Il tuo account PeerTube non è ancora stato creato. Contatta l\'amministratore.');
        }

        if (!$user->canUploadMoreVideosToPeerTube()) {
            return redirect()->route('peertube.upload-limit')
                ->with('error', 'Hai raggiunto il limite di video caricabili.');
        }

        return view('peertube.upload-video', compact('user'));
    }

    /**
     * Gestisce upload video
     */
    public function uploadVideo(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasPeerTubeAccount()) {
            return redirect()->route('dashboard')
                ->with('error', 'Il tuo account PeerTube non è ancora stato creato. Contatta l\'amministratore.');
        }

        if (!$user->canUploadMoreVideosToPeerTube()) {
            return redirect()->route('peertube.upload-limit');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_file' => 'required|file|mimes:mp4,avi,mov,mkv,webm,flv|max:100000', // 100MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|string|max:500',
            'privacy' => 'required|in:public,unlisted,private',
        ]);

        try {
            // Salva thumbnail se fornita
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('videos/thumbnails', 'public');
            }

            // Crea record video locale
            $video = Video::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
                'thumbnail_path' => $thumbnailPath,
                'privacy' => $request->privacy,
                'status' => 'uploading',
                'upload_progress' => 0,
            ]);

            // Upload su PeerTube
            $metadata = [
                'title' => $request->title,
                'description' => $request->description ?: 'Video Poetry Slam',
                'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : ['poetry', 'slam'],
                'privacy' => $this->getPrivacyValue($request->privacy),
            ];

            $peerTubeVideo = $this->peerTubeService->uploadVideo($user, $request->file('video_file'), $metadata);

            // Aggiorna record video
            $video->update([
                'peertube_video_id' => $peerTubeVideo['video_id'],
                'status' => 'published',
                'upload_progress' => 100,
                'uploaded_at' => now(),
                'published_at' => now(),
                'duration' => $peerTubeVideo['duration'] ?? null,
                'metadata' => $peerTubeVideo,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Video caricato con successo su PeerTube!');

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Errore durante l\'upload: ' . $e->getMessage()]);
        }
    }

    /**
     * Lista video utente
     */
    public function myVideos()
    {
        $user = Auth::user();
        
        if (!$user->hasPeerTubeAccount()) {
            return redirect()->route('dashboard')
                ->with('error', 'Il tuo account PeerTube non è ancora stato creato. Contatta l\'amministratore.');
        }

        $videos = $user->videos()->whereNotNull('peertube_video_id')->latest()->paginate(12);

        return view('peertube.my-videos', compact('user', 'videos'));
    }

    /**
     * Limite upload raggiunto
     */
    public function uploadLimit()
    {
        return view('peertube.upload-limit');
    }

    /**
     * Converte privacy string in valore numerico
     */
    private function getPrivacyValue(string $privacy): int
    {
        switch ($privacy) {
            case 'public':
                return 1;
            case 'unlisted':
                return 2;
            case 'private':
                return 3;
            default:
                return 1;
        }
    }
} 