<div class="stack-cards-container" x-data="{ 
    currentIndex: @entangle('currentIndex'),
    swipeCard(direction) {
        if (direction === 'left') @this.nextCard();
        if (direction === 'right') @this.previousCard();
    }
}">
    @if($badges->count() > 0)
    <div class="stack-wrapper">
        <div class="cards-stack">
            @foreach($badges as $index => $userBadge)
            @if($userBadge->badge)
            <div class="stack-card"
                 :class="{ 
                     'top': currentIndex === {{ $index }},
                     'peek-1': currentIndex === {{ $index - 1 }},
                     'peek-2': currentIndex === {{ $index - 2 }},
                     'hidden': currentIndex > {{ $index }}
                 }"
                 style="--card-index: {{ $index }};">
                
                <div class="card-content">
                    <!-- Badge Icon -->
                    <div class="badge-icon-large mb-4">
                        <div class="icon-glow"></div>
                        <img src="{{ $userBadge->badge->icon_url }}" 
                             alt="{{ $userBadge->badge->name }}">
                    </div>

                    <!-- Badge Info -->
                    <h3 class="badge-name mb-2">{{ $userBadge->badge->name }}</h3>
                    <p class="badge-description mb-4">{{ $userBadge->badge->description }}</p>

                    <!-- Stats -->
                    <div class="badge-stats-row">
                        <div class="stat-item">
                            <i class="ph ph-star text-warning f-s-24"></i>
                            <div>
                                <strong>{{ $userBadge->badge->points }}</strong>
                                <small class="d-block text-muted">{{ __('profile.points') }}</small>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="ph ph-calendar text-primary f-s-24"></i>
                            <div>
                                <strong>{{ $userBadge->earned_at->format('d/m') }}</strong>
                                <small class="d-block text-muted">{{ $userBadge->earned_at->format('Y') }}</small>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="ph ph-tag text-success f-s-24"></i>
<div>
                                <strong>{{ ucfirst($userBadge->badge->category) }}</strong>
                                <small class="d-block text-muted">{{ __('profile.category') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Swipe Hint -->
                    <div class="swipe-hint" x-show="currentIndex === {{ $index }}">
                        <i class="ph ph-swipe-left me-2"></i>
                        {{ __('profile.swipe_or_arrows') }}
                        <i class="ph ph-swipe-right ms-2"></i>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Navigation Buttons -->
        <button wire:click="previousCard" 
                class="stack-nav stack-nav-left"
                :disabled="currentIndex === 0">
            <i class="ph ph-caret-left"></i>
        </button>
        <button wire:click="nextCard" 
                class="stack-nav stack-nav-right"
                :disabled="currentIndex === {{ count($badges) - 1 }}">
            <i class="ph ph-caret-right"></i>
        </button>

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar-fill" 
                 :style="`width: ${((currentIndex + 1) / {{ count($badges) }}) * 100}%`">
            </div>
        </div>

        <!-- Counter -->
        <div class="stack-counter">
            <span x-text="currentIndex + 1"></span> / {{ count($badges) }}
        </div>
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="ph ph-medal f-s-48 mb-3"></i>
        <p>{{ __('profile.no_badges_earned') }}</p>
    </div>
    @endif

    <style>
        .stack-cards-container {
            width: 100%;
            padding: 30px 20px;
        }

        .stack-wrapper {
            position: relative;
            width: 100%;
            height: 550px;
        }

        .cards-stack {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stack-card {
            position: absolute;
            width: 90%;
            max-width: 400px;
            height: 500px;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform-origin: center bottom;
        }

        .stack-card.top {
            transform: translateY(0) scale(1) rotate(0deg);
            z-index: 30;
            opacity: 1;
        }

        .stack-card.peek-1 {
            transform: translateY(-20px) scale(0.95) rotate(0deg);
            z-index: 20;
            opacity: 0.8;
            filter: brightness(0.9);
        }

        .stack-card.peek-2 {
            transform: translateY(-35px) scale(0.9) rotate(0deg);
            z-index: 10;
            opacity: 0.6;
            filter: brightness(0.8);
        }

        .stack-card.hidden {
            transform: translateX(-150%) scale(0.8) rotate(-15deg);
            opacity: 0;
            z-index: 0;
            pointer-events: none;
        }

        .card-content {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .card-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, rgba(var(--primary), 1) 0%, rgba(var(--primary), 0.7) 100%);
        }

        .badge-icon-large {
            position: relative;
            width: 180px;
            height: 180px;
        }

        .icon-glow {
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            bottom: -20px;
            background: radial-gradient(circle, rgba(var(--primary), 0.2), transparent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        .badge-icon-large img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2));
        }

        .badge-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            text-align: center;
        }

        .badge-description {
            font-size: 1rem;
            color: #718096;
            text-align: center;
            max-width: 350px;
        }

        .badge-stats-row {
            display: flex;
            gap: 30px;
            justify-content: center;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .swipe-hint {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.85rem;
            color: #a0aec0;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-5px); }
        }

        /* Navigation */
        .stack-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 2rem;
            color: rgba(var(--primary), 1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 40;
        }

        .stack-nav:hover:not(:disabled) {
            background: white;
            transform: translateY(-50%) scale(1.15);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .stack-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .stack-nav-left {
            left: 10px;
        }

        .stack-nav-right {
            right: 10px;
        }

        /* Progress Bar */
        .progress-bar-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(var(--primary), 0.15);
            z-index: 40;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, rgba(var(--primary), 1) 0%, rgba(var(--primary), 0.7) 100%);
            transition: width 0.5s ease;
        }

        .stack-counter {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            color: rgba(var(--primary), 1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 40;
            font-size: 1.1rem;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .stack-cards-container {
                padding: 15px;
            }

            .stack-wrapper {
                height: 500px;
                padding: 0 10px;
            }

            .stack-card {
                width: 100%;
                max-width: 100%;
                height: 450px;
            }

            .card-content {
                padding: 30px 20px;
                border-radius: 20px;
            }

            .badge-icon-large {
                width: 140px;
                height: 140px;
            }

            .badge-name {
                font-size: 1.4rem;
            }

            .badge-description {
                font-size: 0.9rem;
                max-width: 100%;
            }

            .badge-stats-row {
                gap: 15px;
                flex-wrap: wrap;
            }

            .stat-item {
                font-size: 0.85rem;
            }

            .stack-nav {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .stack-nav-left {
                left: 5px;
            }

            .stack-nav-right {
                right: 5px;
            }

            .stack-counter {
                top: 15px;
                padding: 8px 16px;
                font-size: 0.95rem;
            }

            .swipe-hint {
                font-size: 0.75rem;
                bottom: 15px;
            }
        }
    </style>
</div>
