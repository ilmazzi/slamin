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

    /**
     * Get Livewire listeners for Echo events
     */
    protected function getListeners()
    {
        if (!auth()->check()) {
            return [];
        }

        return [
            "echo-private:user.{$this->getUserId()},BadgeEarned" => 'handleBadgeEarned',
        ];
    }

    /**
     * Handle badge earned broadcast event
     */
    public function handleBadgeEarned($event)
    {
        $badgeData = $event['badgeData'] ?? $event;
        
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
    }

    protected function getUserId()
    {
        return auth()->id();
    }

    public function render()
    {
        return view('livewire.badge-notification');
    }
}
