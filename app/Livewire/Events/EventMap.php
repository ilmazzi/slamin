<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;

class EventMap extends Component
{
    #[Modelable]
    public $latitude = '';
    
    #[Modelable]
    public $longitude = '';
    
    #[Reactive]
    public $fullAddress = '';

    public function render()
    {
        return view('livewire.events.event-map');
    }
}

