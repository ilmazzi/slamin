<div>
    @if($badges->count() > 0)
        <div class="badges-sidebar-list">
            @foreach($badges as $userBadge)
                @if($userBadge->badge)
                <div class="badge-sidebar-item mb-2 p-2 rounded d-flex align-items-center gap-2" style="background: rgba(var(--primary), 0.05); transition: all 0.3s ease;">
                    <img src="{{ $userBadge->badge->icon_url }}" 
                         alt="{{ $userBadge->badge->name }}" 
                         class="badge-sidebar-icon"
                         style="width: 40px; height: 40px; object-fit: contain;">
                    <div class="flex-grow-1">
                        <div class="f-w-600 f-s-13 text-dark">{{ $userBadge->badge->name }}</div>
                        <small class="text-muted f-s-11">{{ $userBadge->earned_at->format('d/m/Y') }}</small>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-3">
            <i class="ph ph-medal text-muted" style="font-size: 32px; opacity: 0.3;"></i>
            <p class="text-muted mt-2 f-s-12 mb-0">{{ __('profile.no_badges_yet') }}</p>
        </div>
    @endif

    <style>
        .badge-sidebar-item:hover {
            background: rgba(var(--primary), 0.1) !important;
            transform: translateX(5px);
        }
    </style>
</div>

