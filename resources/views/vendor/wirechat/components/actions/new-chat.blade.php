@props([
    'widget' => false,
    'panel'=>null
])


<x-wirechat::actions.open-modal
        component="chat.new.chat"
        :widget="$widget"
        :panel="$panel"
        >
{{$slot}}
</x-wirechat::actions.open-modal>
