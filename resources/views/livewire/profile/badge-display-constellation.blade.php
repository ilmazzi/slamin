<div class="constellation-container">
    <div class="star-field">
        <!-- Background stars -->
        @for($i = 0; $i < 100; $i++)
        <div class="bg-star" style="
            left: {{ rand(0, 100) }}%;
            top: {{ rand(0, 100) }}%;
            animation-delay: {{ rand(0, 3000) / 1000 }}s;
            animation-duration: {{ rand(2, 4) }}s;
        "></div>
        @endfor

        <!-- SVG for connections -->
        <svg class="constellation-lines" width="100%" height="100%">
            @php
            $positions = [];
            $earnedIds = $earnedBadges->pluck('badge_id')->toArray();
            @endphp
            
            @foreach($allBadges->take(20) as $i => $badge)
                @php
                // Calculate position in circular pattern
                $angle = ($i / 20) * 2 * pi();
                $radius = 35; // percentage
                $centerX = 50;
                $centerY = 50;
                $x = $centerX + $radius * cos($angle);
                $y = $centerY + $radius * sin($angle);
                $positions[$badge->id] = ['x' => $x, 'y' => $y];
                $isEarned = in_array($badge->id, $earnedIds);
                @endphp
                
                <!-- Draw connection lines -->
                @if($i > 0 && $badge->category === $allBadges[$i-1]->category)
                <line x1="{{ $positions[$allBadges[$i-1]->id]['x'] }}%" 
                      y1="{{ $positions[$allBadges[$i-1]->id]['y'] }}%"
                      x2="{{ $x }}%" 
                      y2="{{ $y }}%"
                      stroke="{{ $isEarned ? 'rgba(102, 126, 234, 0.6)' : 'rgba(255, 255, 255, 0.2)' }}"
                      stroke-width="2"
                      class="constellation-line {{ $isEarned ? 'earned-line' : '' }}" />
                @endif
            @endforeach
        </svg>

        <!-- Badge Stars -->
        @foreach($allBadges->take(20) as $i => $badge)
            @php
            $pos = $positions[$badge->id] ?? ['x' => 50, 'y' => 50];
            $isEarned = in_array($badge->id, $earnedIds);
            @endphp
            
            <div class="star-badge {{ $isEarned ? 'earned-star' : 'locked-star' }}"
                 wire:click="selectBadge({{ $badge->id }})"
                 style="
                     left: {{ $pos['x'] }}%;
                     top: {{ $pos['y'] }}%;
                     animation-delay: {{ $i * 0.1 }}s;
                 ">
                
                <div class="star-outer-ring {{ $isEarned ? 'earned-ring' : '' }}"></div>
                <div class="star-inner">
                    @if($isEarned)
                    <div class="star-particles">
                        @for($p = 0; $p < 8; $p++)
                        <div class="particle" style="--angle: {{ $p * 45 }}deg;"></div>
                        @endfor
                    </div>
                    @endif
                    
                    <img src="{{ $badge->icon_url }}" 
                         alt="{{ $badge->name }}"
                         class="star-icon {{ !$isEarned ? 'locked-icon' : '' }}">
                    
                    @if(!$isEarned)
                    <div class="lock-icon">
                        <i class="ph ph-lock"></i>
                    </div>
                    @endif
                </div>
                
                <div class="star-label">
                    {{ $isEarned ? Str::limit($badge->name, 15) : '???' }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Badge Details -->
    @if($selectedBadge)
    <div class="constellation-modal" wire:click="closeDetails">
        <div class="constellation-detail" @click.stop>
            <button wire:click="closeDetails" class="close-constellation-btn">
                <i class="ph ph-x"></i>
            </button>

            <div class="text-center">
                @if($selectedBadge->is_earned)
                <div class="earned-star-badge mb-3">
                    <i class="ph ph-star-four text-warning f-s-48"></i>
                </div>
                @else
                <div class="locked-star-badge mb-3">
                    <i class="ph ph-lock text-muted f-s-48"></i>
                </div>
                @endif

                <img src="{{ $selectedBadge->badge->icon_url }}" 
                     alt="{{ $selectedBadge->badge->name }}"
                     class="constellation-detail-icon {{ !$selectedBadge->is_earned ? 'locked' : '' }}">

                <h3 class="mt-3">{{ $selectedBadge->is_earned ? $selectedBadge->badge->name : 'Badge Bloccato' }}</h3>
                <p class="text-muted">{{ $selectedBadge->badge->description }}</p>

                @if($selectedBadge->is_earned)
                <div class="detail-stats mt-4">
                    <div class="detail-stat-box">
                        <i class="ph ph-star text-warning f-s-24"></i>
                        <strong>{{ $selectedBadge->badge->points }}</strong>
                        <small>Punti</small>
                    </div>
                    <div class="detail-stat-box">
                        <i class="ph ph-calendar text-primary f-s-24"></i>
                        <strong>{{ $selectedBadge->earned_at->format('d/m/Y') }}</strong>
                        <small>Sbloccato</small>
                    </div>
                </div>
                @else
                <div class="unlock-requirement mt-4">
                    <i class="ph ph-target me-2"></i>
                    Sblocca con: <strong>{{ $selectedBadge->badge->criteria_value }}x {{ $selectedBadge->badge->criteria_type }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <style>
        .constellation-container {
            width: 100%;
            height: 600px;
            background: radial-gradient(ellipse at center, #1a1f3a 0%, #0a0e1a 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background stars */
        .bg-star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: starTwinkle ease-in-out infinite;
        }

        @keyframes starTwinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); }
        }

        .star-field {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .constellation-lines {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .constellation-line.earned-line {
            animation: lineGlow 2s ease-in-out infinite;
        }

        @keyframes lineGlow {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        /* Badge Stars */
        .star-badge {
            position: absolute;
            transform: translate(-50%, -50%);
            cursor: pointer;
            z-index: 10;
            animation: starAppear 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
        }

        @keyframes starAppear {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0) rotate(-180deg);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1) rotate(0deg);
            }
        }

        .star-outer-ring {
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .earned-ring {
            border-color: #667eea;
            animation: ringPulse 2s ease-in-out infinite;
        }

        @keyframes ringPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.3); opacity: 0.5; }
        }

        .star-inner {
            position: relative;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .earned-star .star-inner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 0 30px rgba(102, 126, 234, 0.8);
        }

        .star-badge:hover .star-inner {
            transform: scale(1.3);
        }

        .star-particles {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            animation: particleFloat 2s ease-in-out infinite;
            transform-origin: center;
            transform: translate(-50%, -50%) rotate(var(--angle)) translateY(-40px);
        }

        @keyframes particleFloat {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }

        .star-icon {
            width: 40px;
            height: 40px;
            object-fit: contain;
            position: relative;
            z-index: 2;
        }

        .locked-icon {
            filter: grayscale(100%) brightness(2);
            opacity: 0.3;
        }

        .lock-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 1.5rem;
            z-index: 3;
        }

        .star-label {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 8px;
            font-size: 0.7rem;
            color: white;
            white-space: nowrap;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Modal */
        .constellation-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .constellation-detail {
            background: linear-gradient(135deg, #1a1f3a 0%, #2d3748 100%);
            border-radius: 25px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            color: white;
            position: relative;
            animation: detailAppear 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes detailAppear {
            from {
                transform: scale(0.3) rotate(-15deg);
                opacity: 0;
            }
            to {
                transform: scale(1) rotate(0);
                opacity: 1;
            }
        }

        .close-constellation-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
        }

        .close-constellation-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .constellation-detail-icon {
            width: 150px;
            height: 150px;
            object-fit: contain;
            animation: iconSpin 1s ease-out;
        }

        .constellation-detail-icon.locked {
            filter: grayscale(100%) brightness(1.5);
            opacity: 0.4;
        }

        @keyframes iconSpin {
            from { transform: rotate(-360deg) scale(0); }
            to { transform: rotate(0) scale(1); }
        }

        .detail-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .detail-stat-box {
            text-align: center;
        }

        .unlock-requirement {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 15px;
            font-size: 1rem;
        }
    </style>
</div>
