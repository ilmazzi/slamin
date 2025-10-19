@props(['content' => null, 'model' => null, 'type' => 'content', 'size' => 'md'])

@php
    // Supporta sia 'content' che 'model' per compatibilità
    $item = $content ?? $model;
    if (!$item) {
        throw new \Exception('social-comment-button: è necessario passare o :content o :model');
    }
    
    $commentCount = $item->comment_count ?? 0;
    $contentType = strtolower(class_basename($item));
    $uniqueId = $contentType . '_' . $item->id;

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

<div class="social-comment-btn"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $item->id }}"
     data-unique-id="{{ $uniqueId }}"
     onclick="showCommentsModal_{{ $uniqueId }}(event)"
     title="{{ __('common.comments') }}"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $buttonStyle }}"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <i class="ph-duotone ph-chat-circle {{ $iconClass }}"></i>
    <span class="comment-count {{ $textClass }}">{{ number_format($commentCount) }}</span>
</div>
