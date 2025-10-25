<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class BadgeNotification extends Component
{
    public $showNotification = false;
    public $badge = null;
    public $points = 0;
    public $level = 0;
    public $previousLevel = 0;
    public $leveledUp = false;

    #[On('badge-earned')]
    public function showBadgeNotification($badgeData)
    {
        $this->badge = (object) $badgeData['badge'];
        $this->points = $badgeData['points'] ?? 0;
        $this->level = $badgeData['level'] ?? 0;
        $this->previousLevel = $badgeData['previous_level'] ?? 0;
        $this->leveledUp = $this->level > $this->previousLevel;
        $this->showNotification = true;
    }

    public function closeNotification()
    {
        $this->showNotification = false;
        $this->badge = null;
    }

    public function render()
    {
        return view('livewire.badge-notification');
    }
}
