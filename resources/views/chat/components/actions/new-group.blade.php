@props([
    'widget' => false
])


<x-chat::actions.open-modal
        component="wirechat.new.group"
        :widget="$widget"
        >
{{$slot}}
</x-chat::actions.open-modal>
