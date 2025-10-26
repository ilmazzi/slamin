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
        // Get only the 3 rotating badges selected by user
        $rotatingBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->whereIn('rotating_position', [1, 2, 3])
            ->orderBy('rotating_position')
            ->get();

        // If no rotating badges selected, show most recent 3
        if ($rotatingBadges->count() < 3) {
            $this->badges = UserBadge::where('user_id', $this->user->id)
                ->with('badge')
                ->orderBy('earned_at', 'desc')
                ->take(3)
                ->get();
        } else {
            $this->badges = $rotatingBadges;
        }
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
