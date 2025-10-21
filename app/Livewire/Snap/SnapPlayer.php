<?php

namespace App\Livewire\Snap;

use App\Models\Video;
use App\Models\VideoSnap;
use Livewire\Component;

class SnapPlayer extends Component
{
    public $video;
    public $snaps;
    public $currentTime = 0;
    public $duration = 0;
    public $showSnapModal = false;
    public $snapTimestamp = 0;
    public $snapTitle = '';
    public $snapDescription = '';
    
    protected $listeners = ['seek-video' => 'seekToTime', 'open-snap-modal' => 'openSnapModal'];
    
    public function mount(Video $video)
    {
        $this->video = $video;
        $this->snaps = $video->approvedSnaps()->orderBy('timestamp')->get();
        $this->duration = $video->duration ?? 0;
    }
    
    public function seekToTime($timestamp)
    {
        $this->dispatch('player-seek', timestamp: $timestamp);
    }
    
    public function openSnapModal($timestamp)
    {
        $this->snapTimestamp = $timestamp;
        $this->showSnapModal = true;
    }
    
    public function closeSnapModal()
    {
        $this->showSnapModal = false;
        $this->snapTitle = '';
        $this->snapDescription = '';
    }
    
    public function createSnap()
    {
        $this->validate([
            'snapTitle' => 'required|string|max:255',
            'snapDescription' => 'nullable|string|max:500',
            'snapTimestamp' => 'required|integer|min:0'
        ]);
        
        VideoSnap::create([
            'video_id' => $this->video->id,
            'user_id' => auth()->user()->id,
            'timestamp' => $this->snapTimestamp,
            'title' => $this->snapTitle,
            'description' => $this->snapDescription,
            'status' => 'approved'
        ]);
        
        $this->snapTitle = '';
        $this->snapDescription = '';
        $this->showSnapModal = false;
        
        // Ricarica gli snap
        $this->snaps = $this->video->approvedSnaps()->orderBy('timestamp')->get();
        
        $this->dispatch('snap-created');
    }
    
    public function render()
    {
        return view('livewire.snap.snap-player');
    }
}
