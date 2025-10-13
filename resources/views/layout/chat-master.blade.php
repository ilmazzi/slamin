<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/js/app.js') <!-- All meta and title start-->
    @include('layout.head')
    <!-- meta and title end-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
    <!-- Current User ID -->
    <meta name="current-user-id" content="{{ auth()->id() }}">
    @endauth

    <!-- THEME: applica tema prima del paint per evitare flicker -->
    <script>
      function updateTheme(isDark) {
        if (isDark) document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
      }
      const mq = window.matchMedia('(prefers-color-scheme: dark)');
      updateTheme(mq.matches);
      mq.addEventListener('change', e => updateTheme(e.matches));
      document.addEventListener('livewire:navigated', () => updateTheme(window.matchMedia('(prefers-color-scheme: dark)').matches));
    </script>

    <!-- css start !-->
    @include('layout.css')

    <!-- Quill.js CSS locale (se ti serve anche qui) -->
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">

    <!-- CSS per preservare spazi nelle poesie (se necessario) -->
    <style>
      .poem-content, .poem-content * { white-space: pre-wrap !important; }
      html, body { height: 100%; }
      body { margin: 0; }
    </style>
    <!-- css end !-->

    @livewireStyles

    {{-- Stack per stili (Wirechat li pushiamo dalla pagina) --}}
    @stack('styles')
</head>

<body>
  <div class="app-wrapper">
    {{-- NIENTE header/sidebar/footer/chat-widget per evitare ricorsione --}}

    <div class="app-content">
      <main>
        @yield('main-content')
      </main>
    </div>
  </div>

  @routes
  {{-- scripts base del tuo tema --}}
  

  {{-- Quill (se ti serve anche qui) --}}
  <script src="{{ asset('js/quill.min.js') }}"></script>

  {{-- Se ti serve, puoi incollare QUI eventuale JS comune NON legato a Echo/ChatWidget --}}

  @livewireScripts

  {{-- Stack per script (Wirechat li pushiamo dalla pagina) --}}
  @stack('scripts')
</body>

</html>
