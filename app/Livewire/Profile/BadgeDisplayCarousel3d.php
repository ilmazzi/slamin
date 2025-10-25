<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;

class BadgeDisplayCarousel3d extends Component
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

    public function next()
    {
        $this->currentIndex = ($this->currentIndex + 1) % count($this->badges);
    }

    public function previous()
    {
        $this->currentIndex = ($this->currentIndex - 1 + count($this->badges)) % count($this->badges);
    }

    public function goTo($index)
    {
        $this->currentIndex = $index;
    }

    public function render()
    {
        return view('livewire.profile.badge-display-carousel3d');
    }
}
