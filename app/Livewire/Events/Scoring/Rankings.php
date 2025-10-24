<?php

namespace App\Livewire\Events\Scoring;

use Livewire\Component;
use App\Models\Event;
use App\Services\EventScoringService;

class Rankings extends Component
{
    public $event;
    public $isLocked = false;
    public $rankings;
    public $canCalculate = false;
    public $stats;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->isLocked = $event->status === Event::STATUS_COMPLETED;
        $this->loadRankings();
    }

    public function loadRankings()
    {
        $this->rankings = $this->event->rankings()
            ->with(['participant.user', 'badge'])
            ->ordered()
            ->get();

        // Check if we can calculate rankings
        $this->canCalculate = $this->event->scores()->exists() && $this->event->participants()->whereIn('status', ['performed', 'confirmed'])->exists();

        // Load stats
        $this->stats = [
            'total_participants' => $this->event->participants()->count(),
            'with_scores' => $this->event->participants()
                ->whereHas('scores')
                ->count(),
            'total_scores' => $this->event->scores()->count(),
            'badges_awarded' => $this->rankings->where('badge_awarded', true)->count(),
        ];
    }

    public function calculatePartialRankings()
    {
        try {
            $scoringService = app(EventScoringService::class);
            $scoringService->calculateRankings($this->event);
            
            $this->loadRankings();
            $this->dispatch('swal:success', ['title' => 'Classifica Parziale!', 'text' => 'Classifica aggiornata. L\'evento resta aperto.']);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Errore', 'text' => 'Errore nel calcolo: ' . $e->getMessage()]);
        }
    }

    public function finalizeEvent()
    {
        if ($this->isLocked) {
            $this->dispatch('swal:error', ['title' => 'Errore', 'text' => 'L\'evento è già stato completato e la classifica generata.']);
            return;
        }
        
        try {
            $scoringService = app(EventScoringService::class);
            
            // 1. Calculate final rankings
            $scoringService->calculateRankings($this->event);
            
            // 2. Award badges to winners
            $badgesAwarded = $scoringService->awardBadgesToWinners($this->event);
            
            // 3. Mark event as completed
            $this->event->status = \App\Models\Event::STATUS_COMPLETED;
            $this->event->save();
            
            $this->loadRankings();
            $this->dispatch('swal:success', [
                'title' => 'Evento Completato!', 
                'text' => "Classifica finale generata, {$badgesAwarded} badge assegnati e evento chiuso!"
            ]);
            
            // Redirect to event page after 3 seconds
            $this->dispatch('redirect-after-delay', ['url' => route('events.show', $this->event), 'delay' => 3000]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Errore', 'text' => 'Errore: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.events.scoring.rankings');
    }
}
