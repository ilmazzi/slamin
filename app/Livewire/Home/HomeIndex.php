<?php

namespace App\Livewire\Home;

use Livewire\Component;

class HomeIndex extends Component
{
    public function render()
    {
        return view('livewire.home.home-index')
            ->extends('layout.master')
            ->section('main-content');
    }
}