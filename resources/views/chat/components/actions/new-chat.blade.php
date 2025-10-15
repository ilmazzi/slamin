@props([
    'widget' => false
])


<x-chat::actions.open-modal
        component="wirechat.new.chat"
        :widget="$widget"
        >
{{$slot}}
</x-chat::actions.open-modal>
