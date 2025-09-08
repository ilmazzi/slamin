<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRequest;
use App\Models\SystemSetting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ProfileController extends Controller
{
    /**
     * Mostra il profilo pubblico di un utente
     */
    public function show($userId = null)
    {
        $user = $userId ? User::with('languages')->findOrFail($userId) : Auth::user()->load('languages');
        $isOwnProfile = Auth::check() && Auth::id() == $user->id;

        // Statistiche utente
        $stats = [
            'total_events' => $user->events()->count(),
            'participated_events' => $user->eventRequests()->where('status', 'accepted')->count(),
            'total_videos' => $user->videos()->count(),
            'total_articles' => $user->articles()->count(),
        ];

        // Eventi recenti dell'utente
        $recentEvents = $user->events()
            ->with(['organizer', 'venueOwner'])
            ->latest()
            ->take(5)
            ->get();

        // Eventi a cui ha partecipato
        $participatedEvents = $user->eventRequests()
            ->with(['event.organizer', 'event.venueOwner'])
            ->where('status', 'accepted')
            ->latest()
            ->take(5)
            ->get();

        // Video dell'utente
        $videos = $user->videos()
            ->latest()
            ->take(6)
            ->get();

        // Articoli dell'utente
        $articles = $user->articles()
            ->with(['category', 'tags'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(4);

        // Attività recenti
        $recentActivity = $this->getUserActivity($user);

        // Following dell'utente per la card Friends
        $following = $user->following()
            ->withCount(['videos', 'photos', 'poems'])
            ->limit(6)
            ->get();

        // Aggiungi lo stato follow per l'utente autenticato
        if (auth()->check()) {
            $following->each(function($followedUser) {
                $followedUser->is_followed_by_current_user = auth()->user()->isFollowing($followedUser);
            });
        }

        return view('profile.show', compact(
            'user',
            'isOwnProfile',
            'stats',
            'recentEvents',
            'participatedEvents',
            'videos',
            'articles',
            'recentActivity',
            'following'
        ));
    }

    /**
     * Mostra il form di modifica del profilo
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Aggiorna il profilo utente
     */
    public function update(Request $request)
    {
        \Log::info('Profile update request received', [
            'ajax' => $request->ajax(),
            'has_file' => $request->hasFile('profile_photo'),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'all_data' => $request->all(),
            'files' => $request->allFiles()
        ]);

        $user = Auth::user();

        // Se è una richiesta AJAX e contiene solo profile_photo, gestisci separatamente
        if ($request->ajax() && $request->hasFile('profile_photo')) {
            \Log::info('AJAX request with profile_photo detected, calling updateProfilePhoto');
            return $this->updateProfilePhoto($request, $user);
        }

        // Se è una richiesta AJAX e contiene solo banner_image, gestisci separatamente
        if ($request->ajax() && $request->hasFile('banner_image')) {
            \Log::info('AJAX request with banner_image detected, calling updateBannerImage');
            return $this->updateBannerImage($request, $user);
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'bio' => 'nullable|string|max:1000',
                'nickname' => 'nullable|string|max:50|unique:users,nickname,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'website' => 'nullable|url|max:255',
                'social_facebook' => 'nullable|url|max:255',
                'social_instagram' => 'nullable|url|max:255',
                'social_youtube' => 'nullable|url|max:255',
                'social_twitter' => 'nullable|url|max:255',
                'profile_photo' => 'nullable|image|max:' . SystemSetting::get('profile_photo_max_size', 5120),
                'banner_image' => 'nullable|image|max:' . SystemSetting::get('banner_image_max_size', 10240),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in profile update', [
                'errors' => $e->errors()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore di validazione',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        }

        // Aggiorna dati base
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'nickname' => $request->nickname,
            'phone' => $request->phone,
            'website' => $request->website,
            'social_facebook' => $request->social_facebook,
            'social_instagram' => $request->social_instagram,
            'social_youtube' => $request->social_youtube,
            'social_twitter' => $request->social_twitter,
        ]);

        // Gestione foto profilo
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            // Verifica se il file è valido
            if (!$file->isValid()) {
                \Log::error('File profile_photo non valido', [
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File non valido: ' . $file->getErrorMessage()
                    ], 400);
                }

                return redirect()->back()->withErrors(['profile_photo' => 'File non valido: ' . $file->getErrorMessage()]);
            }

            \Log::info('File profile_photo ricevuto', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension()
            ]);

            // Elimina vecchia foto se esiste
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
                \Log::info('Vecchia foto eliminata', ['path' => $user->profile_photo]);
            }

                        // Salva nuova foto
            try {
                $path = $file->store('profile-photos', 'public');
                \Log::info('Nuova foto salvata', ['path' => $path]);

                $user->update(['profile_photo' => $path]);
                \Log::info('Profilo aggiornato con nuova foto', ['user_id' => $user->id, 'profile_photo' => $path]);
            } catch (\Exception $e) {
                \Log::error('Errore durante il salvataggio della foto', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Errore durante il salvataggio: ' . $e->getMessage()
                    ], 500);
                }

                return redirect()->back()->withErrors(['profile_photo' => 'Errore durante il salvataggio: ' . $e->getMessage()]);
            }
        }

        // Se è una richiesta AJAX, restituisci JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profilo aggiornato con successo!',
                'profile_photo_url' => $user->profile_photo_url
            ]);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profilo aggiornato con successo!');
    }

    /**
     * Mostra la gestione dei video
     */
    public function videos()
    {
        $user = Auth::user();
        $videos = $user->videos()->latest()->paginate(12);

        return view('profile.videos', compact('user', 'videos'));
    }

    /**
     * Carica un nuovo video
     */
    public function uploadVideo(Request $request)
    {
        \Log::info('🎬 Inizio processo upload video', [
            'user_id' => Auth::id(),
            'request_data' => $request->only(['title', 'description', 'is_public', 'tags'])
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_file' => 'required|file|mimes:mp4,avi,mov,mkv,webm,flv|max:102400', // 100MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'tags' => 'nullable|string|max:255',
            'is_public' => 'boolean',
        ]);

        \Log::info('✅ Validazione completata');

        $user = Auth::user();

        try {
            \Log::info('🔍 Verifica account PeerTube', ['user_id' => $user->id]);

            // Verifica che l'utente abbia un account PeerTube
            if (!$user->hasPeerTubeAccount()) {
                \Log::warning('❌ Utente senza account PeerTube', ['user_id' => $user->id]);
                return back()->withErrors(['error' => 'Devi avere un account PeerTube per caricare video. Contatta l\'amministratore.']);
            }

            \Log::info('✅ Account PeerTube verificato');

            // Salva temporaneamente il file video
            \Log::info('📁 Salvataggio file video temporaneo');

            $videoFile = $request->file('video_file');
            $tempPath = $videoFile->store('temp-videos', 'local');
            $fullTempPath = Storage::disk('local')->path($tempPath);

            \Log::info('✅ File video salvato temporaneamente', [
                'original_name' => $videoFile->getClientOriginalName(),
                'size' => $videoFile->getSize(),
                'temp_path' => $tempPath,
                'full_path' => $fullTempPath
            ]);

            // Prepara i dati per PeerTube
            \Log::info('📋 Preparazione dati per PeerTube');

            $videoData = [
                'name' => $request->title,
                'description' => $request->description ?? '',
                'privacy' => $request->is_public ? 1 : 3, // 1 = Public, 3 = Private
                'category' => 1, // Music
                'licence' => 1, // Attribution
                'language' => 'it',
                'downloadEnabled' => true,
                'commentsPolicy' => 1, // Enabled
                'nsfw' => false,
            ];

            \Log::info('✅ Dati PeerTube preparati', ['video_data' => $videoData]);

            // Aggiungi tags se presenti
            if ($request->tags) {
                \Log::info('🏷️ Elaborazione tags', ['raw_tags' => $request->tags]);

                $tags = array_map('trim', explode(',', $request->tags));
                $tags = array_filter($tags, function($tag) {
                    return strlen($tag) >= 2 && strlen($tag) <= 30;
                });
                if (!empty($tags)) {
                    $videoData['tags'] = array_slice($tags, 0, 5); // Max 5 tags
                    \Log::info('✅ Tags elaborati', ['final_tags' => $videoData['tags']]);
                }
            }

            // Gestione thumbnail
            if ($request->hasFile('thumbnail')) {
                \Log::info('🖼️ Elaborazione thumbnail');

                $thumbnailPath = $request->file('thumbnail')->store('temp-thumbnails', 'local');
                $fullThumbnailPath = Storage::disk('local')->path($thumbnailPath);
                $videoData['thumbnail_path'] = $fullThumbnailPath;

                \Log::info('✅ Thumbnail salvato', [
                    'thumbnail_path' => $thumbnailPath,
                    'full_path' => $fullThumbnailPath
                ]);
            } else {
                \Log::info('ℹ️ Nessun thumbnail fornito');
            }

            // Upload su PeerTube
            \Log::info('🚀 Inizio upload su PeerTube', [
                'user_id' => $user->id,
                'file_path' => $fullTempPath,
                'video_data' => $videoData
            ]);

            $peerTubeService = new \App\Services\PeerTubeService();
            $peerTubeVideo = $peerTubeService->uploadVideo($user, $fullTempPath, $videoData);

            \Log::info('📡 Risposta upload PeerTube', [
                'success' => !empty($peerTubeVideo),
                'response' => $peerTubeVideo
            ]);

            if (!$peerTubeVideo) {
                \Log::error('❌ Upload PeerTube fallito');

                // Pulisci i file temporanei
                Storage::disk('local')->delete($tempPath);
                if (isset($thumbnailPath)) {
                    Storage::disk('local')->delete($thumbnailPath);
                }

                return back()->withErrors(['error' => 'Errore durante l\'upload su PeerTube. Riprova più tardi.']);
            }

            \Log::info('✅ Upload PeerTube completato con successo');

            // Costruisci l'URL del video PeerTube
            \Log::info('🔗 Costruzione URL video PeerTube');

            $peerTubeService = new \App\Services\PeerTubeService();
            $videoUrl = $peerTubeService->getBaseUrl() . '/videos/watch/' . ($peerTubeVideo['shortUUID'] ?? $peerTubeVideo['uuid']);

            \Log::info('✅ URL video costruito', ['video_url' => $videoUrl]);

            // Salva il video nel database locale
            \Log::info('💾 Salvataggio video nel database locale');

            $localVideoData = [
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $user->id,
                'video_url' => $videoUrl, // URL del video PeerTube
                'peertube_video_id' => $peerTubeVideo['id'],
                'peertube_uuid' => $peerTubeVideo['uuid'],
                'peertube_short_uuid' => $peerTubeVideo['shortUUID'] ?? null,
                'is_public' => $request->is_public,
                'status' => 'processing', // PeerTube processa il video
            ];

            \Log::info('📋 Dati video preparati per il database', ['local_video_data' => $localVideoData]);

            // Salva thumbnail se presente
            if ($request->hasFile('thumbnail')) {
                \Log::info('🖼️ Salvataggio thumbnail permanente');

                $thumbnailPath = $request->file('thumbnail')->store('video-thumbnails', 'public');
                $localVideoData['thumbnail'] = $thumbnailPath;

                \Log::info('✅ Thumbnail salvato permanentemente', ['thumbnail_path' => $thumbnailPath]);
            }

            \Log::info('💾 Creazione record video nel database');
            $video = $user->videos()->create($localVideoData);

            \Log::info('✅ Video creato nel database', [
                'video_id' => $video->id,
                'peertube_video_id' => $video->peertube_video_id,
                'peertube_uuid' => $video->peertube_uuid
            ]);

            // Pulisci i file temporanei
            \Log::info('🧹 Pulizia file temporanei');

            Storage::disk('local')->delete($tempPath);
            if (isset($thumbnailPath)) {
                Storage::disk('local')->delete($thumbnailPath);
            }

            \Log::info('✅ File temporanei puliti');

            \Log::info('🎉 Upload video completato con successo', [
                'video_id' => $video->id,
                'user_id' => $user->id,
                'title' => $video->title
            ]);

            return redirect()->route('profile.videos')
                ->with('success', 'Video caricato con successo! Il video sarà disponibile a breve una volta completata l\'elaborazione.');

        } catch (\Exception $e) {
            \Log::error('💥 Errore critico durante upload video', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Pulisci i file temporanei in caso di errore
            if (isset($tempPath)) {
                Storage::disk('local')->delete($tempPath);
            }
            if (isset($thumbnailPath)) {
                Storage::disk('local')->delete($thumbnailPath);
            }

            return back()->withErrors(['error' => 'Errore durante l\'upload del video: ' . $e->getMessage()]);
        }
    }

    /**
     * Elimina un video
     */
    public function deleteVideo($videoId)
    {
        try {
            $user = Auth::user();
            $video = $user->videos()->findOrFail($videoId);

            // Log per debug
            \Log::info('Eliminazione video', [
                'video_id' => $videoId,
                'user_id' => $user->id,
                'video_title' => $video->title,
                'peertube_uuid' => $video->peertube_uuid,
                'peertube_video_id' => $video->peertube_video_id
            ]);

            // Elimina il video da PeerTube se ha UUID
            $peerTubeDeleted = false;
            if ($video->peertube_uuid) {
                try {
                    $peerTubeService = new \App\Services\PeerTubeService();
                    $peerTubeDeleted = $peerTubeService->deleteVideoByUuid($video->peertube_uuid);

                    \Log::info('Tentativo eliminazione PeerTube', [
                        'video_id' => $videoId,
                        'peertube_uuid' => $video->peertube_uuid,
                        'success' => $peerTubeDeleted
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Errore eliminazione PeerTube', [
                        'video_id' => $videoId,
                        'peertube_uuid' => $video->peertube_uuid,
                        'error' => $e->getMessage()
                    ]);
                    // Continua anche se l'eliminazione da PeerTube fallisce
                }
            }

            // Elimina thumbnail se esiste
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                try {
                    Storage::disk('public')->delete($video->thumbnail);
                } catch (\Exception $e) {
                    \Log::warning('Errore eliminazione thumbnail', ['error' => $e->getMessage()]);
                    // Continua anche se l'eliminazione della thumbnail fallisce
                }
            }

            // Elimina il video dal database locale
            $video->delete();

            \Log::info('Video eliminato con successo', [
                'video_id' => $videoId,
                'peer_tube_deleted' => $peerTubeDeleted
            ]);

            $message = 'Video eliminato con successo!';
            if ($video->peertube_uuid && !$peerTubeDeleted) {
                $message .= ' (Nota: il video potrebbe essere ancora presente su PeerTube)';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'peer_tube_deleted' => $peerTubeDeleted
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Video non trovato', ['video_id' => $videoId, 'user_id' => Auth::id()]);
            return response()->json([
                'success' => false,
                'message' => 'Video non trovato'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Errore eliminazione video', [
                'video_id' => $videoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione del video. Riprova più tardi.'
            ], 500);
        }
    }

    /**
     * Mostra le attività dell'utente
     */
    public function activity()
    {
        $user = Auth::user();
        $activities = $this->getUserActivity($user, 20);

        return view('profile.activity', compact('user', 'activities'));
    }

    /**
     * Mostra i followers di un utente
     */
    public function followers($userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : Auth::user();
        $isOwnProfile = Auth::check() && Auth::id() == $user->id;

        $followers = $user->followers()
            ->withCount(['videos', 'photos', 'poems'])
            ->withCount('followers')
            ->withCount('following')
            ->paginate(20);

        // Aggiungi lo stato follow per l'utente autenticato
        if (auth()->check()) {
            $followers->each(function($follower) {
                $follower->is_followed_by_current_user = auth()->user()->isFollowing($follower);
            });
        }

        return view('profile.followers', compact('user', 'followers', 'isOwnProfile'));
    }

    /**
     * Mostra gli utenti che un utente segue
     */
    public function following($userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : Auth::user();
        $isOwnProfile = Auth::check() && Auth::id() == $user->id;

        $following = $user->following()
            ->withCount(['videos', 'photos', 'poems'])
            ->withCount('followers')
            ->withCount('following')
            ->paginate(20);

        // Aggiungi lo stato follow per l'utente autenticato
        if (auth()->check()) {
            $following->each(function($followedUser) {
                $followedUser->is_followed_by_current_user = auth()->user()->isFollowing($followedUser);
            });
        }

        return view('profile.following', compact('user', 'following', 'isOwnProfile'));
    }

    /**
     * Ottiene le attività di un utente
     */
    private function getUserActivity($user, $limit = 10)
    {
        $activities = collect();

        // Eventi organizzati
        $organizedEvents = $user->events()
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($event) {
                return [
                    'type' => 'event_organized',
                    'title' => 'Hai organizzato l\'evento "' . $event->title . '"',
                    'description' => $event->description,
                    'date' => $event->created_at,
                    'icon' => 'ph-calendar-plus',
                    'color' => 'primary',
                    'url' => route('events.show', $event->id)
                ];
            });

        // Partecipazioni a eventi
        $participations = $user->eventRequests()
            ->with('event')
            ->where('status', 'accepted')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'event_participation',
                    'title' => 'Hai partecipato all\'evento "' . $request->event->title . '"',
                    'description' => $request->event->description,
                    'date' => $request->created_at,
                    'icon' => 'ph-users',
                    'color' => 'success',
                    'url' => route('events.show', $request->event->id)
                ];
            });

        // Video caricati
        $videoUploads = $user->videos()
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($video) {
                return [
                    'type' => 'video_upload',
                    'title' => 'Hai caricato il video "' . $video->title . '"',
                    'description' => $video->description,
                    'date' => $video->created_at,
                    'icon' => 'ph-video-camera',
                    'color' => 'warning',
                    'url' => route('profile.videos')
                ];
            });

        // Articoli scritti
        $articleWrites = $user->articles()
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($article) {
                return [
                    'type' => 'article_written',
                    'title' => 'Hai scritto l\'articolo "' . $article->title . '"',
                    'description' => $article->excerpt,
                    'date' => $article->created_at,
                    'icon' => 'ph-newspaper',
                    'color' => 'info',
                    'url' => route('articles.show', $article)
                ];
            });

        // Combina e ordina per data
        $activities = $organizedEvents
            ->concat($participations)
            ->concat($videoUploads)
            ->concat($articleWrites)
            ->sortByDesc('date')
            ->take($limit);

        return $activities;
    }

    /**
     * Aggiorna solo la foto del profilo (per richieste AJAX)
     */
    private function updateProfilePhoto(Request $request, $user)
    {
        \Log::info('updateProfilePhoto chiamato', [
            'user_id' => $user->id,
            'has_file' => $request->hasFile('profile_photo'),
            'all_data' => $request->all()
        ]);

        try {
            $request->validate([
                'profile_photo' => 'required|image|max:' . SystemSetting::get('profile_photo_max_size', 5120),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in profile photo update', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore di validazione',
                'errors' => $e->errors()
            ], 422);
        }

        $file = $request->file('profile_photo');

        // Verifica se il file è valido
        if (!$file->isValid()) {
            \Log::error('File profile_photo non valido', [
                'error' => $file->getError(),
                'error_message' => $file->getErrorMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'File non valido: ' . $file->getErrorMessage()
            ], 400);
        }

        \Log::info('File profile_photo ricevuto per aggiornamento AJAX', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension()
        ]);

        // Elimina vecchia foto se esiste
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
            \Log::info('Vecchia foto eliminata', ['path' => $user->profile_photo]);
        }

        // Salva nuova foto
        try {
            $path = $file->store('profile-photos', 'public');
            \Log::info('Nuova foto salvata', ['path' => $path]);

            $user->update(['profile_photo' => $path]);
            \Log::info('Profilo aggiornato con nuova foto', ['user_id' => $user->id, 'profile_photo' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profilo aggiornata con successo',
                'profile_photo_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            \Log::error('Errore durante il salvataggio della foto', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il salvataggio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aggiorna solo l'immagine di sfondo (per richieste AJAX)
     */
    private function updateBannerImage(Request $request, $user)
    {
        \Log::info('updateBannerImage chiamato', [
            'user_id' => $user->id,
            'has_file' => $request->hasFile('banner_image'),
            'all_data' => $request->all()
        ]);

        try {
            $request->validate([
                'banner_image' => 'required|image|max:' . SystemSetting::get('banner_image_max_size', 10240),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in banner image update', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore di validazione',
                'errors' => $e->errors()
            ], 422);
        }

        $file = $request->file('banner_image');

        // Verifica se il file è valido
        if (!$file->isValid()) {
            \Log::error('File banner_image non valido', [
                'error' => $file->getError(),
                'error_message' => $file->getErrorMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'File non valido: ' . $file->getErrorMessage()
            ], 400);
        }

        \Log::info('File banner_image ricevuto per aggiornamento AJAX', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension()
        ]);

        // Elimina vecchia immagine se esiste
        if ($user->banner_image && Storage::disk('public')->exists($user->banner_image)) {
            Storage::disk('public')->delete($user->banner_image);
            \Log::info('Vecchia immagine di sfondo eliminata', ['path' => $user->banner_image]);
        }

        // Salva nuova immagine
        try {
            $path = $file->store('banner-images', 'public');
            \Log::info('Nuova immagine di sfondo salvata', ['path' => $path]);

            $user->update(['banner_image' => $path]);
            \Log::info('Profilo aggiornato con nuova immagine di sfondo', ['user_id' => $user->id, 'banner_image' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Immagine di sfondo aggiornata con successo',
                'banner_image_url' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            \Log::error('Errore durante il salvataggio dell\'immagine di sfondo', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il salvataggio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restituisce gli articoli paginati per richieste AJAX
     */
    public function getArticles($userId, Request $request)
    {
        $user = User::findOrFail($userId);
        $page = $request->get('page', 1);

        $articles = $user->articles()
            ->with(['category', 'tags'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(4, ['*'], 'page', $page);

        // Controlla se è una richiesta AJAX controllando l'header
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('profile.partials.articles-list', compact('articles', 'user'))->render();
        }

        return response()->json([
            'success' => false,
            'message' => 'Richiesta non valida'
        ], 400);
    }
}
