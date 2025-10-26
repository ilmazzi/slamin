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
        // Get badges selected for profile display (using existing columns)
        $profileBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->where('show_in_profile', true)
            ->orderBy('profile_order', 'asc')
            ->get();

        // If less than 3, fill with most recent badges
        if ($profileBadges->count() < 3) {
            $existingIds = $profileBadges->pluck('id')->toArray();
            $additionalBadges = UserBadge::where('user_id', $this->user->id)
                ->with('badge')
                ->whereNotIn('id', $existingIds)
                ->orderBy('earned_at', 'desc')
                ->take(3 - $profileBadges->count())
                ->get();
            
            $this->badges = $profileBadges->concat($additionalBadges);
        } else {
            $this->badges = $profileBadges->take(3);
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
