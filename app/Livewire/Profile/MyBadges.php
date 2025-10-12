<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyBadges extends Component
{
    public $user;
    public $badges;
    public $featuredBadgeIds = [];
    public $displayOrders = [];

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
        
        // Load current featured states and display orders
        $this->featuredBadgeIds = $this->badges->where('is_featured', true)->pluck('id')->toArray();
        $this->displayOrders = $this->badges->pluck('display_order', 'id')->toArray();
    }

    public function toggleFeatured($userBadgeId)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            $userBadge->is_featured = !$userBadge->is_featured;
            $userBadge->save();
            
            $this->loadBadges();
            
            $message = $userBadge->is_featured ? 'Badge ora visibile!' : 'Badge nascosto!';
            $this->dispatch('swal:success', ['title' => 'Aggiornato!', 'text' => $message]);
        }
    }

    public function updateDisplayOrder($userBadgeId, $newOrder)
    {
        $userBadge = $this->user->userBadges()->find($userBadgeId);
        
        if ($userBadge) {
            $userBadge->display_order = (int) $newOrder;
            $userBadge->save();
            
            $this->loadBadges();
        }
    }

    public function saveOrders()
    {
        foreach ($this->displayOrders as $userBadgeId => $order) {
            $userBadge = $this->user->userBadges()->find($userBadgeId);
            if ($userBadge) {
                $userBadge->display_order = (int) $order;
                $userBadge->save();
            }
        }
        
        $this->dispatch('swal:success', ['title' => 'Salvato!', 'text' => 'Ordine badge aggiornato!']);
        $this->loadBadges();
    }

    public function render()
    {
        return view('livewire.profile.my-badges');
    }
}
