<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\Attributes\Modelable;

class EventMap extends Component
{
    #[Modelable]
    public $latitude = '';
    
    #[Modelable]
    public $longitude = '';
    
    public $city = '';
    public $address = '';
    public $postcode = '';
    public $country = 'IT';

    public function render()
    {
        return view('livewire.events.event-map');
    }
}

