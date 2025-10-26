<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;

class BadgeDisplayPodium extends Component
{
    public $user;
    public $topBadges = [];
    public $otherBadges = [];
    public $currentRotation = 0;
    public $autoRotate = true;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
    }

    public function loadBadges()
    {
        $allBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->get();

        // Top 3 badges for podium (most recent or highest points)
        $this->topBadges = $allBadges->sortByDesc(function($ub) {
            return $ub->badge->points ?? 0;
        })->take(3)->values();

        // Rest for gallery
        $topIds = $this->topBadges->pluck('id')->toArray();
        $this->otherBadges = $allBadges->whereNotIn('id', $topIds)->values();
    }

    public function rotate()
    {
        if(count($this->topBadges) >= 3) {
            // Rotate positions
            $temp = $this->topBadges[0];
            $this->topBadges[0] = $this->topBadges[1];
            $this->topBadges[1] = $this->topBadges[2];
            $this->topBadges[2] = $temp;
            
            $this->currentRotation++;
        }
    }

    public function toggleAutoRotate()
    {
        $this->autoRotate = !$this->autoRotate;
    }

    public function render()
    {
        return view('livewire.profile.badge-display-podium');
    }
}
