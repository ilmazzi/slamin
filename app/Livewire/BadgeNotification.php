<?php

namespace App\Livewire;

use Livewire\Component;

class BadgeNotification extends Component
{
    public $showNotification = false;
    public $badge = null;
    public $points = 0;
    public $level = 0;
    public $previousLevel = 0;
    public $leveledUp = false;

    public function mount()
    {
        // Check if there's a badge earned in session
        $this->checkSessionForBadge();
    }

    public function checkSessionForBadge()
    {
        if (session()->has('badge_earned')) {
            $badgeData = session()->pull('badge_earned'); // Get and remove from session
            $this->handleBadgeEarned($badgeData);
        }
    }

    /**
     * Handle badge earned event
     */
    public function handleBadgeEarned($badgeData)
    {
        $this->badge = (object) ($badgeData['badge'] ?? []);
        $this->points = $badgeData['points'] ?? 0;
        $this->level = $badgeData['level'] ?? 0;
        $this->previousLevel = $badgeData['previous_level'] ?? 0;
        $this->leveledUp = $badgeData['leveled_up'] ?? ($this->level > $this->previousLevel);
        $this->showNotification = true;
    }

    public function closeNotification()
    {
        $this->showNotification = false;
        $this->badge = null;
        
        // Clear session if still present
        session()->forget('badge_earned');
    }

    public function render()
    {
        return view('livewire.badge-notification');
    }
}
