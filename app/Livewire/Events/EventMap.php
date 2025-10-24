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

    public function updatedFullAddress($value)
    {
        if (empty($value) || strlen($value) < 5) {
            return;
        }
        
        // Dispatch to JS to geocode
        $this->dispatch('trigger-geocode', address: $value);
    }

    public function render()
    {
        return view('livewire.events.event-map');
    }
}

