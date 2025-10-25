<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyBadges extends Component
{
    public $user;
    public $badges;
    
    // Posizioni badge rotanti (1, 2, 3)
    public $rotatingPosition1 = null;
    public $rotatingPosition2 = null;
    public $rotatingPosition3 = null;
    
    // Posizioni badge sidebar (1, 2, 3)
    public $sidebarPosition1 = null;
    public $sidebarPosition2 = null;
    public $sidebarPosition3 = null;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadBadges();
        $this->loadCurrentPositions();
    }

    public function loadBadges()
    {
        $this->badges = $this->user->userBadges()
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->get();
    }
    
    public function loadCurrentPositions()
    {
        // Carica badge rotanti attuali
        $rotatingBadges = $this->user->userBadges()
            ->where('show_in_profile', true)
            ->orderBy('profile_order')
            ->get();
        
        foreach ($rotatingBadges as $badge) {
            $position = $badge->profile_order + 1; // 0,1,2 -> 1,2,3
            if ($position == 1) $this->rotatingPosition1 = $badge->id;
            if ($position == 2) $this->rotatingPosition2 = $badge->id;
            if ($position == 3) $this->rotatingPosition3 = $badge->id;
        }
        
        // Carica badge sidebar attuali
        $sidebarBadges = $this->user->userBadges()
            ->where('show_in_sidebar', true)
            ->orderBy('sidebar_order')
            ->get();
        
        foreach ($sidebarBadges as $badge) {
            $position = $badge->sidebar_order + 1; // 0,1,2 -> 1,2,3
            if ($position == 1) $this->sidebarPosition1 = $badge->id;
            if ($position == 2) $this->sidebarPosition2 = $badge->id;
            if ($position == 3) $this->sidebarPosition3 = $badge->id;
        }
    }

    public function setRotatingPosition($userBadgeId, $position)
    {
        // Prima rimuovi TUTTI i badge da tutte le posizioni rotanti
        $this->user->userBadges()->update([
            'show_in_profile' => false,
            'profile_order' => null,
        ]);
        
        // Poi imposta i 3 badge scelti
        if ($this->rotatingPosition1) {
            $this->user->userBadges()->find($this->rotatingPosition1)->update([
                'show_in_profile' => true,
                'profile_order' => 0,
            ]);
        }
        if ($this->rotatingPosition2) {
            $this->user->userBadges()->find($this->rotatingPosition2)->update([
                'show_in_profile' => true,
                'profile_order' => 1,
            ]);
        }
        if ($this->rotatingPosition3) {
            $this->user->userBadges()->find($this->rotatingPosition3)->update([
                'show_in_profile' => true,
                'profile_order' => 2,
            ]);
        }
        
        $this->dispatch('swal:success', [
            'title' => __('profile.updated'),
            'text' => __('profile.rotating_badges_updated')
        ]);
    }

    public function setSidebarPosition($userBadgeId, $position)
    {
        // Prima rimuovi TUTTI i badge da tutte le posizioni sidebar
        $this->user->userBadges()->update([
            'show_in_sidebar' => false,
            'sidebar_order' => null,
        ]);
        
        // Poi imposta i 3 badge scelti
        if ($this->sidebarPosition1) {
            $this->user->userBadges()->find($this->sidebarPosition1)->update([
                'show_in_sidebar' => true,
                'sidebar_order' => 0,
            ]);
        }
        if ($this->sidebarPosition2) {
            $this->user->userBadges()->find($this->sidebarPosition2)->update([
                'show_in_sidebar' => true,
                'sidebar_order' => 1,
            ]);
        }
        if ($this->sidebarPosition3) {
            $this->user->userBadges()->find($this->sidebarPosition3)->update([
                'show_in_sidebar' => true,
                'sidebar_order' => 2,
            ]);
        }
        
        $this->dispatch('swal:success', [
            'title' => __('profile.updated'),
            'text' => __('profile.sidebar_badges_updated')
        ]);
        
        $this->dispatch('refresh-sidebar');
    }

    public function render()
    {
        return view('livewire.profile.my-badges')
            ->extends('layout.master')
            ->section('main-content');
    }
}
