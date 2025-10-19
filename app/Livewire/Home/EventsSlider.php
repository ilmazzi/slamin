<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Event;

class EventsSlider extends Component
{
    public function render()
    {
        $recentEvents = Event::where('status', 'published')
            ->where('end_datetime', '>=', now())
            ->with('organizer')
            ->withCount(['views', 'likes', 'comments'])
            ->orderBy('start_datetime', 'asc')
            ->limit(6)
            ->get();
        
        return view('livewire.home.events-slider', [
            'recentEvents' => $recentEvents
        ]);
    }
}
