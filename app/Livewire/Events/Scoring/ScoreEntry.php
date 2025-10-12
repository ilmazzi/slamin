<?php

namespace App\Livewire\Events\Scoring;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventRound;
use App\Models\EventScore;
use App\Models\EventParticipant;

class ScoreEntry extends Component
{
    public $event;
    public $rounds;
    public $participants;
    public $selectedRound = 1;
    
    // Round management
    public $showRoundModal = false;
    public $editingRound = null;
    public $round_number;
    public $round_name;
    public $scoring_type = 'average';

    // Score entry
    public $scores = []; // [participant_id => score_value]

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->loadData();
    }

    public function loadData()
    {
        $this->rounds = $this->event->rounds()->ordered()->get();
        $this->participants = $this->event->participants()
            ->whereIn('status', ['confirmed', 'performed'])
            ->orderByPerformance()
            ->get();
        
        // Load existing scores for selected round
        $this->loadScoresForRound();
        
        // Auto-create first round if none exist
        if ($this->rounds->count() === 0) {
            $this->createDefaultRound();
        }
    }

    public function loadScoresForRound()
    {
        $existingScores = EventScore::where('event_id', $this->event->id)
            ->where('round', $this->selectedRound)
            ->get()
            ->keyBy('participant_id');

        $this->scores = [];
        foreach ($this->participants as $participant) {
            $this->scores[$participant->id] = $existingScores->get($participant->id)?->score ?? '';
        }
    }

    public function updatedSelectedRound()
    {
        $this->loadScoresForRound();
    }

    public function saveScore($participantId)
    {
        $score = $this->scores[$participantId] ?? null;
        
        if ($score === '' || $score === null) {
            return;
        }

        // Validate score (0.0 - 10.0 with 1 decimal)
        if ($score < 0 || $score > 10) {
            $this->dispatch('swal:warning', ['title' => 'Errore', 'text' => 'Il punteggio deve essere tra 0.0 e 10.0!']);
            return;
        }

        EventScore::updateOrCreate(
            [
                'event_id' => $this->event->id,
                'participant_id' => $participantId,
                'round' => $this->selectedRound,
                'judge_id' => auth()->id(),
            ],
            [
                'score' => round($score, 1),
                'scored_at' => now(),
            ]
        );

        $this->dispatch('swal:success', ['title' => 'Salvato!', 'text' => 'Punteggio salvato!']);
    }

    public function createDefaultRound()
    {
        EventRound::create([
            'event_id' => $this->event->id,
            'round_number' => 1,
            'name' => 'Turno Unico',
            'scoring_type' => 'average',
            'order' => 1,
        ]);
        
        $this->loadData();
    }

    public function openRoundModal()
    {
        $this->resetRoundForm();
        $this->editingRound = null;
        $this->showRoundModal = true;
    }

    public function editRound($roundId)
    {
        $round = EventRound::findOrFail($roundId);
        $this->editingRound = $round;
        $this->round_number = $round->round_number;
        $this->round_name = $round->name;
        $this->scoring_type = $round->scoring_type;
        $this->showRoundModal = true;
    }

    public function saveRound()
    {
        $this->validate([
            'round_number' => 'required|integer|min:1',
            'round_name' => 'required|string|max:255',
            'scoring_type' => 'required|in:average,sum,best_of,elimination',
        ]);

        $data = [
            'event_id' => $this->event->id,
            'round_number' => $this->round_number,
            'name' => $this->round_name,
            'scoring_type' => $this->scoring_type,
            'order' => $this->round_number,
        ];

        if ($this->editingRound) {
            $this->editingRound->update($data);
        } else {
            EventRound::create($data);
        }

        $this->dispatch('swal:success', ['title' => 'Salvato!', 'text' => 'Turno salvato con successo!']);
        $this->showRoundModal = false;
        $this->loadData();
    }

    public function deleteRound($roundId)
    {
        $round = EventRound::findOrFail($roundId);
        $round->delete();
        
        $this->dispatch('swal:success', ['title' => 'Eliminato!', 'text' => 'Turno eliminato!']);
        $this->loadData();
    }

    private function resetRoundForm()
    {
        $this->round_number = ($this->rounds->max('round_number') ?? 0) + 1;
        $this->round_name = '';
        $this->scoring_type = 'average';
    }

    public function render()
    {
        return view('livewire.events.scoring.score-entry');
    }
}
