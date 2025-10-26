<div class="podium-container" x-data="{
    autoRotate: @entangle('autoRotate')
}" x-init="
    setInterval(() => {
        if (autoRotate) @this.rotate();
    }, 5000);
">
    @if($topBadges->count() > 0)
    
    <!-- Auto-rotate toggle -->
    <div class="text-end mb-3">
        <button wire:click="toggleAutoRotate" class="btn btn-sm btn-outline-primary">
            <i class="ph ph-{{ $autoRotate ? 'pause' : 'play' }}"></i>
            {{ $autoRotate ? 'Pausa Rotazione' : 'Avvia Rotazione' }}
        </button>
    </div>

    <div class="podium-scene">
        <!-- Spotlights -->
        <div class="spotlight spotlight-gold"></div>
        <div class="spotlight spotlight-silver"></div>
        <div class="spotlight spotlight-bronze"></div>

        <!-- Podium Structure -->
        <div class="podium-base">
            <!-- 2nd Place (Silver) - Left -->
            @if(isset($topBadges[1]) && $topBadges[1]->badge)
            <div class="podium-position second-place">
                <div class="medal-badge silver-medal">
                    <div class="medal-glow silver-glow"></div>
                    <img src="{{ $topBadges[1]->badge->icon_url }}" alt="{{ $topBadges[1]->badge->name }}">
                    <div class="medal-rank">
                        <i class="ph ph-medal"></i> 2°
                    </div>
                </div>
                <div class="podium-block silver-block">
                    <div class="block-shine"></div>
                    <h5>{{ $topBadges[1]->badge->name }}</h5>
                    <p class="f-s-12 mb-2">{{ Str::limit($topBadges[1]->badge->description, 50) }}</p>
                    <div class="badge-points">
                        <i class="ph ph-star"></i> {{ $topBadges[1]->badge->points }}
                    </div>
                </div>
            </div>
            @endif

            <!-- 1st Place (Gold) - Center -->
            @if(isset($topBadges[0]) && $topBadges[0]->badge)
            <div class="podium-position first-place">
                <div class="medal-badge gold-medal">
                    <div class="medal-glow gold-glow"></div>
                    <div class="crown">
                        <i class="ph ph-crown"></i>
                    </div>
                    <img src="{{ $topBadges[0]->badge->icon_url }}" alt="{{ $topBadges[0]->badge->name }}">
                    <div class="medal-rank">
                        <i class="ph ph-trophy"></i> 1°
                    </div>
                </div>
                <div class="podium-block gold-block">
                    <div class="block-shine"></div>
                    <h4>{{ $topBadges[0]->badge->name }}</h4>
                    <p class="f-s-13 mb-2">{{ Str::limit($topBadges[0]->badge->description, 60) }}</p>
                    <div class="badge-points">
                        <i class="ph ph-star"></i> {{ $topBadges[0]->badge->points }}
                    </div>
                </div>
            </div>
            @endif

            <!-- 3rd Place (Bronze) - Right -->
            @if(isset($topBadges[2]) && $topBadges[2]->badge)
            <div class="podium-position third-place">
                <div class="medal-badge bronze-medal">
                    <div class="medal-glow bronze-glow"></div>
                    <img src="{{ $topBadges[2]->badge->icon_url }}" alt="{{ $topBadges[2]->badge->name }}">
                    <div class="medal-rank">
                        <i class="ph ph-medal"></i> 3°
                    </div>
                </div>
                <div class="podium-block bronze-block">
                    <div class="block-shine"></div>
                    <h5>{{ $topBadges[2]->badge->name }}</h5>
                    <p class="f-s-12 mb-2">{{ Str::limit($topBadges[2]->badge->description, 50) }}</p>
                    <div class="badge-points">
                        <i class="ph ph-star"></i> {{ $topBadges[2]->badge->points }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Other Badges Gallery -->
        @if($otherBadges->count() > 0)
        <div class="gallery-section">
            <h6 class="text-center mb-3">Altri Badge</h6>
            <div class="gallery-grid">
                @foreach($otherBadges as $userBadge)
                @if($userBadge->badge)
                <div class="gallery-badge">
                    <img src="{{ $userBadge->badge->icon_url }}" alt="{{ $userBadge->badge->name }}">
                    <span class="gallery-name">{{ $userBadge->badge->name }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @else
    <div class="text-center py-5">
        <i class="ph ph-trophy f-s-48 text-muted mb-3"></i>
        <p class="text-muted">Nessun badge sul podio</p>
    </div>
    @endif

    <style>
        .podium-container {
            width: 100%;
            padding: 30px;
            background: radial-gradient(ellipse at bottom, #1e3a8a 0%, #1e293b 100%);
            border-radius: 20px;
            min-height: 600px;
            position: relative;
            overflow: hidden;
        }

        /* Spotlights */
        .spotlight {
            position: absolute;
            width: 150px;
            height: 300px;
            filter: blur(40px);
            opacity: 0.4;
            animation: spotlightPulse 3s ease-in-out infinite;
        }

        .spotlight-gold {
            background: linear-gradient(180deg, #ffd700 0%, transparent 100%);
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
        }

        .spotlight-silver {
            background: linear-gradient(180deg, #c0c0c0 0%, transparent 100%);
            top: -100px;
            left: 20%;
        }

        .spotlight-bronze {
            background: linear-gradient(180deg, #cd7f32 0%, transparent 100%);
            top: -100px;
            right: 20%;
        }

        @keyframes spotlightPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        .podium-scene {
            position: relative;
            z-index: 2;
        }

        .podium-base {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .podium-position {
            position: relative;
            animation: podiumRise 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
        }

        .first-place {
            animation-delay: 0.2s;
        }

        .second-place {
            animation-delay: 0.1s;
        }

        .third-place {
            animation-delay: 0.3s;
        }

        @keyframes podiumRise {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Medal Badges */
        .medal-badge {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .medal-badge:hover {
            transform: scale(1.15) rotate(5deg);
        }

        .gold-medal {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            box-shadow: 0 15px 40px rgba(255, 215, 0, 0.5);
        }

        .silver-medal {
            background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
            box-shadow: 0 10px 30px rgba(192, 192, 192, 0.5);
        }

        .bronze-medal {
            background: linear-gradient(135deg, #cd7f32 0%, #e0a160 100%);
            box-shadow: 0 10px 30px rgba(205, 127, 50, 0.5);
        }

        .medal-glow {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            animation: medalGlow 2s ease-in-out infinite;
        }

        .gold-glow {
            background: radial-gradient(circle, rgba(255, 215, 0, 0.6), transparent);
        }

        .silver-glow {
            background: radial-gradient(circle, rgba(192, 192, 192, 0.6), transparent);
        }

        .bronze-glow {
            background: radial-gradient(circle, rgba(205, 127, 50, 0.6), transparent);
        }

        @keyframes medalGlow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.3); opacity: 0.8; }
        }

        .medal-badge img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.3));
        }

        .crown {
            position: absolute;
            top: -25px;
            font-size: 2rem;
            color: #ffd700;
            z-index: 3;
            animation: crownFloat 2s ease-in-out infinite;
        }

        @keyframes crownFloat {
            0%, 100% { transform: translateY(0) rotate(-10deg); }
            50% { transform: translateY(-10px) rotate(10deg); }
        }

        .medal-rank {
            position: absolute;
            bottom: -10px;
            right: -10px;
            background: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 3;
        }

        /* Podium Blocks */
        .podium-block {
            width: 180px;
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 15px 15px 0 0;
            position: relative;
            overflow: hidden;
        }

        .gold-block {
            height: 200px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        }

        .silver-block {
            height: 160px;
            background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
        }

        .bronze-block {
            height: 140px;
            background: linear-gradient(135deg, #cd7f32 0%, #e0a160 100%);
        }

        .block-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: blockShine 3s infinite;
        }

        @keyframes blockShine {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        .podium-block h4,
        .podium-block h5 {
            color: #1a202c;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }

        .podium-block p {
            color: rgba(26, 32, 44, 0.8);
            font-size: 0.85rem;
        }

        .badge-points {
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 15px;
            border-radius: 15px;
            display: inline-block;
            color: #1a202c;
            font-weight: 600;
        }

        /* Gallery */
        .gallery-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid rgba(255, 255, 255, 0.1);
        }

        .gallery-section h6 {
            color: white;
            font-weight: 600;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 15px;
        }

        .gallery-badge {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-badge:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }

        .gallery-badge img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .gallery-name {
            font-size: 0.7rem;
            color: white;
            display: block;
            font-weight: 500;
        }
    </style>
</div>
