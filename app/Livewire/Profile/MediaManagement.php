<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Video;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class MediaManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 12;
    public $mediaType = 'all'; // all, videos, photos
    public $status = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMediaType()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function getMediaProperty()
    {
        $videos = collect();
        $photos = collect();

        if ($this->mediaType === 'all' || $this->mediaType === 'videos') {
            $videoQuery = Auth::user()->videos();

            if ($this->search) {
                $videoQuery->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->status !== 'all') {
                $videoQuery->where('status', $this->status);
            }

            $videos = $videoQuery->orderBy('created_at', 'desc')->get()->map(function($video) {
                $video->media_type = 'video';
                return $video;
            });
        }

        if ($this->mediaType === 'all' || $this->mediaType === 'photos') {
            $photoQuery = Auth::user()->photos();

            if ($this->search) {
                $photoQuery->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->status !== 'all') {
                $photoQuery->where('status', $this->status);
            }

            $photos = $photoQuery->orderBy('created_at', 'desc')->get()->map(function($photo) {
                $photo->media_type = 'photo';
                return $photo;
            });
        }

        // Merge and sort
        $media = $videos->merge($photos)->sortByDesc('created_at');

        // Manual pagination
        $currentPage = $this->getPage();
        $items = $media->forPage($currentPage, $this->perPage);
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $media->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }

    public function deleteMedia($mediaId, $mediaType)
    {
        if ($mediaType === 'video') {
            $video = Video::findOrFail($mediaId);
            
            if ($video->user_id !== Auth::id()) {
                abort(403);
            }

            if ($video->video_path && \Storage::exists($video->video_path)) {
                \Storage::delete($video->video_path);
            }

            if ($video->thumbnail_path && \Storage::exists($video->thumbnail_path)) {
                \Storage::delete($video->thumbnail_path);
            }

            $video->delete();
            $this->dispatch('swal:success', [
                'title' => __('media.success'),
                'text' => __('media.video_deleted')
            ]);
        } else {
            $photo = Photo::findOrFail($mediaId);
            
            if ($photo->user_id !== Auth::id()) {
                abort(403);
            }

            if ($photo->image_path && \Storage::exists($photo->image_path)) {
                \Storage::delete($photo->image_path);
            }

            $photo->delete();
            $this->dispatch('swal:success', [
                'title' => __('media.success'),
                'text' => __('media.photo_deleted')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.profile.media-management')
            ->extends('layout.master')
            ->section('main-content');
    }
}

