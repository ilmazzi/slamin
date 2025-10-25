<div class="carousel-3d-container">
    @if($badges->count() > 0)
    <div class="carousel-3d-wrapper">
        <!-- Carousel Stage -->
        <div class="carousel-stage" x-data="{ currentIndex: @entangle('currentIndex') }">
            @foreach($badges as $index => $userBadge)
            @if($userBadge->badge)
            <div class="carousel-item"
                 :class="{ 
                     'active': currentIndex === {{ $index }},
                     'prev': currentIndex === {{ ($index + 1) % count($badges) }},
                     'next': currentIndex === {{ ($index - 1 + count($badges)) % count($badges) }}
                 }"
                 style="--item-index: {{ $index }}; --total-items: {{ count($badges) }};">
                
                <div class="badge-card-3d">
                    <div class="badge-shine"></div>
                    <img src="{{ $userBadge->badge->icon_url }}" 
                         alt="{{ $userBadge->badge->name }}"
                         class="badge-icon-3d">
                    <h4 class="badge-title-3d">{{ $userBadge->badge->name }}</h4>
                    <p class="badge-desc-3d">{{ Str::limit($userBadge->badge->description, 60) }}</p>
                    <div class="badge-stats-3d">
                        <span class="badge-points">
                            <i class="ph ph-star"></i> {{ $userBadge->badge->points }}
                        </span>
                        <span class="badge-date">
                            <i class="ph ph-calendar"></i> {{ $userBadge->earned_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Navigation Arrows -->
        <button wire:click="previous" class="carousel-nav carousel-nav-left">
            <i class="ph ph-caret-left"></i>
        </button>
        <button wire:click="next" class="carousel-nav carousel-nav-right">
            <i class="ph ph-caret-right"></i>
        </button>

        <!-- Dots Indicator -->
        <div class="carousel-dots">
            @foreach($badges as $index => $badge)
            <button wire:click="goTo({{ $index }})" 
                    class="carousel-dot"
                    :class="{ 'active': currentIndex === {{ $index }} }">
            </button>
            @endforeach
        </div>

        <!-- Counter -->
        <div class="carousel-counter">
            <span x-text="currentIndex + 1"></span> / {{ count($badges) }}
        </div>
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="ph ph-medal f-s-48 mb-3"></i>
        <p>Nessun badge guadagnato ancora</p>
    </div>
    @endif

    <style>
        .carousel-3d-container {
            width: 100%;
            padding: 20px;
        }

        .carousel-3d-wrapper {
            position: relative;
            width: 100%;
            height: 450px;
            perspective: 1200px;
        }

        .carousel-stage {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
        }

        .carousel-item {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 400px;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform-style: preserve-3d;
        }

        .carousel-item.active {
            transform: translate(-50%, -50%) translateZ(0) scale(1);
            opacity: 1;
            z-index: 10;
        }

        .carousel-item.prev {
            transform: translate(-150%, -50%) translateZ(-300px) rotateY(45deg) scale(0.7);
            opacity: 0.5;
            z-index: 5;
        }

        .carousel-item.next {
            transform: translate(50%, -50%) translateZ(-300px) rotateY(-45deg) scale(0.7);
            opacity: 0.5;
            z-index: 5;
        }

        .carousel-item:not(.active):not(.prev):not(.next) {
            transform: translate(-50%, -50%) translateZ(-600px) scale(0.4);
            opacity: 0;
            pointer-events: none;
        }

        .badge-card-3d {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 25px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .badge-shine {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .badge-icon-3d {
            width: 150px;
            height: 150px;
            object-fit: contain;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
            animation: iconFloat 3s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .badge-title-3d {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .badge-desc-3d {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .badge-stats-3d {
            display: flex;
            gap: 20px;
            color: white;
            font-weight: 600;
        }

        .badge-stats-3d i {
            margin-right: 5px;
        }

        /* Navigation */
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.5rem;
            color: #667eea;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 20;
        }

        .carousel-nav:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-nav-left {
            left: 20px;
        }

        .carousel-nav-right {
            right: 20px;
        }

        /* Dots */
        .carousel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 20;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            border: 2px solid rgba(102, 126, 234, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: #667eea;
            transform: scale(1.3);
        }

        .carousel-counter {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            color: #667eea;
            z-index: 20;
        }

        /* Details Modal */
        .badge-details-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .stat-box {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
        }
    </style>
</div>
