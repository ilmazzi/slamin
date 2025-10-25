<div class="floating-bubbles-container">
    <div class="bubbles-wrapper">
        @foreach($badges as $index => $userBadge)
        @if($userBadge->badge)
        <div class="bubble bubble-{{ $index }}" 
             wire:click="selectBadge({{ $userBadge->id }})"
             style="
                --bubble-delay: {{ $index * 0.3 }}s;
                --bubble-duration: {{ rand(8, 12) }}s;
                --bubble-x: {{ rand(10, 90) }}%;
                --bubble-y-start: {{ rand(60, 90) }}%;
                --bubble-y-end: {{ rand(10, 40) }}%;
                --bubble-size: {{ rand(80, 120) }}px;
             ">
            <div class="bubble-glow"></div>
            <div class="bubble-inner">
                <img src="{{ $userBadge->badge->icon_url }}" 
                     alt="{{ $userBadge->badge->name }}"
                     class="bubble-icon">
                <div class="bubble-name">{{ $userBadge->badge->name }}</div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <!-- Badge Details Modal -->
    @if($selectedBadge && $selectedBadge->badge)
    <div class="badge-details-overlay" wire:click="closeDetails">
        <div class="badge-details-card" @click.stop>
            <button wire:click="closeDetails" class="close-btn">
                <i class="ph ph-x"></i>
            </button>
            
            <div class="text-center">
                <img src="{{ $selectedBadge->badge->icon_url }}" 
                     alt="{{ $selectedBadge->badge->name }}"
                     class="badge-detail-icon mb-3">
                
                <h3 class="mb-2">{{ $selectedBadge->badge->name }}</h3>
                <p class="text-muted mb-3">{{ $selectedBadge->badge->description }}</p>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-box">
                            <i class="ph ph-star text-warning f-s-24"></i>
                            <div class="f-w-600">{{ $selectedBadge->badge->points }}</div>
                            <small class="text-muted">Punti</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <i class="ph ph-calendar text-primary f-s-24"></i>
                            <div class="f-w-600">{{ $selectedBadge->earned_at->format('d/m/Y') }}</div>
                            <small class="text-muted">Guadagnato</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .floating-bubbles-container {
            position: relative;
            width: 100%;
            height: 400px;
            background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            overflow: hidden;
        }

        .bubbles-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .bubble {
            position: absolute;
            left: var(--bubble-x);
            bottom: var(--bubble-y-start);
            width: var(--bubble-size);
            height: var(--bubble-size);
            cursor: pointer;
            animation: float var(--bubble-duration) ease-in-out infinite;
            animation-delay: var(--bubble-delay);
            transition: transform 0.3s ease;
        }

        .bubble:hover {
            transform: scale(1.2);
            z-index: 10;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(calc(var(--bubble-y-start) - var(--bubble-y-end))) translateX(-20px);
            }
            50% {
                transform: translateY(calc((var(--bubble-y-start) - var(--bubble-y-end)) * 0.7)) translateX(10px);
            }
            75% {
                transform: translateY(calc((var(--bubble-y-start) - var(--bubble-y-end)) * 0.4)) translateX(-10px);
            }
        }

        .bubble-glow {
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3), transparent);
            border-radius: 50%;
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bubble:hover .bubble-glow {
            opacity: 1;
            animation: glowPulse 1.5s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .bubble-inner {
            position: relative;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border: 3px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .bubble-icon {
            width: 50%;
            height: 50%;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .bubble-name {
            font-size: 0.65rem;
            font-weight: 600;
            text-align: center;
            color: #495057;
            line-height: 1.2;
        }

        /* Modal Details */
        .badge-details-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .badge-details-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            position: relative;
            animation: slideUp 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(90deg);
        }

        .badge-detail-icon {
            width: 120px;
            height: 120px;
            object-fit: contain;
            animation: iconPop 0.5s ease;
        }

        @keyframes iconPop {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .stat-box {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            text-align: center;
        }
    </style>
</div>
