@props([
    'conversation' => null, //Should be conversation  ID (Int)
    'widget' => false
])


<x-chat::actions.open-chat-drawer 
        component="wirechat.chat.info"
        dusk="show_chat_info"
        conversation="{{$conversation}}"
        :widget="$widget"
        >
{{$slot}}
</x-chat::actions.open-chat-drawer>
