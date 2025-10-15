@props([
    'widget' => false
])


<x-chat::actions.open-modal
        component="chat.new-chat"
        :widget="$widget"
        >
{{$slot}}
</x-chat::actions.open-modal>
