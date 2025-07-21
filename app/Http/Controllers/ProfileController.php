<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRequest;

class ProfileController extends Controller
{
    /**
     * Mostra il profilo pubblico di un utente
     */
    public function show($userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : Auth::user();
        $isOwnProfile = Auth::check() && Auth::id() == $user->id;

        // Statistiche utente
        $stats = [
            'total_events' => $user->events()->count(),
            'participated_events' => $user->eventRequests()->where('status', 'accepted')->count(),
            'pending_requests' => $user->eventRequests()->where('status', 'pending')->count(),
            'total_videos' => $user->videos()->count(),
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

        // Attività recenti
        $recentActivity = $this->getUserActivity($user);

        return view('profile.show', compact(
            'user',
            'isOwnProfile',
            'stats',
            'recentEvents',
            'participatedEvents',
            'videos',
            'recentActivity'
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
        $user = Auth::user();

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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
            // Elimina vecchia foto se esiste
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Salva nuova foto
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->update(['profile_photo' => $path]);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_url' => 'required|url|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $user = Auth::user();

        $videoData = [
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'user_id' => $user->id,
        ];

        // Gestione thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('video-thumbnails', 'public');
            $videoData['thumbnail'] = $path;
        }

        $user->videos()->create($videoData);

        return redirect()->route('profile.videos')
            ->with('success', 'Video caricato con successo!');
    }

    /**
     * Elimina un video
     */
    public function deleteVideo($videoId)
    {
        $user = Auth::user();
        $video = $user->videos()->findOrFail($videoId);

        // Elimina thumbnail se esiste
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('profile.videos')
            ->with('success', 'Video eliminato con successo!');
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

        // Combina e ordina per data
        $activities = $organizedEvents
            ->concat($participations)
            ->concat($videoUploads)
            ->sortByDesc('date')
            ->take($limit);

        return $activities;
    }
}
