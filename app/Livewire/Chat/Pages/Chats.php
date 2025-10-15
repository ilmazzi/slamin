<?php

namespace App\Livewire\Chat\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class Chats extends Component
{
    #[Title('Chats')]
    public function render()
    {
        return view('chat::livewire.pages.chats')
            ->layout(config('chat.layout', 'chat::layouts.app'));

    }
}
