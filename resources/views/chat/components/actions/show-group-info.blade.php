@props([
    'conversation' => null, //Should be conversation  ID (Int)
    'widget' => false
])


<x-chat::actions.open-chat-drawer 
        component="chat.chat.group.info"
        dusk="show_group_info"
        conversation="{{$conversation}}"
        :widget="$widget"
        >
{{$slot}}
</x-chat::actions.open-chat-drawer>
