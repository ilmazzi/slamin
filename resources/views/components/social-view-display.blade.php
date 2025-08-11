@props(['content', 'type' => 'content'])

@php
    $viewCount = $content->view_count ?? 0;
@endphp

<div title="Visualizzazioni" style="display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px;">
    <i class="ti ti-eye f-s-24 text-muted"></i>
    <span class="text-secondary f-s-12">{{ number_format($viewCount) }}</span>
</div> 
