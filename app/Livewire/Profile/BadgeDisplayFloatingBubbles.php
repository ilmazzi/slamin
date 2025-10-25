<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;

class BadgeDisplayFloatingBubbles extends Component
{
    public $user;
    public $badges = [];
    public $selectedBadge = null;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
    }

    public function loadBadges()
    {
        $this->badges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->take(10) // Max 10 floating bubbles
            ->get();
    }

    public function selectBadge($userBadgeId)
    {
        $this->selectedBadge = UserBadge::with('badge')->find($userBadgeId);
    }

    public function closeDetails()
    {
        $this->selectedBadge = null;
    }

    public function render()
    {
        return view('livewire.profile.badge-display-floating-bubbles');
    }
}
