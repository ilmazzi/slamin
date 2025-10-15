@props([
    'widget' => false
])


<x-chat::actions.open-modal
        component="chat.new.group"
        :widget="$widget"
        >
{{$slot}}
</x-chat::actions.open-modal>
