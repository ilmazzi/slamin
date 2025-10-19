<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Video;

class VideosSection extends Component
{
    public $contentType = 'recent'; // 'recent' o 'popular'

    public function toggleContent($type)
    {
        $this->contentType = $type;
    }

    public function render()
    {
        if ($this->contentType === 'popular') {
            $videos = Video::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->withCount(['views', 'likes', 'comments'])
                ->orderBy('views_count', 'desc')
                ->limit(6)
                ->get();
        } else {
            $videos = Video::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->withCount(['views', 'likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }
        
        return view('livewire.home.videos-section', [
            'videos' => $videos
        ]);
    }
}
