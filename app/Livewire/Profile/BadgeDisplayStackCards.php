<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;

class BadgeDisplayStackCards extends Component
{
    public $user;
    public $badges = [];
    public $currentIndex = 0;

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
            ->get();
    }

    public function nextCard()
    {
        if ($this->currentIndex < count($this->badges) - 1) {
            $this->currentIndex++;
        }
    }

    public function previousCard()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function render()
    {
        return view('livewire.profile.badge-display-stack-cards');
    }
}
