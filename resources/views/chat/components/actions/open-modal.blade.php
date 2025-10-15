@props([
    'component', 
    'conversation' => null,
    'widget' => false
])

<div  
    x-data 
    @click="$dispatch('openChatModal', { 
        component: '{{ $component }}', 
        arguments: { 
            conversation: '{{$conversation ?? null }}', 
            widget: {{ $widget ? 'true' : 'false' }}
        } 
    })">

    {{ $slot }}
</div>
