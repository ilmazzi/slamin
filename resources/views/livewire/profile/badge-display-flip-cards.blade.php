<div class="flip-cards-container">
    @if($badges->count() > 0)
    <!-- Auto-flip toggle -->
    <div class="text-end mb-3">
        <button wire:click="toggleAutoFlip" class="btn btn-sm btn-outline-primary">
            <i class="ph ph-{{ $autoFlipEnabled ? 'pause' : 'play' }}"></i>
            {{ $autoFlipEnabled ? 'Pausa Auto-Flip' : 'Avvia Auto-Flip' }}
        </button>
    </div>

    <div class="flip-cards-grid" x-data="{ 
        autoFlip: @entangle('autoFlipEnabled'),
        flipRandomCard() {
            const cards = Array.from(document.querySelectorAll('.flip-card-inner'));
            if (cards.length > 0 && this.autoFlip) {
                const randomCard = cards[Math.floor(Math.random() * cards.length)];
                randomCard.classList.toggle('flipped');
            }
        }
    }" x-init="
        setInterval(() => {
            if (autoFlip) flipRandomCard();
        }, 5000);
    ">
        @foreach($badges as $index => $userBadge)
        @if($userBadge->badge)
        <div class="flip-card" 
             style="animation-delay: {{ $index * 0.1 }}s;"
             wire:key="flip-card-{{ $userBadge->id }}">
            <div class="flip-card-inner {{ isset($flippedCards[$userBadge->id]) && $flippedCards[$userBadge->id] ? 'flipped' : '' }}"
                 wire:click="toggleFlip({{ $userBadge->id }})">
                
                <!-- Front Face -->
                <div class="flip-card-front">
                    <div class="card-glow"></div>
                    <div class="badge-icon-wrapper">
                        <img src="{{ $userBadge->badge->icon_url }}" 
                             alt="{{ $userBadge->badge->name }}"
                             class="flip-badge-icon">
                    </div>
                    <div class="flip-card-footer">
                        <i class="ph ph-flip-horizontal text-muted f-s-12"></i>
                    </div>
                </div>

                <!-- Back Face -->
                <div class="flip-card-back">
                    <div class="back-content">
                        <h5 class="mb-2">{{ $userBadge->badge->name }}</h5>
                        <p class="f-s-13 mb-3">{{ $userBadge->badge->description }}</p>
                        
                        <div class="back-stats">
                            <div class="stat-item">
                                <i class="ph ph-star text-warning"></i>
                                <span>{{ $userBadge->badge->points }} pts</span>
                            </div>
                            <div class="stat-item">
                                <i class="ph ph-calendar text-primary"></i>
                                <span>{{ $userBadge->earned_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="badge-category">
                            <span class="badge bg-light-primary f-s-11">
                                {{ ucfirst($userBadge->badge->category) }}
                            </span>
                        </div>
                    </div>
                    <div class="flip-card-footer">
                        <i class="ph ph-flip-horizontal text-muted f-s-12"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="ph ph-medal f-s-48 mb-3"></i>
        <p>Nessun badge guadagnato ancora</p>
    </div>
    @endif

    <style>
        .flip-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .flip-card {
            height: 280px;
            perspective: 1000px;
            animation: cardAppear 0.5s ease-out backwards;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(30px) rotateX(-15deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform-style: preserve-3d;
            cursor: pointer;
        }

        .flip-card-inner.flipped {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .flip-card-front {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            overflow: hidden;
        }

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.2), transparent);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .badge-icon-wrapper {
            position: relative;
            z-index: 2;
        }

        .flip-badge-icon {
            width: 120px;
            height: 120px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .flip-card:hover .flip-badge-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .flip-card-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: rotateY(180deg);
        }

        .back-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .back-content h5 {
            color: white;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .back-content p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
        }

        .back-stats {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .flip-card-footer {
            margin-top: auto;
            padding-top: 10px;
        }

        .badge-category {
            margin-top: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .flip-cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .flip-card {
                height: 220px;
            }

            .flip-badge-icon {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</div>
