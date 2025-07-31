@props(['content', 'type' => 'content'])

@php
    $snapCount = $content->snaps()->count() ?? 0;
    $contentType = strtolower(class_basename($content));
@endphp

<div class="social-snap-btn"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $content->id }}"
     onclick="showSnapModal()"
     title="Crea snap"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <img src="{{ asset('assets/images/snap.png') }}" alt="Snap" style="width: 24px; height: 24px;">
    <span class="text-secondary snap-count f-s-12">{{ number_format($snapCount) }}</span>
</div>
