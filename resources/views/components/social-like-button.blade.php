@props(['content' => null, 'model' => null, 'type' => 'content', 'size' => 'md'])

@php
    // Supporta sia 'content' che 'model' per compatibilità
    $item = $content ?? $model;
    if (!$item) {
        throw new \Exception('social-like-button: è necessario passare o :content o :model');
    }
    
    $isLiked = auth()->check() ? $item->isLikedBy(auth()->user()) : false;
    $likeCount = $item->like_count ?? 0;
    $contentType = strtolower(class_basename($item));

    // Dimensioni
    $sizeStyles = [
        'sm' => 'min-width: 50px; padding: 6px; gap: 2px;',
        'md' => 'min-width: 60px; padding: 8px; gap: 2px;',
        'lg' => 'min-width: 70px; padding: 10px; gap: 2px;'
    ];
    $iconSizes = [
        'sm' => 'width: 20px; height: 20px;',
        'md' => 'width: 24px; height: 24px;',
        'lg' => 'width: 28px; height: 28px;'
    ];
    $textSizes = [
        'sm' => 'f-s-10',
        'md' => 'f-s-12',
        'lg' => 'f-s-14'
    ];
    $buttonStyle = $sizeStyles[$size] ?? $sizeStyles['md'];
    $iconStyle = $iconSizes[$size] ?? $iconSizes['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
@endphp

@if(auth()->check())
<div class="social-like-btn"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $item->id }}"
     onclick="toggleSocialLike(this)"
     title="{{ $isLiked ? 'Rimuovi like' : 'Metti like' }}"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $buttonStyle }}"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <img src="{{ asset('assets/images/like.svg') }}" alt="{{ __('common.like') }}" style="{{ $iconStyle }} {{ $isLiked ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);' }}">
    <span class="text-secondary like-count {{ $textClass }}">{{ number_format($likeCount) }}</span>
</div>
@else
<div class="social-like-counter"
     style="display: flex; flex-direction: column; align-items: center; border-radius: 8px; {{ $buttonStyle }}">
    <img src="{{ asset('assets/images/like.svg') }}" alt="{{ __('common.like') }}" style="{{ $iconStyle }} filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%); opacity: 0.6;">
    <span class="text-secondary like-count {{ $textClass }}">{{ number_format($likeCount) }}</span>
</div>
@endif
