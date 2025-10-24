<?php

namespace App\Livewire\Events\Scoring;

use Livewire\Component;
use App\Models\Event;

class Dashboard extends Component
{
    public $event;
    public $isLocked = false;
    public $stats;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->isLocked = $event->status === Event::STATUS_COMPLETED;
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_participants' => $this->event->participants()->count(),
            'confirmed_participants' => $this->event->participants()->where('status', 'confirmed')->count(),
            'performed_participants' => $this->event->participants()->where('status', 'performed')->count(),
            'total_rounds' => $this->event->rounds()->count(),
            'total_scores' => $this->event->scores()->count(),
            'has_rankings' => $this->event->rankings()->exists(),
            'winners_count' => $this->event->rankings()->winners()->count(),
        ];
    }

    public function render()
    {
        return view('livewire.events.scoring.dashboard')
            ->extends('layout.master')
            ->section('main-content');
    }
}
