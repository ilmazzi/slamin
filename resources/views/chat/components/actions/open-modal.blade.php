@props([
    'component', 
    'conversation' => null,
    'widget' => false
])

<div onclick="Livewire.dispatch('openChatModal', { 
        component: '{{ $component }}', 
        arguments: { 
            conversation: {{ $conversation ? "'" . $conversation . "'" : 'null' }}, 
            widget: @js($widget)
        } 
    })">

    {{ $slot }}
</div>
