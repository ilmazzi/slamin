<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;
use App\Models\Badge;

class BadgeDisplayConstellation extends Component
{
    public $user;
    public $earnedBadges = [];
    public $allBadges = [];
    public $selectedBadge = null;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
    }

    public function loadBadges()
    {
        // Earned badges
        $this->earnedBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->get();

        // All portal badges for constellation
        $this->allBadges = Badge::active()
            ->portal()
            ->orderBy('category')
            ->orderBy('criteria_value')
            ->get();
    }

    public function selectBadge($badgeId)
    {
        $userBadge = $this->earnedBadges->firstWhere('badge_id', $badgeId);
        
        if ($userBadge) {
            $this->selectedBadge = (object)[
                'badge' => $userBadge->badge,
                'earned_at' => $userBadge->earned_at,
                'is_earned' => true
            ];
        } else {
            $badge = $this->allBadges->firstWhere('id', $badgeId);
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
        return view('livewire.profile.badge-display-constellation');
    }
}
