<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;

class BadgeDisplaySidebar extends Component
{
    public $user;
    public $badges = [];

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
    }

    public function loadBadges()
    {
        // Get badges selected for sidebar display (using existing columns)
        $sidebarBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->where('show_in_sidebar', true)
            ->orderBy('sidebar_order', 'asc')
            ->get();

        // If less than 5, fill with most recent badges
        if ($sidebarBadges->count() < 5) {
            $existingIds = $sidebarBadges->pluck('id')->toArray();
            $additionalBadges = UserBadge::where('user_id', $this->user->id)
                ->with('badge')
                ->whereNotIn('id', $existingIds)
                ->orderBy('earned_at', 'desc')
                ->take(5 - $sidebarBadges->count())
                ->get();
            
            $this->badges = $sidebarBadges->concat($additionalBadges);
        } else {
            $this->badges = $sidebarBadges->take(5);
        }
    }

    public function render()
    {
        return view('livewire.profile.badge-display-sidebar');
    }
}

