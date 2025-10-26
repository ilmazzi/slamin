<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.head')
    <!-- meta and title end-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
    <!-- Current User ID -->
    <meta name="current-user-id" content="{{ auth()->id() }}">
    @endauth

    <!-- css start !-->
    @include('layout.css')
    
    <!-- Quill.js CSS locale -->
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    
    <!-- CSS per preservare spazi nelle poesie -->
    <style>
    .poem-content, .poem-content * {
        white-space: pre-wrap !important;
    }
    
    /* Sidebar Badge Animation */
    .sidebar-badge-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
        cursor: pointer;
        border: 2px solid rgba(var(--primary), 0.3);
        padding: 2px;
        border-radius: 50%;
        transition: all 0.3s ease;
        animation: badgePulse 3s ease-in-out infinite;
        box-shadow: 0 0 0 0 rgba(var(--primary), 0.4);
    }
    
    @keyframes badgePulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(var(--primary), 0.4);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(var(--primary), 0);
            transform: scale(1.05);
        }
    }
    
    .sidebar-badge-icon:hover {
        transform: scale(1.15) rotate(5deg);
        border-color: rgba(var(--primary), 0.6);
        box-shadow: 0 4px 12px rgba(var(--primary), 0.3);
    }
    
    .badge-sidebar-wrapper {
        display: inline-block;
        animation: badgeAppear 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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
    
    @keyframes badgeSpin {
        from {
            transform: scale(0.5) rotate(-360deg);
            opacity: 0;
        }
        to {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }
    
    /* Floating Avatar Animation */
    .floating-avatar {
        animation: floatIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .floating-avatar img {
        animation: floatContinuous 3s ease-in-out infinite;
        transition: all 0.3s ease;
    }
    
    .floating-avatar:hover img {
        transform: scale(1.08) translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    
    @keyframes floatIn {
        0% {
            opacity: 0;
            transform: translateY(-50px) scale(0.8);
        }
        60% {
            transform: translateY(5px) scale(1.05);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes floatContinuous {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    /* Profile Sidebar Tabs - Clean & Compact */
    .profile-tab-item {
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        color: #64748b;
        display: flex;
        align-items: center;
    }
    
    .profile-tab-item:hover {
        background: rgba(var(--primary), 0.05);
        color: rgba(var(--primary), 1);
    }
    
    .profile-tab-item.active {
        background: rgba(var(--primary), 1);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(var(--primary), 0.25);
    }
    
    .profile-tab-item i {
        font-size: 18px;
    }
    
    .profile-tab-item span {
        font-size: 14px;
    }
    </style>
    
    <!-- Initialize Popovers for Badge -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl, {
                    container: 'body',
                    customClass: 'badge-popover'
                });
            });
        });
    </script>
    <!-- css end !-->
    
    @livewireStyles
    
    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
    
    @stack('styles')
</head>

<body>
    <!-- Loader start-->
    <div class="app-wrapper">
        <!-- Loader start-->
        <x-splash-screen />
        <!-- Loader end-->

        <!-- Menu Navigation start -->
        @include('layout.sidebar')
        <!-- Menu Navigation end -->

        <!-- Sidebar Overlay per mobile -->
        <div class="sidebar-overlay"></div>

        <div class="app-content">
            <!-- Header Section start -->
            @include('layout.header')
            <!-- Header Section end -->

            <!-- Main Section start -->
            <main>
                {{-- main body content --}}
                @yield('main-content')
            </main>
            <!-- Main Section end -->
        </div>

        <!-- tap on top -->
        <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-arrow-up"></i>
            </span>
        </div>


        <!-- Footer Section start -->
        @include('layout.footer')
        <!-- Footer Section end -->
    </div>

@routes
    <!-- Search Configuration -->
    @include('layout.search-config')
    <!-- scripts start-->
    @include('layout.script')
    
    <!-- Quill.js JavaScript locale -->
    <script src="{{ asset('js/quill.min.js') }}"></script>
    <!-- scripts end-->
 

    @livewireScripts
    
    <!-- Leaflet Maps JS -->
    <script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
    
    @stack('scripts')
    
    <!-- Global Comment Modal -->
    <livewire:social.global-comment-modal />
    
    <!-- Translation Sidebar (Admin Only) -->
    @if(auth()->check() && auth()->user()->hasRole('admin'))
        <livewire:admin.translation-sidebar />
    @endif
    
    <!-- SVG Icons -->
    <svg style="display: none;">
        <symbol id="icon-like" viewBox="0 0 32 32" fill="currentColor">
            <path d="M14.856 0.069c-1.206 0.138-2.381 0.494-3.512 1.056-4.744 2.375-6.906 8-4.994 12.969 0.912 2.356 2.694 4.313 4.994 5.475l0.875 0.444 0.031 2.231 0.031 2.225 0.906 0.038v7.494h5.625v-7.494l0.906-0.038 0.063-4.475 0.413-0.181c2.35-1.038 4.325-3.050 5.337-5.45 0.306-0.719 0.6-1.775 0.725-2.581 0.094-0.625 0.094-2.15 0-2.813-0.244-1.731-0.938-3.412-2-4.844-0.488-0.669-1.563-1.706-2.256-2.2-2.075-1.462-4.688-2.144-7.144-1.856zM17.613 2.031c1.050 0.206 2.137 0.65 3.044 1.25 1.919 1.275 3.212 3.213 3.669 5.5 0.163 0.813 0.175 2.2 0.025 3-0.581 3.144-2.756 5.65-5.738 6.619-1.006 0.325-1.394 0.381-2.675 0.381-1.037-0.006-1.225-0.025-1.813-0.169-2.25-0.563-4.044-1.825-5.244-3.675-1.506-2.319-1.763-5.338-0.681-7.888 1.181-2.8 3.819-4.756 6.925-5.138 0.438-0.056 1.981 0.019 2.488 0.119zM16 20.656c0.569 0 1.219-0.025 1.456-0.050l0.425-0.056-0.019 1.025-0.019 1.019h-3.688l-0.019-1.019-0.019-1.025 0.425 0.056c0.238 0.025 0.887 0.050 1.456 0.050zM16.938 27.313v2.813h-1.875v-5.625h1.875v2.813z"></path>
            <path d="M10.875 6.556v0.938l0.906 0.037 0.019 3.762 0.012 3.769h1.875l-0.012-4.706-0.019-4.7-1.387-0.019-1.394-0.013v0.931z"></path>
            <path d="M17.9 5.688c-1.35 0.206-2.431 1.194-2.744 2.512-0.131 0.569-0.131 3.725 0 4.294 0.313 1.319 1.412 2.325 2.769 2.519 0.988 0.144 2.019-0.2 2.725-0.912 0.425-0.425 0.738-0.956 0.881-1.506 0.137-0.537 0.137-3.963 0-4.5-0.413-1.563-2.044-2.65-3.631-2.406zM19 7.688c0.288 0.15 0.531 0.412 0.65 0.7 0.144 0.344 0.144 3.569 0.006 3.912-0.238 0.556-0.875 0.944-1.444 0.863-0.544-0.069-0.919-0.35-1.144-0.85-0.119-0.262-0.131-0.406-0.131-1.975v-1.688l0.163-0.325c0.356-0.719 1.194-1 1.9-0.638z"></path>
        </symbol>
    </svg>

    <!-- Badge Notification Component (Full Screen) -->
    @auth
    @livewire('badge-notification')
    @endauth

</body>



</html>
