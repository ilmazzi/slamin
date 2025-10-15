<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register chat views namespace
        View::addNamespace('chat', resource_path('views/chat'));

        // Register Livewire components with 'chat' prefix (based on Wirechat structure)
        
        // Pages
        Livewire::component('chat.pages.chats', \App\Livewire\Chat\Pages\Chats::class);
        Livewire::component('chat.pages.chat', \App\Livewire\Chat\Pages\Chat::class);
        
        // Main components
        Livewire::component('chat.chats', \App\Livewire\Chat\Chats\Chats::class);
        Livewire::component('chat.chat', \App\Livewire\Chat\Chat\Chat::class);
        Livewire::component('chat.modal', \App\Livewire\Chat\Modals\Modal::class);
        
        // New chat/group
        Livewire::component('chat.new.chat', \App\Livewire\Chat\New\Chat::class);
        Livewire::component('chat.new.group', \App\Livewire\Chat\New\Group::class);
        
        // Chat components
        Livewire::component('chat.chat.info', \App\Livewire\Chat\Chat\Info::class);
        Livewire::component('chat.chat.drawer', \App\Livewire\Chat\Chat\Drawer::class);
        
        // Group components
        Livewire::component('chat.chat.group.info', \App\Livewire\Chat\Chat\Group\Info::class);
        Livewire::component('chat.chat.group.members', \App\Livewire\Chat\Chat\Group\Members::class);
        Livewire::component('chat.chat.group.permissions', \App\Livewire\Chat\Chat\Group\Permissions::class);
        Livewire::component('chat.chat.group.add-members', \App\Livewire\Chat\Chat\Group\AddMembers::class);
        
        // Widget
        Livewire::component('chat.widget', \App\Livewire\Chat\Widgets\WireChat::class);
    }
}

