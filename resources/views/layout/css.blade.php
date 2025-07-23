<!-- Animation css -->
<link rel="stylesheet" href="{{ asset('assets/vendor/animation/animate.min.css') }}" >

<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
      rel="stylesheet">

<!--Flag Icon css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}">

<!-- Tabler icons-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}">

<!-- Prism css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/prism/prism.min.css') }}">

<!-- Bootstrap css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">

<!-- Simplebar css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/simplebar/simplebar.css') }}">

<!-- Phosphor Icons css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/phosphor/phosphor-light.css') }}">

<!-- AOS css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/aos/aos.css') }}">

<!-- App css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">

<!-- Icon sizes css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icon-sizes.css') }}">

<!-- Sidebar scrollbar css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sidebar-scrollbar.css') }}">

@yield('css')

@vite(['public/assets/scss/style.scss'])
