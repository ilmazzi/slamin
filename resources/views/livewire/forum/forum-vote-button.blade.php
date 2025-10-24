<div class="vote-buttons d-flex flex-column align-items-center">
    {{-- Upvote --}}
    <button wire:click="upvote" 
            class="btn btn-sm p-1 {{ $userVote === 'upvote' ? 'text-success' : 'text-muted' }}" 
            title="{{ __('forum.upvote') }}">
        <i class="ph {{ $userVote === 'upvote' ? 'ph-fill' : '' }} ph-arrow-fat-up" style="font-size: 20px;"></i>
    </button>

    {{-- Score --}}
    <span class="fw-bold {{ $score > 0 ? 'text-success' : ($score < 0 ? 'text-danger' : 'text-muted') }}" 
          style="font-size: 14px;">
        {{ number_format($score) }}
    </span>

    {{-- Downvote --}}
    <button wire:click="downvote" 
            class="btn btn-sm p-1 {{ $userVote === 'downvote' ? 'text-danger' : 'text-muted' }}" 
            title="{{ __('forum.downvote') }}">
        <i class="ph {{ $userVote === 'downvote' ? 'ph-fill' : '' }} ph-arrow-fat-down" style="font-size: 20px;"></i>
    </button>
</div>
