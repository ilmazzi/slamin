<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyBadges extends Component
{
    public $user;
    public $badges;
    public $sidebarBadgeIds = [];
    public $profileBadgeIds = [];
    public $sidebarOrders = [];
    public $profileOrders = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadBadges();
    }

    public function loadBadges()
    {
        $this->badges = $this->user->userBadges()
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->get();
        
        // Load current states
        $this->sidebarBadgeIds = $this->badges->where('show_in_sidebar', true)->pluck('id')->toArray();
        $this->profileBadgeIds = $this->badges->where('show_in_profile', true)->pluck('id')->toArray();
        $this->sidebarOrders = $this->badges->pluck('sidebar_order', 'id')->toArray();
        $this->profileOrders = $this->badges->pluck('profile_order', 'id')->toArray();
    }

    public function toggleSidebar($userBadgeId)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            // Check if we're trying to enable and already have 3 in sidebar
            if (!$userBadge->show_in_sidebar) {
                $sidebarCount = $this->user->userBadges()->where('show_in_sidebar', true)->count();
                if ($sidebarCount >= 3) {
                    $this->dispatch('swal:warning', ['title' => 'Limite Raggiunto', 'text' => 'Puoi mostrare massimo 3 badge nella sidebar!']);
                    return;
                }
            }

            $userBadge->show_in_sidebar = !$userBadge->show_in_sidebar;
            $userBadge->save();
            
            $this->loadBadges();
            
            $message = $userBadge->show_in_sidebar ? 'Badge visibile nella sidebar!' : 'Badge rimosso dalla sidebar!';
            $this->dispatch('swal:success', ['title' => 'Aggiornato!', 'text' => $message]);
        }
    }

    public function toggleProfile($userBadgeId)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            $userBadge->show_in_profile = !$userBadge->show_in_profile;
            $userBadge->save();
            
            $this->loadBadges();
            
            $message = $userBadge->show_in_profile ? 'Badge visibile nel profilo!' : 'Badge rimosso dal profilo!';
            $this->dispatch('swal:success', ['title' => 'Aggiornato!', 'text' => $message]);
        }
    }

    public function updateSidebarOrder($userBadgeId, $newOrder)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            $userBadge->sidebar_order = (int) $newOrder;
            $userBadge->save();
            $this->loadBadges();
        }
    }

    public function updateProfileOrder($userBadgeId, $newOrder)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            $userBadge->profile_order = (int) $newOrder;
            $userBadge->save();
            $this->loadBadges();
        }
    }

    public function render()
    {
        return view('livewire.profile.my-badges');
    }
}
