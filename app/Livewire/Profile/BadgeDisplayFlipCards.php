<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;

class BadgeDisplayFlipCards extends Component
{
    public $user;
    public $badges = [];
    public $flippedCards = [];
    public $autoFlipEnabled = true;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
        $this->initializeFlipped();
    }

    public function loadBadges()
    {
        $this->badges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->get();
    }

    public function initializeFlipped()
    {
        // Initialize all cards as not flipped
        foreach($this->badges as $badge) {
            $this->flippedCards[$badge->id] = false;
        }
    }

    public function toggleFlip($badgeId)
    {
        $this->flippedCards[$badgeId] = !($this->flippedCards[$badgeId] ?? false);
    }

    public function toggleAutoFlip()
    {
        $this->autoFlipEnabled = !$this->autoFlipEnabled;
    }

    public function render()
    {
        return view('livewire.profile.badge-display-flip-cards');
    }
}
