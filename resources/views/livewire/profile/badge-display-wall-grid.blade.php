<div class="badge-wall-container">
    <div class="wall-grid">
        <!-- Earned Badges -->
        @foreach($earnedBadges as $index => $userBadge)
        @if($userBadge->badge)
        <div class="wall-badge earned {{ $userBadge->show_in_profile ? 'in-profile' : '' }} {{ $userBadge->show_in_sidebar ? 'in-sidebar' : '' }}" 
             wire:click="selectBadge({{ $userBadge->id }})"
             style="animation-delay: {{ $index * 0.05 }}s;">
            <div class="badge-frame">
                <div class="badge-shine"></div>
                <div class="badge-glow-ring"></div>
                <img src="{{ $userBadge->badge->icon_url }}" 
                     alt="{{ $userBadge->badge->name }}"
                     class="wall-badge-icon">
                <div class="badge-label">{{ $userBadge->badge->name }}</div>
                <div class="earned-checkmark">
                    <i class="ph ph-check-circle"></i>
                </div>
                
                <!-- Profile indicator -->
                @if($userBadge->show_in_profile)
                <div class="profile-indicator" title="In evidenza nel profilo">
                    <i class="ph ph-star-fill"></i>
                </div>
                @endif
                
                <!-- Sidebar indicator -->
                @if($userBadge->show_in_sidebar)
                <div class="sidebar-indicator" title="Mostrato in sidebar">
                    <i class="ph ph-layout-fill"></i>
                </div>
                @endif
            </div>
        </div>
        @endif
        @endforeach

        <!-- Locked Badges - Non cliccabili -->
        @foreach($lockedBadges as $index => $badge)
        <div class="wall-badge locked" 
             style="animation-delay: {{ ($earnedBadges->count() + $index) * 0.05 }}s;">
            <div class="badge-frame">
                <div class="lock-overlay">
                    <i class="ph ph-lock f-s-32"></i>
                </div>
                <img src="{{ $badge->icon_url }}" 
                     alt="Locked"
                     class="wall-badge-icon locked-icon">
                <div class="badge-label">???</div>
                <div class="progress-ring">
                    <span class="progress-text">{{ $badge->criteria_value }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Badge Details Modal -->
    @if($selectedBadge)
    <div class="badge-detail-modal" wire:click="closeDetails">
        <div class="detail-card" @click.stop>
            <button wire:click="closeDetails" class="close-detail-btn">
                <i class="ph ph-x"></i>
            </button>

            <div class="detail-content">
                <div class="earned-banner">
                    <i class="ph ph-check-circle me-2"></i>
                    Sbloccato!
                </div>

                <img src="{{ $selectedBadge->badge->icon_url }}" 
                     alt="{{ $selectedBadge->badge->name }}"
                     class="detail-icon">

                <h3>{{ $selectedBadge->badge->name }}</h3>
                <p class="text-muted">{{ $selectedBadge->badge->description }}</p>

                <div class="detail-stats">
                    <div class="detail-stat">
                        <i class="ph ph-star text-warning"></i>
                        <span>{{ $selectedBadge->badge->points }} punti</span>
                    </div>
                    <div class="detail-stat">
                        <i class="ph ph-calendar text-primary"></i>
                        <span>{{ $selectedBadge->earned_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <!-- Management Section -->
                <div class="badge-management mt-4 pt-4" style="border-top: 2px solid #e2e8f0;">
                    <h6 class="mb-3 f-w-600">
                        <i class="ph ph-gear me-2"></i>
                        Gestisci Visualizzazione
                    </h6>
                    
                    <div class="d-flex flex-column gap-3">
                        <!-- Profile Toggle -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: rgba(var(--primary), 0.05);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph ph-star f-s-24 text-primary"></i>
                                <div>
                                    <div class="f-w-600">Profilo Stack Cards</div>
                                    <small class="text-muted">Mostra tra i 3 badge in evidenza</small>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       @if($showInProfile) checked @endif
                                       wire:click="toggleProfile"
                                       style="cursor: pointer; width: 3rem; height: 1.5rem;">
                            </div>
                        </div>
                        
                        <!-- Sidebar Toggle -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: rgba(var(--success), 0.05);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph ph-layout f-s-24 text-success"></i>
                                <div>
                                    <div class="f-w-600">Sidebar</div>
                                    <small class="text-muted">Mostra nella barra laterale (max 5)</small>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       @if($showInSidebar) checked @endif
                                       wire:click="toggleSidebar"
                                       style="cursor: pointer; width: 3rem; height: 1.5rem;">
                            </div>
                        </div>
                    </div>
                    
                    @if(session()->has('success'))
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="ph ph-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    @if(session()->has('error'))
                    <div class="alert alert-danger mt-3 mb-0">
                        <i class="ph ph-warning-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .badge-wall-container {
            width: 100%;
            padding: 30px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 20px;
            min-height: 500px;
        }

        .wall-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 20px;
        }

        .wall-badge {
            animation: badgeAppear 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
            cursor: pointer;
        }

        @keyframes badgeAppear {
            from {
                opacity: 0;
                transform: scale(0) rotate(-180deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        .badge-frame {
            position: relative;
            background: white;
            border-radius: 15px;
            padding: 20px;
            height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .wall-badge:hover .badge-frame {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .wall-badge.earned .badge-frame {
            border: 3px solid #48bb78;
        }

        .wall-badge.locked .badge-frame {
            border: 3px solid #cbd5e0;
            background: #f7fafc;
        }

        .badge-shine {
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .badge-glow-ring {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3), transparent);
            animation: glowPulse 2s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.3); opacity: 0.8; }
        }

        .wall-badge-icon {
            width: 80px;
            height: 80px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .wall-badge:hover .wall-badge-icon {
            transform: scale(1.2) rotate(15deg);
        }

        .locked-icon {
            filter: grayscale(100%) brightness(1.3);
            opacity: 0.4;
        }

        .lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            color: #a0aec0;
        }

        .badge-label {
            margin-top: 10px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            color: #4a5568;
            z-index: 2;
        }

        .earned-checkmark {
            position: absolute;
            top: 5px;
            right: 5px;
            color: #48bb78;
            font-size: 1.5rem;
            animation: checkPop 0.5s ease;
        }

        @keyframes checkPop {
            0% { transform: scale(0) rotate(-180deg); }
            50% { transform: scale(1.3) rotate(10deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        
        .profile-indicator {
            position: absolute;
            bottom: 5px;
            left: 5px;
            background: linear-gradient(135deg, rgba(var(--primary), 0.9), rgba(var(--primary), 1));
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(var(--primary), 0.4);
        }
        
        .sidebar-indicator {
            position: absolute;
            bottom: 5px;
            left: 38px;
            background: linear-gradient(135deg, rgba(var(--success), 0.9), rgba(var(--success), 1));
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(var(--success), 0.4);
        }

        .progress-ring {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-text {
            font-size: 0.7rem;
            font-weight: 700;
            color: #667eea;
        }

        /* Modal */
        .badge-detail-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .detail-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            position: relative;
            animation: scaleIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from { transform: scale(0.5) rotate(-10deg); opacity: 0; }
            to { transform: scale(1) rotate(0); opacity: 1; }
        }

        .close-detail-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-detail-btn:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(90deg);
        }

        .earned-banner {
            display: inline-block;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .locked-banner {
            display: inline-block;
            background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .detail-icon {
            width: 150px;
            height: 150px;
            object-fit: contain;
            margin: 20px auto;
            animation: iconBounce 0.6s ease;
        }

        .detail-icon.locked {
            filter: grayscale(100%) brightness(1.5);
            opacity: 0.5;
        }

        @keyframes iconBounce {
            0% { transform: scale(0) rotate(-180deg); }
            50% { transform: scale(1.15) rotate(10deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        .detail-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .detail-stat {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }
    </style>
</div>
