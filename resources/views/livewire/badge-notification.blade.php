<div>
    @if($showNotification && $badge)
    <!-- Full Screen Badge Notification -->
    <div class="badge-notification-overlay" 
         x-data="{ show: @entangle('showNotification') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="badge-backdrop"></div>
        
        <!-- Notification Content -->
        <div class="badge-notification-content"
             x-show="show"
             x-transition:enter="transition ease-out duration-500 delay-150"
             x-transition:enter-start="opacity-0 scale-50 rotate-12"
             x-transition:enter-end="opacity-100 scale-100 rotate-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-75">
            
            <!-- Close Button -->
            <button wire:click="closeNotification" 
                    class="badge-close-btn"
                    aria-label="Chiudi">
                <i class="ph ph-x f-s-24"></i>
            </button>
            
            <!-- Confetti Animation -->
            <div class="confetti-container">
                @for($i = 0; $i < 50; $i++)
                <div class="confetti" style="
                    left: {{ rand(0, 100) }}%;
                    animation-delay: {{ rand(0, 300) / 100 }}s;
                    background: {{ ['#FFD700', '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8'][ rand(0, 5) ] }};
                "></div>
                @endfor
            </div>
            
            <!-- Star Burst Effect -->
            <div class="starburst">
                @for($i = 0; $i < 8; $i++)
                <div class="star-ray" style="transform: rotate({{ $i * 45 }}deg)"></div>
                @endfor
            </div>
            
            <!-- Main Content -->
            <div class="text-center position-relative" style="z-index: 10;">
                
                <!-- Congratulations Title -->
                <h1 class="badge-title mb-4" style="
                    font-size: 3.5rem;
                    font-weight: 800;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    text-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    animation: titlePulse 2s ease-in-out infinite;
                ">
                    🎉 Complimenti! 🎉
                </h1>
                
                <!-- Badge Icon with Glow -->
                <div class="badge-icon-container mb-4">
                    <div class="badge-glow"></div>
                    <img src="{{ $badge->icon_url ?? asset('assets/images/draghetto.png') }}" 
                         alt="{{ $badge->name }}" 
                         class="badge-icon-large">
                </div>
                
                <!-- Badge Name -->
                <h2 class="badge-name mb-3" style="
                    font-size: 2.5rem;
                    font-weight: 700;
                    color: #2d3748;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                ">
                    {{ $badge->name }}
                </h2>
                
                <!-- Badge Description -->
                @if($badge->description)
                <p class="badge-description mb-4" style="
                    font-size: 1.2rem;
                    color: #718096;
                    max-width: 600px;
                    margin: 0 auto;
                ">
                    {{ $badge->description }}
                </p>
                @endif
                
                <!-- Stats Cards -->
                <div class="row justify-content-center mt-5 g-4">
                    <!-- Points Card -->
                    <div class="col-md-4">
                        <div class="stat-card stat-card-points">
                            <div class="stat-icon">
                                <i class="ph ph-star-four"></i>
                            </div>
                            <div class="stat-value">+{{ $points }}</div>
                            <div class="stat-label">Punti Guadagnati</div>
                        </div>
                    </div>
                    
                    <!-- Level Card -->
                    <div class="col-md-4">
                        <div class="stat-card stat-card-level">
                            <div class="stat-icon">
                                <i class="ph ph-ranking"></i>
                            </div>
                            <div class="stat-value">
                                @if($leveledUp)
                                <span class="level-up-animation">
                                    {{ $previousLevel }} → {{ $level }}
                                </span>
                                @else
                                Livello {{ $level }}
                                @endif
                            </div>
                            <div class="stat-label">
                                @if($leveledUp)
                                🎊 Livello Aumentato! 🎊
                                @else
                                Livello Attuale
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Button -->
                <div class="mt-5">
                    <button wire:click="closeNotification" 
                            class="btn btn-lg btn-primary px-5 py-3"
                            style="
                                font-size: 1.2rem;
                                font-weight: 600;
                                border-radius: 50px;
                                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
                                transition: all 0.3s ease;
                            ">
                        <i class="ph ph-check-circle me-2"></i>
                        Fantastico!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .badge-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
            backdrop-filter: blur(10px);
        }
        
        .badge-notification-content {
            position: relative;
            background: white;
            border-radius: 30px;
            padding: 60px 80px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }
        
        .badge-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 20;
        }
        
        .badge-close-btn:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(90deg);
        }
        
        /* Confetti Animation */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            top: -10px;
            animation: confettiFall 3s linear infinite;
            opacity: 0;
        }
        
        @keyframes confettiFall {
            0% {
                top: -10px;
                opacity: 1;
                transform: rotate(0deg);
            }
            100% {
                top: 100%;
                opacity: 0;
                transform: rotate(720deg);
            }
        }
        
        /* Starburst Effect */
        .starburst {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            pointer-events: none;
        }
        
        .star-ray {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 4px;
            height: 150px;
            background: linear-gradient(to bottom, rgba(255, 215, 0, 0.6), transparent);
            transform-origin: center top;
            animation: rayPulse 2s ease-in-out infinite;
        }
        
        @keyframes rayPulse {
            0%, 100% { opacity: 0.3; transform: rotate(0deg) scale(1); }
            50% { opacity: 0.8; transform: rotate(0deg) scale(1.1); }
        }
        
        /* Badge Icon */
        .badge-icon-container {
            position: relative;
            display: inline-block;
        }
        
        .badge-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.4), transparent);
            border-radius: 50%;
            animation: glowPulse 2s ease-in-out infinite;
        }
        
        .badge-icon-large {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
            animation: iconBounce 1s ease-in-out;
        }
        
        @keyframes glowPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.6; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.9; }
        }
        
        @keyframes iconBounce {
            0% { transform: scale(0) rotate(-180deg); }
            50% { transform: scale(1.1) rotate(10deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        
        @keyframes titlePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            animation: statCardSlide 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .stat-card-points {
            animation-delay: 0.3s;
        }
        
        .stat-card-level {
            animation-delay: 0.5s;
        }
        
        @keyframes statCardSlide {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #718096;
            font-weight: 600;
        }
        
        .level-up-animation {
            animation: levelUp 0.6s ease-out;
        }
        
        @keyframes levelUp {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .badge-notification-content {
                padding: 40px 30px;
            }
            
            .badge-title {
                font-size: 2.5rem !important;
            }
            
            .badge-name {
                font-size: 2rem !important;
            }
            
            .badge-icon-large {
                width: 150px;
                height: 150px;
            }
            
            .stat-value {
                font-size: 2rem;
            }
        }
    </style>
    @endif
</div>
