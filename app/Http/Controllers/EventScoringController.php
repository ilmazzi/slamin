<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventScoringController extends Controller
{
    /**
     * Check if user can manage event scoring
     */
    protected function authorize($event)
    {
        $user = auth()->user();
        
        // Only organizer, admin, or users with specific permission can access
        if (!$user->hasRole('admin') && $event->organizer_id !== $user->id) {
            abort(403, 'Non hai i permessi per gestire i punteggi di questo evento.');
        }
    }

    /**
     * Event scoring dashboard
     */
    public function dashboard(Event $event)
    {
        $this->authorize($event);
        
        return view('events.scoring.dashboard', compact('event'));
    }

    /**
     * Manage participants
     */
    public function participants(Event $event)
    {
        $this->authorize($event);
        
        return view('events.scoring.participants', compact('event'));
    }

    /**
     * Enter scores
     */
    public function scores(Event $event)
    {
        $this->authorize($event);
        
        return view('events.scoring.scores', compact('event'));
    }

    /**
     * View rankings
     */
    public function rankings(Event $event)
    {
        $this->authorize($event);
        
        return view('events.scoring.rankings', compact('event'));
    }
}
