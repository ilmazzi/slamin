<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Carousel;

class HeroCarousel extends Component
{
    public function render()
    {
        $carousels = Carousel::active()->ordered()->get();
        
        return view('livewire.home.hero-carousel', [
            'carousels' => $carousels
        ]);
    }
}
