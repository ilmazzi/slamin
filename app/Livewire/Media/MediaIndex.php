<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Video;
use App\Models\Photo;
use App\Models\VideoSnap;
use Illuminate\Http\Request;

class MediaIndex extends Component
{
    public $videoType = 'popular'; // 'popular' o 'recent'
    public $searchQuery = '';
    public $selectedVideo = null;
    public $showVideoModal = false;
    public $selectedPhoto = null;
    public $showPhotoModal = false;

    public function mount()
    {
        // Inizializzazione se necessario
    }

    public function toggleVideoType($type)
    {
        $this->videoType = $type;
    }

    public function openVideoModal($videoId)
    {
        $this->selectedVideo = Video::with(['user', 'likes', 'comments', 'snaps'])
            ->find($videoId);
        $this->showVideoModal = true;
    }

    public function closeVideoModal()
    {
        $this->showVideoModal = false;
        $this->selectedVideo = null;
    }

    public function openPhotoModal($photoId)
    {
        $this->selectedPhoto = Photo::with(['user', 'likes', 'comments'])
            ->find($photoId);
        $this->showPhotoModal = true;
    }

    public function closePhotoModal()
    {
        $this->showPhotoModal = false;
        $this->selectedPhoto = null;
    }

    public function render()
    {
        // Query base per video
        $videosQuery = Video::with(['user', 'likes', 'comments', 'snaps'])
            ->where('is_public', true)
            ->where('moderation_status', 'approved');

        // Video più popolare
        $mostPopularVideo = $videosQuery->withCount([
            'likes',
            'comments' => function($query) {
                $query->where('status', 'approved');
            },
            'snaps',
            'views'
        ])->get()->map(function($video) {
            $video->total_interactions = ($video->likes_count ?? 0) +
                                       ($video->comments_count ?? 0) +
                                       ($video->snaps_count ?? 0) +
                                       ($video->views_count ?? 0);
            return $video;
        })->sortByDesc('total_interactions')->first();

        // Video popolari o recenti
        if ($this->videoType === 'popular') {
            $videos = $videosQuery->withCount([
                'likes',
                'comments' => function($query) {
                    $query->where('status', 'approved');
                },
                'snaps',
                'views'
            ])->get()->map(function($video) {
                $video->total_interactions = ($video->likes_count ?? 0) +
                                           ($video->comments_count ?? 0) +
                                           ($video->snaps_count ?? 0) +
                                           ($video->views_count ?? 0);
                return $video;
            })->sortByDesc('total_interactions')->take(6);
        } else {
            $videos = $videosQuery->withCount(['likes', 'comments', 'snaps', 'views'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }

        // Foto recenti
        $recentPhotos = Photo::with(['user', 'likes', 'comments'])
            ->where('status', 'approved')
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Video più visti
        $mostViewedVideos = $videosQuery->withCount(['likes', 'comments', 'snaps', 'views'])
            ->orderBy('views_count', 'desc')
            ->take(4)
            ->get();

        // Snap più recenti
        $recentSnaps = VideoSnap::with(['video.user', 'user'])
            ->whereHas('video', function($query) {
                $query->where('is_public', true)
                      ->where('moderation_status', 'approved');
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('livewire.media.media-index', [
            'mostPopularVideo' => $mostPopularVideo,
            'videos' => $videos,
            'recentPhotos' => $recentPhotos,
            'mostViewedVideos' => $mostViewedVideos,
            'recentSnaps' => $recentSnaps,
        ]);
    }
}
