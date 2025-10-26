<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;
use App\Models\Badge;

class BadgeDisplayWallGrid extends Component
{
    public $user;
    public $earnedBadges = [];
    public $lockedBadges = [];
    public $selectedBadge = null;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
    }

    public function loadBadges()
    {
        // Get earned badges
        $this->earnedBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->get();

        // Get all badges to show locked ones
        $earnedBadgeIds = $this->earnedBadges->pluck('badge_id')->toArray();
        
        $this->lockedBadges = Badge::active()
            ->whereNotIn('id', $earnedBadgeIds)
            ->orderBy('category')
            ->orderBy('criteria_value')
            ->take(12) // Show next 12 to unlock
            ->get();
    }

    public function selectBadge($badgeId, $isEarned = true)
    {
        if ($isEarned) {
            $userBadge = UserBadge::with('badge')->where('badge_id', $badgeId)->where('user_id', $this->user->id)->first();
            $this->selectedBadge = $userBadge ? (object)[
                'badge' => $userBadge->badge,
                'earned_at' => $userBadge->earned_at,
                'is_earned' => true
            ] : null;
        } else {
            $badge = Badge::find($badgeId);
            $this->selectedBadge = $badge ? (object)[
                'badge' => $badge,
                'is_earned' => false
            ] : null;
        }
    }

    public function closeDetails()
    {
        $this->selectedBadge = null;
    }

    public function render()
    {
        return view('livewire.profile.badge-display-wall-grid');
    }
}
