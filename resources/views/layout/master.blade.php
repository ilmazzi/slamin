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
    @if(auth()->check() && auth()->user()->hasRole('admin'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function waitForEcho(callback, retries = 20) {
                if (typeof window.Echo !== 'undefined') {
                    console.log('[Admin] Echo disponibile');
                    callback();
                } else if (retries > 0) {
                    console.warn('[Admin] Echo non ancora disponibile, ritento...');
                    setTimeout(() => waitForEcho(callback, retries - 1), 250);
                } else {
                    console.error('[Admin] Echo NON disponibile dopo vari tentativi');
                }
            }

            waitForEcho(() => {
                console.log('Admin connesso, ascolto eventi login utenti...');

                window.Echo.private('admin.logged-users')
                    .listen('.UserLoggedIn', (e) => {
                        console.log('Evento ricevuto: utente loggato', e);

                        Toastify({
                            text: `🔐 Utente loggato: ${e.name} (${e.email})`,
                            duration: 5000,
                            gravity: 'top',
                            position: 'right',
                            backgroundColor: '#28a745',
                            stopOnFocus: true,
                            close: true,
                        }).showToast();
                    });
            });
        });
    </script>
    @endif


</body>



</html>
