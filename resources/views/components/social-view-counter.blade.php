@props(['content' => null, 'model' => null, 'type' => 'content', 'size' => 'md'])

@php
    // Supporta sia 'content' che 'model' per compatibilità
    $item = $content ?? $model;
    if (!$item) {
        throw new \Exception('social-view-counter: è necessario passare o :content o :model');
    }
    
    $viewCount = $item->views_count ?? $item->view_count ?? 0;
    $contentType = strtolower(class_basename($item));

    // Assicurati che il tipo sia supportato dal controller
    $supportedTypes = ['video', 'photo', 'poem', 'article', 'event'];
    if (!in_array($contentType, $supportedTypes)) {
        $contentType = 'video'; // fallback
    }
    
    // Dimensioni
    $sizeStyles = [
        'sm' => 'min-width: 50px; padding: 6px; gap: 2px;',
        'md' => 'min-width: 60px; padding: 8px; gap: 2px;',
        'lg' => 'min-width: 70px; padding: 10px; gap: 2px;'
    ];
    $iconSizes = [
        'sm' => 'f-s-16',
        'md' => 'f-s-20', 
        'lg' => 'f-s-24'
    ];
    $textSizes = [
        'sm' => 'f-s-10',
        'md' => 'f-s-12', 
        'lg' => 'f-s-14'
    ];
    $buttonStyle = $sizeStyles[$size] ?? $sizeStyles['md'];
    $iconClass = $iconSizes[$size] ?? $iconSizes['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div class="post-icon social-view-counter"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $item->id }}"
     style="display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $buttonStyle }}">
    <i class="ti ti-eye {{ $iconClass }}"></i>
    <span class="text-secondary view-count {{ $textClass }}">{{ number_format($viewCount) }}</span>
</div>
