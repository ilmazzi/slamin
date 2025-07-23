<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\Video;
use App\Models\Event;
use App\Models\User;
use App\Models\VideoSnap;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CarouselController extends Controller
{
    public function __construct()
    {
        // Middleware già applicato nelle rotte
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carousels = Carousel::orderBy('order', 'asc')->get();
        return view('admin.carousels.index', compact('carousels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contentTypes = Carousel::getAvailableContentTypes();
        return view('admin.carousels.create', compact('contentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Determina se è un contenuto referenziato o un upload
        if ($request->input('content_type') && $request->input('content_id')) {
            // Contenuto referenziato
            $request->validate([
                'content_type' => 'required|string|in:video,event,user,snap',
                'content_id' => 'required|integer',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'link_url' => 'nullable|url|max:255',
                'link_text' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
            ]);

            try {
                // Verifica che il contenuto esista
                $content = $this->getContentById($request->content_type, $request->content_id);
                if (!$content) {
                    return back()->withInput()->with('error', 'Contenuto non trovato!');
                }

                // Determina l'immagine da usare
                $imagePath = 'placeholder/placeholder-1.jpg'; // Default
                switch ($request->content_type) {
                    case 'video':
                        $imagePath = $content->thumbnail_url ?? 'placeholder/placeholder-1.jpg';
                        break;
                    case 'event':
                        $imagePath = $content->image_url ?? 'placeholder/placeholder-1.jpg';
                        break;
                    case 'user':
                        $imagePath = $content->profile_photo_url ?? 'placeholder/placeholder-1.jpg';
                        break;
                    case 'snap':
                        $imagePath = $content->thumbnail_url ?? 'placeholder/placeholder-1.jpg';
                        break;
                }

                // Usa il titolo del contenuto se non è specificato uno personalizzato
                $title = $request->title ?: $this->getContentTitle($content, $request->content_type);
                $description = $request->description ?: $this->getContentDescription($content, $request->content_type);
                $linkUrl = $request->link_url ?: $this->getContentUrl($content, $request->content_type);

                $carouselData = [
                    'title' => $title,
                    'description' => $description,
                    'link_url' => $linkUrl,
                    'link_text' => $request->link_text ?: 'Scopri di più',
                    'order' => $request->order ?? 0,
                    'is_active' => $request->boolean('is_active', true),
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'content_type' => $request->content_type,
                    'content_id' => $request->content_id,
                    'image_path' => $imagePath,
                ];

                $carousel = Carousel::create($carouselData);

                // Aggiorna la cache del contenuto
                $carousel->updateContentCache();

                return redirect()->route('admin.carousels.index')
                    ->with('success', 'Slide del carosello creata con successo!');

            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Errore durante la creazione: ' . $e->getMessage());
            }
        } else {
            // Upload tradizionale
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'video' => 'nullable|file|mimes:mp4,avi,mov,mkv,webm,flv|max:10240',
                'link_url' => 'nullable|url|max:255',
                'link_text' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
            ]);

            try {
                // Upload image
                $imagePath = $request->file('image')->store('carousel', 'public');

                // Upload video if provided
                $videoPath = null;
                if ($request->hasFile('video')) {
                    $videoPath = $request->file('video')->store('carousel', 'public');
                }

                $carouselData = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'image_path' => $imagePath,
                    'video_path' => $videoPath,
                    'link_url' => $request->link_url,
                    'link_text' => $request->link_text,
                    'order' => $request->order ?? 0,
                    'is_active' => $request->boolean('is_active', true),
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ];

                $carousel = Carousel::create($carouselData);

                return redirect()->route('admin.carousels.index')
                    ->with('success', 'Slide del carosello creata con successo!');

            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Errore durante la creazione: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Carousel $carousel)
    {
        return view('admin.carousels.show', compact('carousel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carousel $carousel)
    {
        $contentTypes = Carousel::getAvailableContentTypes();
        return view('admin.carousels.edit', compact('carousel', 'contentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carousel $carousel)
    {
        // Determina se è un contenuto referenziato o un upload
        if ($request->input('content_type') && $request->input('content_id')) {
            // Contenuto referenziato
            $request->validate([
                'content_type' => 'required|string|in:video,event,user,snap',
                'content_id' => 'required|integer',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'link_url' => 'nullable|url|max:255',
                'link_text' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
            ]);

            try {
                // Verifica che il contenuto esista
                $content = $this->getContentById($request->content_type, $request->content_id);
                if (!$content) {
                    return back()->withInput()->with('error', 'Contenuto non trovato!');
                }

                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'link_url' => $request->link_url,
                    'link_text' => $request->link_text,
                    'order' => $request->order ?? 0,
                    'is_active' => $request->boolean('is_active', true),
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'content_type' => $request->content_type,
                    'content_id' => $request->content_id,
                    // Pulisci i campi di upload se si passa a contenuto referenziato
                    'image_path' => null,
                    'video_path' => null,
                ];

                $carousel->update($data);

                // Aggiorna la cache del contenuto
                $carousel->updateContentCache();

                return redirect()->route('admin.carousels.index')
                    ->with('success', 'Slide del carosello aggiornata con successo!');

            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Errore durante l\'aggiornamento: ' . $e->getMessage());
            }
        } else {
            // Upload tradizionale
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'video' => 'nullable|file|mimes:mp4,avi,mov,mkv,webm,flv|max:10240',
                'link_url' => 'nullable|url|max:255',
                'link_text' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
            ]);

            try {
                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'link_url' => $request->link_url,
                    'link_text' => $request->link_text,
                    'order' => $request->order ?? 0,
                    'is_active' => $request->boolean('is_active', true),
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    // Pulisci i campi di contenuto referenziato se si passa a upload
                    'content_type' => null,
                    'content_id' => null,
                    'content_title' => null,
                    'content_description' => null,
                    'content_image_url' => null,
                    'content_url' => null,
                ];

                // Update image if provided
                if ($request->hasFile('image')) {
                    // Delete old image
                    if ($carousel->image_path) {
                        Storage::disk('public')->delete($carousel->image_path);
                    }
                    $data['image_path'] = $request->file('image')->store('carousel', 'public');
                }

                // Update video if provided
                if ($request->hasFile('video')) {
                    // Delete old video
                    if ($carousel->video_path) {
                        Storage::disk('public')->delete($carousel->video_path);
                    }
                    $data['video_path'] = $request->file('video')->store('carousel', 'public');
                }

                $carousel->update($data);

                return redirect()->route('admin.carousels.index')
                    ->with('success', 'Slide del carosello aggiornata con successo!');

            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Errore durante l\'aggiornamento: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carousel $carousel)
    {
        try {
            // Delete files only if they are uploaded files (not referenced content)
            if ($carousel->image_path && !$carousel->isContentReference()) {
                Storage::disk('public')->delete($carousel->image_path);
            }
            if ($carousel->video_path && !$carousel->isContentReference()) {
                Storage::disk('public')->delete($carousel->video_path);
            }

            $carousel->delete();

            return redirect()->route('admin.carousels.index')
                ->with('success', 'Slide del carosello eliminata con successo!');

        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante l\'eliminazione: ' . $e->getMessage());
        }
    }

    /**
     * Update order of carousel items
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:carousels,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Carousel::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Search for existing content
     */
    public function searchContent(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:video,event,user,snap',
            'query' => 'nullable|string|min:2',
        ]);

        $type = $request->input('type');
        $query = $request->input('query');

        switch ($type) {
            case 'video':
                $content = Video::where('moderation_status', 'approved')
                    ->where('is_public', true)
                    ->when($query, function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with('user')
                    ->orderBy('view_count', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($video) {
                        return [
                            'id' => $video->id,
                            'title' => $video->title,
                            'description' => Str::limit($video->description, 100),
                            'image_url' => $video->thumbnail_url,
                            'url' => route('videos.show', $video),
                            'user' => $video->user->getDisplayName(),
                            'views' => $video->view_count,
                            'created_at' => $video->created_at->format('d/m/Y'),
                        ];
                    });
                break;

            case 'event':
                $content = Event::where('status', 'published')
                    ->when($query, function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with('organizer')
                    ->orderBy('start_datetime', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($event) {
                        return [
                            'id' => $event->id,
                            'title' => $event->title,
                            'description' => Str::limit($event->description, 100),
                            'image_url' => $event->image_url ? asset($event->image_url) : asset('assets/images/placeholder/placeholder-1.jpg'),
                            'url' => route('events.show', $event),
                            'organizer' => $event->organizer->getDisplayName(),
                            'date' => $event->start_datetime->format('d/m/Y H:i'),
                            'location' => $event->city,
                        ];
                    });
                break;

            case 'user':
                $content = User::when($query, function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('nickname', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%");
                    })
                    ->withCount(['videos' => function($q) {
                        $q->where('moderation_status', 'approved');
                    }])
                    ->orderBy('videos_count', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($user) {
                        return [
                            'id' => $user->id,
                            'title' => $user->getDisplayName(),
                            'description' => Str::limit($user->bio, 100),
                            'image_url' => $user->profile_photo_url,
                            'url' => route('user.show', $user),
                            'videos_count' => $user->videos_count,
                            'location' => $user->location,
                            'created_at' => $user->created_at->format('d/m/Y'),
                        ];
                    });
                break;

            case 'snap':
                $content = VideoSnap::where('status', 'approved')
                    ->when($query, function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['video', 'user'])
                    ->orderBy('like_count', 'desc')
                    ->limit(20)
                    ->get()
                    ->map(function($snap) {
                        return [
                            'id' => $snap->id,
                            'title' => $snap->title ?: "Snap di {$snap->video->title}",
                            'description' => Str::limit($snap->description, 100),
                            'image_url' => $snap->thumbnail_url ? asset($snap->thumbnail_url) : asset('assets/images/placeholder/placeholder-1.jpg'),
                            'url' => route('videos.show', $snap->video) . "#snap-{$snap->id}",
                            'video_title' => $snap->video->title,
                            'user' => $snap->video->user->getDisplayName(),
                            'likes' => $snap->like_count,
                            'timestamp' => gmdate('i:s', $snap->timestamp),
                        ];
                    });
                break;

            default:
                $content = collect();
        }

        // Debug: log del primo risultato
        if ($content->count() > 0) {
            $firstResult = $content->first();
            Log::info('First result image_url: ' . ($firstResult['image_url'] ?? 'null'));
        }

        return response()->json($content);
    }

    /**
     * Get content by ID and type
     */
    protected function getContentById($type, $id)
    {
        switch ($type) {
            case 'video':
                return Video::where('moderation_status', 'approved')
                    ->where('is_public', true)
                    ->find($id);
            case 'event':
                return Event::where('status', 'published')->find($id);
            case 'user':
                return User::find($id);
            case 'snap':
                return VideoSnap::where('status', 'approved')->find($id);
            default:
                return null;
        }
    }

    protected function getContentTitle($content, $type)
    {
        switch ($type) {
            case 'video':
                return $content->title ?? 'Video senza titolo';
            case 'event':
                return $content->title ?? 'Evento senza titolo';
            case 'user':
                return $content->getDisplayName() ?? 'Utente senza nome';
            case 'snap':
                return $content->title ?: "Snap di {$content->video->title}" ?? 'Snap senza titolo';
            default:
                return 'Contenuto senza titolo';
        }
    }

    protected function getContentDescription($content, $type)
    {
        switch ($type) {
            case 'video':
                return $content->description ?? null;
            case 'event':
                return $content->description ?? null;
            case 'user':
                return $content->bio ?? null;
            case 'snap':
                return $content->description ?? null;
            default:
                return null;
        }
    }

    protected function getContentUrl($content, $type)
    {
        switch ($type) {
            case 'video':
                return route('videos.show', $content);
            case 'event':
                return route('events.show', $content);
            case 'user':
                return route('user.show', $content);
            case 'snap':
                return route('videos.show', $content->video) . "#snap-{$content->id}";
            default:
                return null;
        }
    }
}
