<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyBadges extends Component
{
    public $user;
    public $badges;
    public $rotatingBadgeIds = [];
    public $sidebarBadgeIds = [];
    public $rotatingOrders = [];
    public $sidebarOrders = [];

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
        $this->rotatingBadgeIds = $this->badges->where('show_in_profile', true)->pluck('id')->toArray();
        $this->sidebarBadgeIds = $this->badges->where('show_in_sidebar', true)->pluck('id')->toArray();
        $this->rotatingOrders = $this->badges->pluck('profile_order', 'id')->toArray();
        $this->sidebarOrders = $this->badges->pluck('sidebar_order', 'id')->toArray();
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
            
            // Refresh sidebar badges
            $this->dispatch('refresh-sidebar');
        }
    }

    public function toggleRotating($userBadgeId)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            // Check if we're trying to enable and already have 3 rotating
            if (!$userBadge->show_in_profile) {
                $rotatingCount = $this->user->userBadges()->where('show_in_profile', true)->count();
                if ($rotatingCount >= 3) {
                    $this->dispatch('swal:warning', ['title' => __('profile.limit_reached'), 'text' => __('profile.max_rotating_badges')]);
                    return;
                }
            }

            $userBadge->show_in_profile = !$userBadge->show_in_profile;
            $userBadge->save();
            
            $this->loadBadges();
            
            $message = $userBadge->show_in_profile ? __('profile.badge_rotating_visible') : __('profile.badge_rotating_removed');
            $this->dispatch('swal:success', ['title' => __('profile.updated'), 'text' => $message]);
        }
    }

    public function updateSidebarOrder($userBadgeId, $newOrder)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            $userBadge->sidebar_order = (int) $newOrder;
            $userBadge->save();
            $this->loadBadges();
            
            // Refresh sidebar badges
            $this->dispatch('refresh-sidebar');
        }
    }

    public function updateRotatingOrder($userBadgeId, $newOrder)
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
        return view('livewire.profile.my-badges')
            ->extends('layout.master')
            ->section('main-content');
    }
}
