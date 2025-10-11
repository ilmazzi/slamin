<?php

namespace App\Livewire;

use Livewire\Component;

class TestEventCreation extends Component
{
    public $title = 'Test Event';

    public function render()
    {
        return view('livewire.test-event-creation')
            ->layout('layout.master');
    }
}
