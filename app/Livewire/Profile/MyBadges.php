<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyBadges extends Component
{
    public $user;
    public $badges;

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
    }

    public function render()
    {
        return view('livewire.profile.my-badges')
            ->extends('layout.master')
            ->section('main-content');
    }
}
