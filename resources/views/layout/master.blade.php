<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/js/app.js') <!-- All meta and title start-->
    @include('layout.head')
    <!-- meta and title end-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- css start !-->
    @include('layout.css')
    <!-- css end !-->
</head>

<body>
    <!-- Loader start-->
    <div class="app-wrapper">
        <!-- Loader start-->
        <div class="loader-wrapper">
            <div class="loader_24"></div>
        </div>
        <!-- Loader end-->

        <!-- Menu Navigation start -->
        @include('layout.sidebar')
        <!-- Menu Navigation end -->


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

    <!--customizer-->
    <div id="customizer"></div>

    <!-- scripts start-->
    @include('layout.script')
    <!-- scripts end-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Echo.private(`user-logins`)
                .listen('.user-login', (e) => {
                    console.log(e);
                    Toastify({
                        text: `🔐 Utente loggato: ${e.user.name} (${e.user .email})`,
                        duration: 5000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#28a745',
                        stopOnFocus: true,
                        close: true,
                    }).showToast();
                });

        });
    </script>


</body>



</html>
