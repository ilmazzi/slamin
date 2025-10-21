<?php

namespace App\Livewire\Snap;

use App\Models\Video;
use App\Models\VideoSnap;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
    public $videoDirectUrl = null;
    
    protected $listeners = ['seek-video' => 'seekToTime', 'open-snap-modal' => 'openSnapModal'];
    
    public function mount(Video $video)
    {
        $this->video = $video;
        $this->snaps = $video->approvedSnaps()->orderBy('timestamp')->get();
        $this->duration = $video->duration ?? 0;
        
        // Ottieni l'URL diretto del video (server-side)
        if ($this->video->isUploadedToPeerTube() && $this->video->isReadyOnPeerTube()) {
            $this->videoDirectUrl = $this->getDirectVideoUrl();
        } else {
            $this->videoDirectUrl = $this->video->video_url;
        }
    }
    
    /**
     * Ottiene l'URL diretto del video da PeerTube (server-side)
     */
    private function getDirectVideoUrl(): ?string
    {
        try {
            $baseUrl = config('services.peertube.url', 'https://video.slamin.it');
            $response = Http::timeout(10)->get($baseUrl . '/api/v1/videos/' . $this->video->peertube_uuid);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Ottieni il primo file disponibile (migliore qualità)
                if (isset($data['files'][0]['fileUrl'])) {
                    Log::info('URL diretto PeerTube ottenuto', [
                        'video_id' => $this->video->id,
                        'url' => $data['files'][0]['fileUrl']
                    ]);
                    return $data['files'][0]['fileUrl'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Errore ottenimento URL diretto PeerTube', [
                'video_id' => $this->video->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return null;
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
            'user_id' => Auth::id(),
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
