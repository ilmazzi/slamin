<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class VideoManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 12;
    public $status = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function getVideosProperty()
    {
        $query = Auth::user()->videos();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    public function deleteVideo(Video $video)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete video file
        if ($video->video_path && \Storage::exists($video->video_path)) {
            \Storage::delete($video->video_path);
        }

        // Delete thumbnail
        if ($video->thumbnail_path && \Storage::exists($video->thumbnail_path)) {
            \Storage::delete($video->thumbnail_path);
        }

        $video->delete();
        session()->flash('success', __('videos.deleted_successfully'));
    }

    public function toggleStatus(Video $video)
    {
        if ($video->user_id !== Auth::id()) {
            abort(403);
        }

        $video->update([
            'status' => $video->status === 'approved' ? 'pending' : 'approved'
        ]);

        session()->flash('success', __('videos.status_updated'));
    }

    public function render()
    {
        return view('livewire.profile.video-management', [
            'videos' => $this->videos,
        ]);
    }
}


