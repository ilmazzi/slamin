<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Photo;
use App\Models\Video;

class MediaIndex extends Component
{
    // Video section properties
    public $videoType = 'popular'; // 'popular' or 'recent'
    
    // Photo section properties  
    public $photoType = 'popular'; // 'popular' or 'recent'
    
    // Search properties
    public $searchQuery = '';
    public $mediaType = '';
    public $userId = '';
    public $dateFrom = '';
    public $dateTo = '';

    public function mount()
    {
        // Inizializzazione
    }

    public function toggleVideoType($type)
    {
        $this->videoType = $type;
    }

    public function togglePhotoType($type)
    {
        $this->photoType = $type;
    }

    public function searchMedia()
    {
        // La ricerca viene gestita automaticamente dai computed properties
        // che si aggiornano quando cambiano le proprietà di ricerca
        $this->dispatch('search-performed', [
            'query' => $this->searchQuery,
            'type' => $this->mediaType,
            'user' => $this->userId,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->mediaType = '';
        $this->userId = '';
        $this->dateFrom = '';
        $this->dateTo = '';
    }

    // Search results
    public function getSearchResultsProperty()
    {
        if (!$this->hasActiveSearch) {
            return [
                'videos' => collect(),
                'photos' => collect(),
                'total' => 0
            ];
        }

        $allResults = $this->buildSearchQuery();
        
        $videos = $allResults->where('type', 'video')->values();
        $photos = $allResults->where('type', 'photo')->values();
        
        return [
            'videos' => $videos,
            'photos' => $photos,
            'total' => $videos->count() + $photos->count()
        ];
    }

    private function buildSearchQuery()
    {
        $query = collect();
        
        // Se c'è una ricerca attiva
        if ($this->hasActiveSearch) {
            // Cerca video
            if ($this->mediaType === 'video' || $this->mediaType === '') {
                $videoQuery = Video::with(['user'])
                    ->withCount(['likes', 'comments', 'views'])
                    ->where('is_public', true)
                    ->where('moderation_status', 'approved');
                
                if ($this->searchQuery) {
                    $videoQuery->where(function($q) {
                        $q->where('title', 'like', '%' . $this->searchQuery . '%')
                          ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                          ->orWhereHas('user', function($userQuery) {
                              $userQuery->where('name', 'like', '%' . $this->searchQuery . '%');
                          });
                    });
                }
                
                if ($this->userId) {
                    $videoQuery->whereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->userId . '%');
                    });
                }
                
                if ($this->dateFrom) {
                    $videoQuery->where('created_at', '>=', $this->dateFrom);
                }
                
                if ($this->dateTo) {
                    $videoQuery->where('created_at', '<=', $this->dateTo);
                }
                
                $videos = $videoQuery->get()->map(function($video) {
                    $video->type = 'video';
                    return $video;
                });
                
                $query = $query->merge($videos);
            }
            
            // Cerca foto
            if ($this->mediaType === 'photo' || $this->mediaType === '') {
                $photoQuery = Photo::with(['user'])
                    ->withCount(['likes', 'comments', 'views'])
                    ->where('status', 'approved')
                    ->where('moderation_status', 'approved');
                
                if ($this->searchQuery) {
                    $photoQuery->where(function($q) {
                        $q->where('title', 'like', '%' . $this->searchQuery . '%')
                          ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                          ->orWhereHas('user', function($userQuery) {
                              $userQuery->where('name', 'like', '%' . $this->searchQuery . '%');
                          });
                    });
                }
                
                if ($this->userId) {
                    $photoQuery->whereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->userId . '%');
                    });
                }
                
                if ($this->dateFrom) {
                    $photoQuery->where('created_at', '>=', $this->dateFrom);
                }
                
                if ($this->dateTo) {
                    $photoQuery->where('created_at', '<=', $this->dateTo);
                }
                
                $photos = $photoQuery->get()->map(function($photo) {
                    $photo->type = 'photo';
                    return $photo;
                });
                
                $query = $query->merge($photos);
            }
        }
        
        return $query;
    }

    public function getHasActiveSearchProperty()
    {
        return !empty($this->searchQuery) || 
               !empty($this->mediaType) || 
               !empty($this->userId) || 
               !empty($this->dateFrom) || 
               !empty($this->dateTo);
    }

    // Video methods
    public function getMostPopularVideoProperty()
    {
        return Video::with(['user'])
            ->where('is_public', true)
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->get()
            ->sortByDesc(function($video) {
                return ($video->likes_count ?? 0) + 
                       ($video->comments_count ?? 0) + 
                       ($video->views_count ?? 0);
            })
            ->first();
    }

    public function getPopularVideosProperty()
    {
        return Video::with(['user'])
            ->where('is_public', true)
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->get()
            ->sortByDesc(function($video) {
                return ($video->likes_count ?? 0) + 
                       ($video->comments_count ?? 0) + 
                       ($video->views_count ?? 0);
            })
            ->take(6);
    }

    public function getRecentVideosProperty()
    {
        return Video::with(['user'])
            ->where('is_public', true)
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }

    // Photo methods
    public function getMostPopularPhotoProperty()
    {
        return Photo::with(['user'])
            ->where('status', 'approved')
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->get()
            ->sortByDesc(function($photo) {
                return ($photo->likes_count ?? 0) + 
                       ($photo->comments_count ?? 0) + 
                       ($photo->views_count ?? 0);
            })
            ->first();
    }

    public function getPopularPhotosProperty()
    {
        return Photo::with(['user'])
            ->where('status', 'approved')
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->get()
            ->sortByDesc(function($photo) {
                return ($photo->likes_count ?? 0) + 
                       ($photo->comments_count ?? 0) + 
                       ($photo->views_count ?? 0);
            })
            ->take(6);
    }

    public function getRecentPhotosProperty()
    {
        return Photo::with(['user'])
            ->where('status', 'approved')
            ->where('moderation_status', 'approved')
            ->withCount(['likes', 'comments', 'views'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.media.media-index', [
            'mostPopularVideo' => $this->mostPopularVideo,
            'popularVideos' => $this->popularVideos,
            'recentVideos' => $this->recentVideos,
            'mostPopularPhoto' => $this->mostPopularPhoto,
            'popularPhotos' => $this->popularPhotos,
            'recentPhotos' => $this->recentPhotos,
            'videoType' => $this->videoType,
            'photoType' => $this->photoType,
            'searchResults' => $this->searchResults,
            'hasActiveSearch' => $this->hasActiveSearch,
        ]);
    }
}
