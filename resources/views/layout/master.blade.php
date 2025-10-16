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
    </style>
    <!-- css end !-->
    
    @livewireStyles
    
    
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
    
    @stack('scripts')
    
</body>



</html>
