<?php

namespace App\Livewire\Events\Scoring;

use Livewire\Component;
use App\Models\Event;
use App\Services\EventScoringService;

class Rankings extends Component
{
    public $event;
    public $rankings;
    public $canCalculate = false;
    public $stats;

    public function mount(Event $event)
    {
        $this->event = $event;
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

    public function calculateRankings()
    {
        try {
            $scoringService = app(EventScoringService::class);
            $scoringService->calculateRankings($this->event);
            
            $this->loadRankings();
            $this->dispatch('swal:success', ['title' => 'Classifica Calcolata!', 'text' => 'La classifica è stata calcolata con successo!']);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Errore', 'text' => 'Errore nel calcolo della classifica: ' . $e->getMessage()]);
        }
    }

    public function awardBadges()
    {
        try {
            $scoringService = app(EventScoringService::class);
            $badgesAwarded = $scoringService->awardBadgesToWinners($this->event);
            
            $this->loadRankings();
            $this->dispatch('swal:success', ['title' => 'Badge Assegnati!', 'text' => "{$badgesAwarded} badge assegnati ai vincitori!"]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Errore', 'text' => 'Errore nell\'assegnazione badge: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.events.scoring.rankings');
    }
}
