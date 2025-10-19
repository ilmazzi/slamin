<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Video;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsSection extends Component
{
    public function render()
    {
        $stats = [
            'total_videos' => Video::where('moderation_status', 'approved')->count(),
            'total_events' => Event::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_views' => DB::table('unified_views')->count(),
        ];
        
        return view('livewire.home.statistics-section', [
            'stats' => $stats
        ]);
    }
}
