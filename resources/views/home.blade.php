@extends('layout.master')



@section('title', 'Slam in - Home')

@section('css')
    <!-- Slick CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/slick/slick-theme.css') }}">
    
    <!-- Custom CSS per rimuovere ombreggiatura slider -->
    <style>
        #new-videos-slider .slick-list,
        #new-videos-slider .slick-track,
        #new-videos-slider .slick-slide,
        #popular-videos-slider .slick-list,
        #popular-videos-slider .slick-track,
        #popular-videos-slider .slick-slide {
            box-shadow: none !important;
            filter: none !important;
            text-shadow: none !important;
            -webkit-box-shadow: none !important;
            -moz-box-shadow: none !important;
        }
        
        .center-mode .slick-list,
        .center-mode .slick-track,
        .center-mode .slick-slide {
            box-shadow: none !important;
            filter: none !important;
            text-shadow: none !important;
            -webkit-box-shadow: none !important;
            -moz-box-shadow: none !important;
        }
        
        .app-arrow .slick-list,
        .app-arrow .slick-track,
        .app-arrow .slick-slide {
            box-shadow: none !important;
            filter: none !important;
            text-shadow: none !important;
            -webkit-box-shadow: none !important;
            -moz-box-shadow: none !important;
        }
    </style>
    <style>
        /* Stili aggiuntivi per lo slider degli eventi */
        .events-slider {
            position: relative;
            margin: 0 -10px;
        }

        .events-slider .autoplay-item {
            padding: 0 10px;
            height: auto;
        }

        .events-slider .card {
            height: 100%;
            transition: transform 0.3s ease;
        }

        .events-slider .card:hover {
            transform: translateY(-5px);
        }

        /* Forza altezza uniforme per le card nello slider */
        .events-slider .slick-track {
            display: flex !important;
        }

        .events-slider .slick-slide {
            height: inherit;
        }

        .events-slider .slick-slide > div {
            height: 100%;
        }

        .events-slider .autoplay-item {
            height: 100%;
        }

        /* Forza altezza uniforme delle card */
        .events-slider .slick-slide {
            height: auto !important;
        }

        .events-slider .slick-slide > div {
            height: 100% !important;
        }

        .events-slider .autoplay-item {
            height: 100% !important;
        }

        .events-slider .card {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .events-slider .card-body {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
        }

        .events-slider .card-body > *:not(:last-child) {
            flex-shrink: 0 !important;
        }

        .events-slider .d-flex.justify-content-between {
            margin-top: auto !important;
            flex-shrink: 0 !important;
        }

        /* Forza altezza minima per le card */
        .events-slider .card {
            min-height: 400px !important;
        }

        /* Stili per il carosello video */
        .videos-slider {
            position: relative;
            margin: 0 -10px;
        }

        .videos-slider .autoplay-item {
            padding: 0 10px;
            height: auto;
        }

        .videos-slider .card {
            height: 100%;
            transition: transform 0.3s ease;
        }

        .videos-slider .card:hover {
            transform: translateY(-5px);
        }

        /* Forza altezza uniforme per le card video nello slider */
        .videos-slider .slick-track {
            display: flex !important;
        }

        .videos-slider .slick-slide {
            height: inherit;
        }

        .videos-slider .slick-slide > div {
            height: 100%;
        }

        .videos-slider .autoplay-item {
            height: 100%;
        }

        /* Forza altezza uniforme delle card video */
        .videos-slider .slick-slide {
            height: auto !important;
        }

        .videos-slider .slick-slide > div {
            height: 100% !important;
        }

        .videos-slider .autoplay-item {
            height: 100% !important;
        }

        .videos-slider .card {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .videos-slider .card-body {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
        }

        .videos-slider .card-body > *:not(:last-child) {
            flex-shrink: 0 !important;
        }

        .videos-slider .d-flex.justify-content-between {
            margin-top: auto !important;
            flex-shrink: 0 !important;
        }

        /* Forza altezza minima per le card video */
        .videos-slider .card {
            min-height: 350px !important;
        }
    </style>
@endsection

@push('scripts')
    <!-- Slick JS -->
    <script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick.js') }}"></script>

    <script>
        // Aspetta che tutto sia caricato
        window.addEventListener('load', function() {
            // Verifica se jQuery è disponibile
            if (typeof $ === 'undefined') {
                console.error('jQuery non è caricato!');
                return;
            }

            // Verifica se Bootstrap è disponibile
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap non è caricato!');
                return;
            }

            // Verifica se Slick è disponibile
            if (typeof $.fn.slick === 'undefined') {
                console.error('Slick non è caricato!');
                return;
            }

            // Debug: verifica se lo slider esiste
            const $slider = $('.events-slider');

            // Verifica se il carousel esiste
            const $carousel = $('#heroCarousel');

            if ($slider.length > 0) {
                // Inizializza lo slider degli eventi
                $slider.slick({
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    arrows: true,
                    dots: false,
                    infinite: true,
                    speed: 500,
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            } else {
                console.error('Slider not found!');
            }

            // Inizializza entrambi i slider video
            const $newVideosSlider = $('#new-videos-slider');
            const $popularVideosSlider = $('#popular-videos-slider');
            
            window.sliderConfig = {
                slidesToShow: 3,
                slidesToScroll: 1,
                centerMode: true,
                centerPadding: '60px',
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: true,
                dots: false,
                infinite: true,
                speed: 500,
                cssEase: 'linear',
                responsive: [{
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            centerMode: true,
                            centerPadding: '40px'
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            centerMode: true,
                            centerPadding: '60px'
                        }
                    }
                ]
            };
            
            
            // Inizializza entrambi gli slider all'avvio (ma solo se hanno contenuto)
            setTimeout(() => {
                // Inizializza slider nuovi video
                if ($newVideosSlider.length > 0 && $newVideosSlider.find('.item').length > 0) {
                    try {
                        $newVideosSlider.slick(window.sliderConfig);
                        console.log('Slider nuovi video inizializzato');
                    } catch (error) {
                        console.log('Errore inizializzazione slider nuovi:', error);
                    }
                }
                
                // Inizializza slider popolari video (anche se nascosto)
                if ($popularVideosSlider.length > 0 && $popularVideosSlider.find('.item').length > 0) {
                    try {
                        $popularVideosSlider.slick(window.sliderConfig);
                        console.log('Slider popolari video inizializzato');
                    } catch (error) {
                        console.log('Errore inizializzazione slider popolari:', error);
                    }
                }
                
                // Applica rimozione ombreggiatura a entrambi
                setTimeout(() => {
                    $newVideosSlider.find('.slick-list, .slick-track, .slick-slide').css({
                        'box-shadow': 'none !important',
                        'filter': 'none !important',
                        'text-shadow': 'none !important',
                        '-webkit-box-shadow': 'none !important',
                        '-moz-box-shadow': 'none !important'
                    });
                    
                    $popularVideosSlider.find('.slick-list, .slick-track, .slick-slide').css({
                        'box-shadow': 'none !important',
                        'filter': 'none !important',
                        'text-shadow': 'none !important',
                        '-webkit-box-shadow': 'none !important',
                        '-moz-box-shadow': 'none !important'
                    });
                }, 200);
                
            }, 200);

            // Inizializza il carosello Bootstrap
            if ($carousel.length > 0) {
                try {
                    const bsCarousel = new bootstrap.Carousel($carousel[0], {
                        interval: 5000, // 5 secondi
                        ride: 'carousel', // Avvia automaticamente
                        wrap: true, // Loop infinito
                        keyboard: true, // Controlli da tastiera
                        pause: 'hover' // Pausa al hover
                    });
                } catch (error) {
                    console.warn('Bootstrap Carousel non disponibile, usando fallback manuale');
                    initManualCarousel();
                }
            }

            // Funzione fallback per carosello manuale
            function initManualCarousel() {
                const carousel = document.getElementById('heroCarousel');
                if (!carousel) return;

                const slides = carousel.querySelectorAll('.carousel-item');
                const indicators = carousel.querySelectorAll('.carousel-indicators button');
                const prevBtn = carousel.querySelector('.carousel-control-prev');
                const nextBtn = carousel.querySelector('.carousel-control-next');

                let currentSlide = 0;
                let interval;

                function showSlide(index) {
                    // Nascondi tutte le slide
                    slides.forEach(slide => slide.classList.remove('active'));
                    indicators.forEach(indicator => indicator.classList.remove('active'));

                    // Mostra la slide corrente
                    slides[index].classList.add('active');
                    if (indicators[index]) {
                        indicators[index].classList.add('active');
                    }

                    currentSlide = index;
                }

                function nextSlide() {
                    const next = (currentSlide + 1) % slides.length;
                    showSlide(next);
                }

                function prevSlide() {
                    const prev = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(prev);
                }

                // Event listeners
                if (nextBtn) {
                    nextBtn.addEventListener('click', nextSlide);
                }
                if (prevBtn) {
                    prevBtn.addEventListener('click', prevSlide);
                }

                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => showSlide(index));
                });

                // Auto-scroll
                interval = setInterval(nextSlide, 5000);

                // Pausa al hover
                carousel.addEventListener('mouseenter', () => clearInterval(interval));
                carousel.addEventListener('mouseleave', () => {
                    interval = setInterval(nextSlide, 5000);
                });
            }
        });

        // Toggle functions for Poetry and Articles sections
        window.togglePoetryContent = function(type) {
            const newContent = document.getElementById('newPoetryContent');
            const popularContent = document.getElementById('popularPoetryContent');
            const toggle = document.getElementById('poetryToggle');
            const labelLeft = document.getElementById('poetryToggleLabelLeft');
            const labelRight = document.getElementById('poetryToggleLabelRight');

            if (type === 'new') {
                newContent.style.display = 'block';
                popularContent.style.display = 'none';
                toggle.checked = false;
                // Evidenzia "New" e disattiva "Popolari"
                labelLeft.classList.remove('text-muted');
                labelLeft.classList.add('text-primary');
                labelRight.classList.remove('text-primary');
                labelRight.classList.add('text-muted');
            } else {
                newContent.style.display = 'none';
                popularContent.style.display = 'block';
                toggle.checked = true;
                // Evidenzia "Popolari" e disattiva "New"
                labelLeft.classList.remove('text-primary');
                labelLeft.classList.add('text-muted');
                labelRight.classList.remove('text-muted');
                labelRight.classList.add('text-primary');
            }
        };

        window.toggleArticlesContent = function(type) {
            const newContent = document.getElementById('newArticlesContent');
            const popularContent = document.getElementById('popularArticlesContent');
            const toggle = document.getElementById('articlesToggle');
            const labelLeft = document.getElementById('articlesToggleLabelLeft');
            const labelRight = document.getElementById('articlesToggleLabelRight');

            if (type === 'new') {
                newContent.style.display = 'block';
                popularContent.style.display = 'none';
                toggle.checked = false;
                // Evidenzia "New" e disattiva "Popolari"
                labelLeft.classList.remove('text-muted');
                labelLeft.classList.add('text-primary');
                labelRight.classList.remove('text-primary');
                labelRight.classList.add('text-muted');
            } else {
                newContent.style.display = 'none';
                popularContent.style.display = 'block';
                toggle.checked = true;
                // Evidenzia "Popolari" e disattiva "New"
                labelLeft.classList.remove('text-primary');
                labelLeft.classList.add('text-muted');
                labelRight.classList.remove('text-muted');
                labelRight.classList.add('text-primary');
            }
        };

        // Funzione semplice per il toggle dei video (solo show/hide)
        window.toggleVideosContent = function(type) {
            const toggle = document.getElementById('videosToggle');
            const labelLeft = document.getElementById('videosToggleLabelLeft');
            const labelRight = document.getElementById('videosToggleLabelRight');
            const newSlider = document.getElementById('new-videos-slider');
            const popularSlider = document.getElementById('popular-videos-slider');

            // Aggiorna le etichette del toggle
            if (type === 'new') {
                toggle.checked = false;
                labelLeft.classList.remove('text-muted');
                labelLeft.classList.add('text-primary');
                labelRight.classList.remove('text-primary');
                labelRight.classList.add('text-muted');
                
                // Mostra slider nuovi, nascondi popolari
                newSlider.style.display = 'block';
                popularSlider.style.display = 'none';
            } else {
                toggle.checked = true;
                labelLeft.classList.remove('text-primary');
                labelLeft.classList.add('text-muted');
                labelRight.classList.remove('text-muted');
                labelRight.classList.add('text-primary');
                
                // Mostra slider popolari, nascondi nuovi
                newSlider.style.display = 'none';
                popularSlider.style.display = 'block';
            }
        };

        // Funzione per seguire un utente
        window.followUser = function(userId) {
            // Verifica se l'utente è autenticato
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

            if (!isAuthenticated) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            const button = document.getElementById('followBtn' + userId);
            const text = document.getElementById('followText' + userId);

            // Disabilita il pulsante durante la richiesta
            button.disabled = true;

            fetch('/api/follow/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Aggiorna il pulsante
                        if (data.following) {
                            button.innerHTML = '<i class="ti ti-user-check"></i><span id="followText' +
                                userId + '">Following</span>';
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-success');
                        } else {
                            button.innerHTML = '<i class="ti ti-user"></i><span id="followText' +
                                userId + '">Follow</span>';
                            button.classList.remove('btn-success');
                            button.classList.add('btn-primary');
                        }

                        // Mostra notifica
                        Swal.fire({
                            icon: 'success',
                            title: 'Successo!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Errore', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Errore connessione follow:', error);
                    Swal.fire('Errore', 'Errore durante l\'operazione', 'error');
                })
                .finally(() => {
                    // Riabilita il pulsante
                    button.disabled = false;
                });
        };

        // Funzione per mostrare messaggio di successo
        window.showSuccessMessage = function(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'position-fixed';
            successDiv.style.cssText =
                'top: 20px; right: 20px; z-index: 10002; background: rgba(40, 167, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
            successDiv.textContent = message;
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        };

        // Funzione per mostrare messaggio di errore
        window.showErrorMessage = function(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'position-fixed';
            errorDiv.style.cssText =
                'top: 20px; right: 20px; z-index: 10002; background: rgba(220, 53, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
            errorDiv.textContent = message;
            document.body.appendChild(errorDiv);

            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        };

        // Event listeners per il modal video (da eseguire quando il DOM è pronto)
        document.addEventListener('DOMContentLoaded', function() {
            // Gestione chiusura modal video con ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const videoModal = document.getElementById('videoPlayerModal');
                    if (videoModal && videoModal.style.display === 'block') {
                        closeVideoModal();
                    }
                }
            });

            // Gestione click fuori dal modal per chiudere
            const videoModal = document.getElementById('videoPlayerModal');
            if (videoModal) {
                videoModal.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeVideoModal();
                    }
                });
            }
        });
    </script>
@endpush

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Hero Carousel -->
            @if ($carousels && $carousels->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel"
                                    data-bs-interval="5000">
                                    @if ($carousels && $carousels->count() > 1)
                                        <div class="carousel-indicators">
                                            @foreach ($carousels as $index => $carousel)
                                                <button type="button" data-bs-target="#heroCarousel"
                                                    data-bs-slide-to="{{ $index }}"
                                                    class="bg-primary {{ $index === 0 ? 'active' : '' }}"
                                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                    aria-label="Slide {{ $index + 1 }}"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="carousel-inner">
                                        @foreach ($carousels as $index => $carousel)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                @if ($carousel->video_path && $carousel->videoUrl)
                                                    <video class="d-block w-100" autoplay muted loop
                                                        style="height: 400px; object-fit: cover;">
                                                        <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                                    </video>
                                                @elseif($carousel->image_path && $carousel->imageUrl)
                                                    <img src="{{ $carousel->imageUrl }}" class="d-block w-100"
                                                        alt="{{ $carousel->title }}"
                                                        style="height: 400px; object-fit: cover;">
                                                @else
                                                    <!-- Fallback per media mancante -->
                                                    <div class="d-block w-100 bg-gradient-primary d-flex align-items-center justify-content-center"
                                                        style="height: 400px;">
                                                        <div class="text-center text-white">
                                                            <i class="ph-duotone ph-image f-s-48 mb-3"></i>
                                                            <h5 class="f-w-600">{{ $carousel->title }}</h5>
                                                            @if ($carousel->description)
                                                                <p class="mb-0">{{ $carousel->description }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <div
                                                    class="carousel-caption d-none d-md-block bg-light-success bg-opacity-75 rounded-3 p-4 mx-auto">
                                                    <h5 class="f-w-600 f-s-24 mb-3 text-dark">{{ $carousel->title }}</h5>
                                                    @if ($carousel->description)
                                                        <p class="mb-4 f-s-16 text-primary">{{ $carousel->description }}
                                                        </p>
                                                    @endif
                                                    @if ($carousel->link_url && $carousel->link_text)
                                                        <a href="{{ $carousel->link_url }}"
                                                            class="btn btn-primary btn-lg hover-effect">
                                                            <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                            {{ $carousel->link_text }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if ($carousels && $carousels->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                                            data-bs-slide="prev">
                                            <i class="ph ph-arrow-circle-left f-s-24 text-primary"></i>
                                            <span class="visually-hidden">{{ __('home.carousel.previous') }}</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                                            data-bs-slide="next">
                                            <i class="ph ph-arrow-circle-right f-s-24 text-primary"></i>
                                            <span class="visually-hidden">{{ __('home.carousel.next') }}</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Upcoming Events Slider Section -->
            @if ($recentEvents && $recentEvents->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                                    {{ __('home.upcoming_events') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="events-slider app-arrow" id="events-slider">
                                    @foreach ($recentEvents->take(10) as $event)
                                        <div class="autoplay-item">
                                            <div class="card overflow-hidden hover-effect h-100">
                                                @if ($event->image_url)
                                                    <img src="{{ $event->image_url }}" class="card-img-top"
                                                        alt="{{ $event->title }}"
                                                        style="height: 200px; object-fit: cover;">
                                                @else
                                                    @php
                                                        $fallbackImages = [
                                                            'assets/images/background/default-event-1.webp',
                                                            'assets/images/background/default-event-2.webp',
                                                            'assets/images/background/default-event-3.webp',
                                                            'assets/images/background/default-event-4.webp',
                                                        ];
                                                        $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                                    @endphp
                                                    <img src="{{ asset($randomImage) }}" class="card-img-top"
                                                        alt="{{ $event->title }}"
                                                        style="height: 200px; object-fit: cover;">
                                                @endif
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title f-w-600">{{ $event->title }}</h5>
                                                    <p class="card-text text-muted f-s-14">
                                                        <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                        {{ $event->venue_name }}
                                                    </p>

                                                    @if ($event->description)
                                                        <p class="card-text">{{ Str::limit($event->description, 80) }}</p>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <p class="card-text">
                                                            <small class="text-body-secondary">
                                                                <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                                {{ $event->start_datetime->format('d/m/Y H:i') }}

                                                            </small>
                                                        </p>
                                                        <div class="d-flex gap-1 justify-content-end">
                                                            @auth

                                                                <a href="#" role="button" class="btn btn-sm py-1 px-2 d-flex align-items-center"
                                                                    data-event-id="{{ $event->id }}"
                                                                    title="Aggiungi/{{ __('wishlist.remove_from_wishlist') }}">
                                                                    <img src="{{ asset('assets/images/like.png') }}"
                                                                        alt="Like" style="width: 25px; height: 25px;">
                                                                </a>
                                                            @else
                                                                <a href="{{ route('login') }}" role="button"
                                                                    class="btn btn-sm py-1 px-2 d-flex align-items-center"
                                                                    title="{{ __('auth.login_required') }}">
                                                                    <img src="{{ asset('assets/images/like.png') }}"
                                                                        alt="Like" style="width: 25px; height: 25px;">
                                                                </a>
                                                            @endauth


                                                            <a href="{{ route('events.show', $event) }}" role="button"
                                                                class="btn btn-sm btn-primary py-1 px-2 d-flex align-items-center">
                                                                <i class="ph-duotone ph-info f-s-12 me-1"></i>{{ __('home.details') }}
                                                            </a>

                                                            <x-report-button :content="$event" type="event"
                                                                size="sm" />

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Video Slider Section -->
            @if (($recentVideos && $recentVideos->count() > 0) || ($popularVideos && $popularVideos->count() > 0))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card equal-card">
                            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="ph-duotone ph-video-camera f-s-16 me-2"></i>
                                    {{ __('home.videos_section') }}
                                </h5>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span id="videosToggleLabelLeft"
                                        class="text-primary f-s-12 me-2">{{ __('common.new') }}</span>
                                    <div class="form-check form-switch mx-2">
                                        <input class="form-check-input" type="checkbox" id="videosToggle"
                                            onchange="toggleVideosContent(this.checked ? 'popular' : 'new')">
                                    </div>
                                    <span id="videosToggleLabelRight"
                                        class="text-muted f-s-12 ms-2">{{ __('common.popular') }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Slider per video nuovi -->
                                <div class="center-mode app-arrow" id="new-videos-slider">
                                    @foreach ($recentVideos as $video)
                                        <div class="item">
                                            <div class="card overflow-hidden hover-effect h-100">
                                                <div class="position-relative">
                                                    @if ($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                                        <img src="{{ $video->thumbnail_url }}" class="card-img-top"
                                                            alt="{{ $video->title }}"
                                                            style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient-light"
                                                            style="height: 200px;">
                                                            <i class="ph-duotone ph-video-camera f-s-48 text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-success f-s-11 fw-bold px-2 py-1 rounded-pill">
                                                            <i class="ph-duotone ph-clock f-s-10 me-1"></i>
                                                            {{ __('common.new') }}
                                                        </span>
                                                    </div>
                                                    <div class="position-absolute top-50 start-50 translate-middle"
                                                        style="cursor: pointer;"
                                                        onclick="openVideoModal({{ $video->id }})">
                                                        <div class="bg-white bg-opacity-90 rounded-circle p-2 d-flex-center"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="ph-duotone ph-play f-s-20 text-primary"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body d-flex flex-column">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-2">
                                                        <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                            {{ Str::limit($video->title, 50) }}
                                                        </a>
                                                    </h6>
                                                    <p class="text-muted f-s-12 mb-2">
                                                        <a href="{{ route('user.show', $video->user) }}"
                                                            class="text-decoration-none hover-effect">
                                                            {{ $video->user->getDisplayName() }}
                                                        </a>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <div class="d-flex gap-2">
                                                            <small class="text-muted f-s-11">
                                                                <i class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($video->views_count ?? 0) }}
                                                            </small>
                                                            <small class="text-muted f-s-11">
                                                                <i class="ph-duotone ph-thumbs-up f-s-10 me-1"></i>{{ number_format($video->likes_count ?? 0) }}
                                                            </small>
                                                            <small class="text-muted f-s-11">
                                                                <i class="ph-duotone ph-chat-circle f-s-10 me-1"></i>{{ number_format($video->comments_count ?? 0) }}
                                                            </small>
                                                        </div>
                                                        <x-report-button :content="$video" type="video" size="sm" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Slider per video popolari -->
                                <div class="center-mode app-arrow" id="popular-videos-slider" style="display: none;">
                                    @foreach ($popularVideos as $video)
                                        <div class="item">
                                            <div class="card overflow-hidden hover-effect h-100">
                                                <div class="position-relative">
                                                    @if ($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                                        <img src="{{ $video->thumbnail_url }}" class="card-img-top"
                                                            alt="{{ $video->title }}"
                                                            style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient-light"
                                                            style="height: 200px;">
                                                            <i class="ph-duotone ph-video-camera f-s-48 text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-warning text-dark f-s-11 fw-bold px-2 py-1 rounded-pill">
                                                            <i class="ph-duotone ph-trophy f-s-10 me-1"></i>
                                                            {{ __('common.popular') }}
                                                        </span>
                                                    </div>
                                                    <div class="position-absolute top-50 start-50 translate-middle"
                                                        style="cursor: pointer;"
                                                        onclick="openVideoModal({{ $video->id }})">
                                                        <div class="bg-white bg-opacity-90 rounded-circle p-2 d-flex-center"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="ph-duotone ph-play f-s-20 text-primary"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body d-flex flex-column">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-2">
                                                        <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                            {{ Str::limit($video->title, 50) }}
                                                        </a>
                                                    </h6>
                                                    <p class="text-muted f-s-12 mb-2">
                                                        <a href="{{ route('user.show', $video->user) }}"
                                                            class="text-decoration-none hover-effect">
                                                            {{ $video->user->getDisplayName() }}
                                                        </a>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <div class="d-flex gap-2">
                                                            <small class="text-muted f-s-11">
                                                                <i class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($video->views_count ?? 0) }}
                                                            </small>
                                                            <small class="text-muted f-s-11">
                                                                <i class="ph-duotone ph-thumbs-up f-s-10 me-1"></i>{{ number_format($video->likes_count ?? 0) }}
                                                            </small>
                                                            <small class="text-muted f-s-11">
                                                                <i class="ph-duotone ph-chat-circle f-s-10 me-1"></i>{{ number_format($video->comments_count ?? 0) }}
                                                            </small>
                                                        </div>
                                                        <x-report-button :content="$video" type="video" size="sm" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-light-primary">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="eshop-cards">
                                        <div class="eshop-cards-body">
                                            <div class="eshop-cards-icon bg-gradient-primary">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-white"></i>
                                            </div>
                                            <h4 class="mb-1 text-primary">{{ number_format($stats['total_videos']) }}</h4>
                                            <p class="mb-0 text-muted">{{ __('home.stats.total_videos') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="eshop-cards">
                                        <div class="eshop-cards-body">
                                            <div class="eshop-cards-icon bg-gradient-success">
                                                <i class="ph-duotone ph-eye f-s-24 text-white"></i>
                                            </div>
                                            <h4 class="mb-1 text-success">{{ number_format($stats['total_views']) }}</h4>
                                            <p class="mb-0 text-muted">{{ __('home.stats.total_views') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="eshop-cards">
                                        <div class="eshop-cards-body">
                                            <div class="eshop-cards-icon bg-gradient-warning">
                                                <i class="ph-duotone ph-calendar f-s-24 text-white"></i>
                                            </div>
                                            <h4 class="mb-1 text-warning">{{ number_format($stats['total_events']) }}</h4>
                                            <p class="mb-0 text-muted">{{ __('home.stats.total_events') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="eshop-cards">
                                        <div class="eshop-cards-body">
                                            <div class="eshop-cards-icon bg-gradient-info">
                                                <i class="ph-duotone ph-users f-s-24 text-white"></i>
                                            </div>
                                            <h4 class="mb-1 text-info">{{ number_format($stats['total_users']) }}</h4>
                                            <p class="mb-0 text-muted">{{ __('home.stats.total_users') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Entry Section - Nuovi Utenti Registrati -->
            @if ($newUsers->count() > 0)

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">
                            <i class="ph-duotone ph-user-plus f-s-16 me-2"></i>
                            {{ __('home.new_users') }}
                        </h5>
                    </div>
                    @foreach ($newUsers->take(3) as $user)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-container"
                                        onclick="window.location.href='{{ route('user.show', $user) }}'"
                                        style="cursor: pointer;">
                                        <div class="image-details">
                                            <div class="profile-image">
                                                <img src="{{ $user->banner_image_url ?? asset('assets/images/avatar/default-banner.webp?v=1') }}"
                                                    alt="{{ $user->name }}" class="w-100 h-100"
                                                    style="object-fit: cover;">
                                            </div>
                                            <div class="profile-pic">
                                                <div class="avatar-upload">
                                                    <div class="avatar-preview">
                                                        <div id="imgPreview">

                                                            <img src="{{ $user->profile_photo_url }}"
                                                                alt="{{ $user->name }}" class="w-100 h-100"
                                                                style="object-fit: cover;">

                                                            <div
                                                                class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                                <span
                                                                    class="text-white fw-bold f-s-20">{{ strtoupper(substr(trim($user->name), 0, 2)) ?: 'U' }}</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="person-details">
                                            <h4 class="f-w-600 mb-1">{{ $user->name }}
                                                @if ($user->nickname)
                                                    <span class="text-muted f-s-14 fw-normal">({{ $user->nickname }})</span>
                                                @endif
                                                @if ($user->verified)
                                                    <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/01.png"
                                                        class="w-20 h-20" alt="instagram-check-mark">
                                                @endif
                                            </h4>
                                            <p class="f-s-12 mb-3">{{ $user->city ?? __('home.location_not_specified') }}</p>
                                            <div class="details">
                                                <div>
                                                    <h4 class="text-primary">{{ $user->poems_count }}</h4>
                                                    <p class="text-secondary f-s-12">{{ __('common.poems') }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-primary">{{ $user->articles_count }}</h4>
                                                    <p class="text-secondary f-s-12">{{ __('common.articles') }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-primary">{{ number_format($user->total_interactions) }}</h4>
                                                    <p class="text-secondary f-s-12">{{ __('home.interactions') }}</p>
                                                </div>
                                            </div>
                                            <div class="my-2">
                                                @auth
                                                    <button type="button"
                                                        class="btn {{ $user->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-primary' }} b-r-22"
                                                        onclick="event.stopPropagation(); followUser({{ $user->id }})"
                                                        id="followBtn{{ $user->id }}">
                                                        <i
                                                            class="ti {{ $user->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user' }}"></i>
                                                        <span
                                                            id="followText{{ $user->id }}">{{ $user->is_followed_by_current_user ?? false ? 'Following' : 'Follow' }}</span>
                                                    </button>
                                                @else
                                                    <div class="text-center">
                                                        <div class="social-counter"
                                                            style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                                            <i class="ti ti-user f-s-24 text-muted" style="opacity: 0.6;"></i>
                                                            <span class="text-secondary f-s-12">Follow</span>
                                                        </div>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <!-- No Videos Available Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card hover-effect border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                    <i class="ph-duotone ph-video-camera-slash f-s-48 text-primary"></i>
                                </div>
                                <h4 class="text-dark f-w-600 mb-2">{{ __('home.no_videos_available') }}</h4>
                                <p class="text-muted mb-3">{{ __('home.no_videos_description') }}</p>
                                @auth
                                    <a href="{{ route('videos.upload') }}" class="btn btn-primary">
                                        <i class="ph-duotone ph-upload me-2"></i>
                                        {{ __('home.upload_first_video') }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        <i class="ph-duotone ph-sign-in me-2"></i>
                                        {{ __('home.login_to_upload') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Poetry and Articles Section -->
            <div class="row">
                <!-- Poetry Section (Left) -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card ">
                        <div
                            class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ph-duotone ph-book-open f-s-16 me-2"></i>
                                {{ __('home.poetry_section') }}
                            </h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="poetryToggleLabelLeft"
                                    class="text-primary f-s-12 me-2">{{ __('common.new') }}</span>
                                <div class="form-check form-switch mx-2">
                                    <input class="form-check-input" type="checkbox" id="poetryToggle"
                                        onchange="togglePoetryContent(this.checked ? 'popular' : 'new')">
                                </div>
                                <span id="poetryToggleLabelRight"
                                    class="text-muted f-s-12 ms-2">{{ __('common.popular') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- New Poetry Content -->
                            <div id="newPoetryContent">
                                <div class="row">
                                    @foreach ($recentPoems ?? [] as $poem)
                                        <div class="col-12 mb-3">
                                            <div class="card  hover-effect border-info">
                                                <div class="card-body pa-15">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="position-relative">
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    @if ($poem->thumbnail_path)
                                                                        <img src="{{ $poem->thumbnail_url }}"
                                                                            alt="{{ $poem->title }}" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <div
                                                                            class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                            <i
                                                                                class="ph-duotone ph-book-open f-s-20 text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-50 start-50 translate-middle">
                                                                    <i
                                                                        class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary">
                                                                {{ Str::limit($poem->title, 40) }}</h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="{{ route('user.show', $poem->user) }}"
                                                                    class="text-decoration-none hover-effect">
                                                                    {{ $poem->user->getDisplayName() }}
                                                                </a>
                                                            </p>
                                                            <div class="d-flex align-items-center">
                                                                <small class="text-muted f-s-11 me-3">
                                                                    <i
                                                                        class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($poem->views_count) }}
                                                                </small>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $poem->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            @if($poem->slug)
                                                                <a href="{{ route('poems.show', $poem->slug) }}"
                                                                    class="btn btn-sm btn-gradient-info hover-effect">
                                                                    <i class="ph-duotone ph-book-open f-s-12"></i>
                                                                </a>
                                                            @else
                                                                <span class="btn btn-sm btn-secondary" title="Poema non disponibile">
                                                                    <i class="ph-duotone ph-book-open f-s-12"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Popular Poetry Content (Hidden by default) -->
                            <div id="popularPoetryContent" style="display: none;">
                                <div class="row">
                                    @foreach ($popularPoems ?? [] as $poem)
                                        <div class="col-12 mb-3">
                                            <div class="card  hover-effect border-info">
                                                <div class="card-body pa-15">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="position-relative">
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    @if ($poem->thumbnail_path)
                                                                        <img src="{{ $poem->thumbnail_url }}"
                                                                            alt="{{ $poem->title }}" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <div
                                                                            class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                            <i
                                                                                class="ph-duotone ph-book-open f-s-20 text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-50 start-50 translate-middle">
                                                                    <i
                                                                        class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary">
                                                                {{ Str::limit($poem->title, 40) }}</h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="{{ route('user.show', $poem->user) }}"
                                                                    class="text-decoration-none hover-effect">
                                                                    {{ $poem->user->getDisplayName() }}
                                                                </a>
                                                            </p>
                                                            <div class="d-flex align-items-center">
                                                                <small class="text-muted f-s-11 me-3">
                                                                    <i
                                                                        class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($poem->views_count) }}
                                                                </small>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-thumbs-up f-s-10 me-1"></i>{{ number_format($poem->likes_count) }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            @if($poem->slug)
                                                                <a href="{{ route('poems.show', $poem->slug) }}"
                                                                    class="btn btn-sm btn-gradient-info hover-effect">
                                                                    <i class="ph-duotone ph-book-open f-s-12"></i>
                                                                </a>
                                                            @else
                                                                <span class="btn btn-sm btn-secondary" title="Poema non disponibile">
                                                                    <i class="ph-duotone ph-book-open f-s-12"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Footer with link to all poems -->
                            <div class="text-center mt-3">
                                <div class="d-flex gap-2 justify-content-center">
                                    @auth
                                        <a href="{{ route('poems.create') }}" class="btn btn-info btn-sm">
                                            <i class="ph-duotone ph-plus f-s-12 me-1"></i>
                                            {{ __('home.create_poetry') }}
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-info btn-sm"
                                            title="{{ __('auth.login_required') }}">
                                            <i class="ph-duotone ph-plus f-s-12 me-1"></i>
                                            {{ __('home.create_poetry') }}
                                        </a>
                                    @endauth
                                    <a href="{{ route('poems.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="ph-duotone ph-arrow-right f-s-12 me-1"></i>
                                        {{ __('home.view_all_poems') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles Section (Right) -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card">
                        <div
                            class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                                {{ __('home.articles_section') }}
                            </h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="articlesToggleLabelLeft"
                                    class="text-primary f-s-12 me-2">{{ __('common.new') }}</span>
                                <div class="form-check form-switch mx-2">
                                    <input class="form-check-input" type="checkbox" id="articlesToggle"
                                        onchange="toggleArticlesContent(this.checked ? 'popular' : 'new')">
                                </div>
                                <span id="articlesToggleLabelRight"
                                    class="text-muted f-s-12 ms-2">{{ __('common.popular') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- New Articles Content -->
                            <div id="newArticlesContent">
                                <div class="row">
                                    @foreach ($recentArticles ?? [] as $article)
                                        <div class="col-12 mb-3">
                                            <div class="card  hover-effect border-warning">
                                                <div class="card-body pa-15">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="position-relative">
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    @if ($article->featured_image)
                                                                        <img src="{{ $article->featured_image_url }}"
                                                                            alt="{{ $article->title }}" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <div
                                                                            class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                            <i
                                                                                class="ph-duotone ph-newspaper f-s-20 text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-50 start-50 translate-middle">
                                                                    <i
                                                                        class="ph-duotone ph-newspaper f-s-12 text-white"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-warning">
                                                                {{ Str::limit($article->title, 40) }}</h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="{{ route('user.show', $article->user) }}"
                                                                    class="text-decoration-none hover-effect">
                                                                    {{ $article->user->getDisplayName() }}
                                                                </a></p>
                                                            <div class="d-flex align-items-center">
                                                                <small class="text-muted f-s-11 me-3">
                                                                    <i
                                                                        class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($article->views_count ?? 0) }}
                                                                </small>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $article->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            @if($article->slug)
                                                                <a href="{{ route('articles.show', $article->slug) }}"
                                                                    class="btn btn-sm btn-gradient-warning hover-effect">
                                                                    <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                                </a>
                                                            @else
                                                                <span class="btn btn-sm btn-secondary" title="Articolo non disponibile">
                                                                    <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Popular Articles Content (Hidden by default) -->
                            <div id="popularArticlesContent" style="display: none;">
                                <div class="row">
                                    @foreach ($popularArticles ?? [] as $article)
                                        <div class="col-12 mb-3">
                                            <div class="card  hover-effect border-warning">
                                                <div class="card-body pa-15">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="position-relative">
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    @if ($article->featured_image)
                                                                        <img src="{{ $article->featured_image_url }}"
                                                                            alt="{{ $article->title }}" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <div
                                                                            class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                            <i
                                                                                class="ph-duotone ph-newspaper f-s-20 text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                </div>
                                                                <div
                                                                    class="position-absolute top-50 start-50 translate-middle">
                                                                    <i
                                                                        class="ph-duotone ph-newspaper f-s-12 text-white"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-warning">
                                                                {{ Str::limit($article->title, 40) }}</h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="{{ route('user.show', $article->user) }}"
                                                                    class="text-decoration-none hover-effect">
                                                                    {{ $article->user->getDisplayName() }}
                                                                </a></p>
                                                            <div class="d-flex align-items-center">
                                                                <small class="text-muted f-s-11 me-3">
                                                                    <i
                                                                        class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($article->views_count ?? 0) }}
                                                                </small>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-thumbs-up f-s-10 me-1"></i>{{ number_format($article->likes_count ?? 0) }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            @if($article->slug)
                                                                <a href="{{ route('articles.show', $article->slug) }}"
                                                                    class="btn btn-sm btn-gradient-warning hover-effect">
                                                                    <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                                </a>
                                                            @else
                                                                <span class="btn btn-sm btn-secondary" title="Articolo non disponibile">
                                                                    <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Video Player Modal a Tutta Pagina -->
    <div class="custom-modal" id="videoPlayerModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px);">
        <div class="modal-content"
            style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column;">
            <div class="modal-header"
                style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title text-white" id="videoPlayerModalLabel">{{ __('home.video_player') }}</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeVideoModal()"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1; padding: 0; position: relative;">
                <!-- Loading indicator -->
                <div class="text-center position-absolute top-50 start-50 translate-middle" id="modalVideoLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('home.loading_video') }}</span>
                    </div>
                    <p class="mt-2 text-white">{{ __('home.loading_video') }}</p>
                </div>

                <!-- Error message -->
                <div class="alert alert-danger position-absolute top-50 start-50 translate-middle" id="modalVideoError"
                    style="display: none; z-index: 1000;">
                    <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                    <span id="modalErrorMessage">{{ __('home.video_loading_error') }}</span>
                </div>

                <!-- Video Container -->
                <div class="video-container position-relative d-flex align-items-center justify-content-center"
                    id="modalVideoContainer" style="display: none; padding: 20px;">
                    <div class="w-100" style="max-width: 1200px;">
                        <div class="video-container position-relative">
                            <!-- Video Player HTML5 Nativo -->
                            <video id="modalVideoPlayer" class="w-100"
                                style="height: 500px; max-height: 500px; object-fit: cover; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); background: #000;"
                                preload="metadata" controls>
                                Il tuo browser non supporta la riproduzione video.
                            </video>

                            <!-- Snap Markers sulla Progress Bar del Player -->
                            <div class="snap-markers-overlay position-absolute" id="modalSnapMarkers"
                                style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">
                                <!-- Snap markers verranno aggiunti dinamicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Pulsante per creare snap con scritta sotto (solo per utenti autenticati) -->
                    @auth
                        <div class="position-absolute" id="modalFloatingSnapButton"
                            style="opacity: 1; transition: opacity 0.3s ease; z-index: 10000; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                                style="width: 60px; height: 60px;" onclick="toggleSnapForm()">
                                <img src="{{ asset('assets/images/snap.png') }}" alt="Snap"
                                    style="width: 28px; height: 28px; filter: brightness(0) invert(1);">
                            </button>
                            <div class="snap-label"
                                style="color: white; font-size: 11px; text-align: center; white-space: nowrap; text-shadow: 0 1px 2px rgba(0,0,0,0.8); font-weight: 500;">
                                {{ __('home.create_snap') }}
                            </div>
                        </div>
                    @endauth

                    <!-- Form inline per creare snap (solo per utenti autenticati) -->
                    @auth
                        <div class="position-absolute" id="modalSnapForm"
                            style="display: none; z-index: 10001; top: 20px; right: 20px; background: rgba(0,0,0,0.9); border-radius: 12px; padding: 20px; min-width: 300px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-white mb-0">{{ __('home.create_snap_button') }}</h6>
                                <button type="button" class="btn-close btn-close-white" onclick="toggleSnapForm()"></button>
                            </div>
                            <form id="inlineSnapForm">
                                <div class="mb-3">
                                    <label for="inlineSnapTitle" class="form-label text-white"
                                        style="font-size: 12px;">{{ __('home.snap_title_optional') }}</label>
                                    <input type="text" class="form-control form-control-sm" id="inlineSnapTitle"
                                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                                </div>
                                <div class="mb-3">
                                    <label for="inlineSnapDescription" class="form-label text-white"
                                        style="font-size: 12px;">{{ __('home.snap_description_optional') }}</label>
                                    <textarea class="form-control form-control-sm" id="inlineSnapDescription" rows="2"
                                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; resize: none;"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white" style="font-size: 12px;">{{ __('home.timestamp') }} <span
                                            id="inlineCurrentTime" class="text-warning">00:00</span></label>
                                    <input type="hidden" id="inlineSnapTimestamp" value="0">
                                    <input type="hidden" id="inlineSnapVideoId" value="">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        onclick="toggleSnapForm()">{{ __('home.cancel') }}</button>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="createInlineSnap()">{{ __('home.create_snap_button') }}</button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script>
    // Variabili globali per il modal video
    let modalCurrentVideoTime = 0;
    let modalVideoDuration = 0;
    let modalSnaps = [];

    // Funzione per aprire il modal video
    function openVideoModal(videoId) {
        // Mostra il modal personalizzato
        const modal = document.getElementById('videoPlayerModal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Previene lo scroll

        // Carica il video
        loadVideoInModal(videoId);
    }

    // Funzione per chiudere il modal video
    function closeVideoModal() {
        const modal = document.getElementById('videoPlayerModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Ripristina lo scroll

        // Ferma il video se in riproduzione
        const videoPlayer = document.getElementById('modalVideoPlayer');
        if (videoPlayer && !videoPlayer.paused) {
            videoPlayer.pause();
        }

        // Reset variabili
        modalCurrentVideoTime = 0;
        modalVideoDuration = 0;
        modalSnaps = [];
    }

    // Funzione per caricare il video nel modal
    async function loadVideoInModal(videoId) {
        const loadingDiv = document.getElementById('modalVideoLoading');
        const errorDiv = document.getElementById('modalVideoError');
        const containerDiv = document.getElementById('modalVideoContainer');
        const videoPlayer = document.getElementById('modalVideoPlayer');

        // Mostra loading
        loadingDiv.style.display = 'block';
        errorDiv.style.display = 'none';
        containerDiv.style.display = 'none';

        try {
            // Prima ottieni i dati del video
            const videoResponse = await fetch(`/api/videos/${videoId}`);
            const videoData = await videoResponse.json();

            if (!videoData.success) {
                throw new Error(videoData.message || 'Errore nel caricamento del video');
            }

            const video = videoData.video;

            // Imposta il titolo del modal
            document.getElementById('videoPlayerModalLabel').textContent = video.title;

            // Usa sempre il player HTML5 nativo
            videoPlayer.style.display = 'block';

            // Ottieni l'URL diretto del video da PeerTube
            const urlResponse = await fetch(`/videos/${videoId}/peertube-url`);
            const urlData = await urlResponse.json();

            // Gestisci il caso in cui il video è ancora in elaborazione
            if (urlData.status === 'processing') {
                throw new Error('Il video è ancora in elaborazione su PeerTube. Riprova tra qualche minuto.');
            }

            if (urlData.success && urlData.files && urlData.files.length > 0) {
                // Usa il primo file disponibile (migliore qualità)
                const videoFile = urlData.files[0];

                // Crea l'elemento source
                const source = document.createElement('source');
                source.src = videoFile.url;
                source.type = 'video/mp4';

                // Rimuovi eventuali source esistenti e aggiungi quello nuovo
                videoPlayer.innerHTML = '';
                videoPlayer.appendChild(source);

                // Forza il caricamento del video
                videoPlayer.load();

                // Inizializza il player del modal
                initializeModalVideoPlayer(video);
            } else {
                throw new Error('Nessuna sorgente video disponibile');
            }

            // Imposta l'ID del video per le funzioni snap
            videoPlayer.setAttribute('data-video-id', video.id);

            // Carica gli snap
            loadSnapsForModal(videoId);

            // Mostra il container del video
            loadingDiv.style.display = 'none';
            containerDiv.style.display = 'block';

        } catch (error) {
            console.error('Errore caricamento video:', error);
            loadingDiv.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('modalErrorMessage').textContent = error.message;
        }
    }

    // Chiudi il modal quando si clicca fuori
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('videoPlayerModal');
        if (event.target === modal) {
            closeVideoModal();
        }
    });

    // Chiudi il modal con il tasto ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVideoModal();
        }
    });

    // Funzione per inizializzare il player del modal
    function initializeModalVideoPlayer(video) {
        const videoPlayer = document.getElementById('modalVideoPlayer');
        modalVideoDuration = video.duration || 60;

        // Event listener per quando i metadati sono caricati
        videoPlayer.addEventListener('loadedmetadata', function() {
            modalVideoDuration = videoPlayer.duration || modalVideoDuration;
            updateModalSnapMarkers();
        });

        // Event listener per quando la durata cambia
        videoPlayer.addEventListener('durationchange', function() {
            modalVideoDuration = videoPlayer.duration;
            updateModalSnapMarkers();
        });
    }

    // Funzione per caricare gli snap nel modal
    function loadSnapsForModal(videoId) {
        fetch(`/api/videos/${videoId}/snaps`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    modalSnaps = data.snaps || [];
                    updateModalSnapMarkers();
                } else {
                    modalSnaps = [];
                    updateModalSnapMarkers();
                }
            })
            .catch(error => {
                console.error('Errore caricamento snap:', error);
                modalSnaps = [];
                updateModalSnapMarkers();
            });
    }

    // Funzione per aggiornare i marker degli snap nel modal
    function updateModalSnapMarkers() {
        const markersContainer = document.getElementById('modalSnapMarkers');
        if (!markersContainer) return;

        markersContainer.innerHTML = '';

        if (!modalSnaps || modalSnaps.length === 0) return;

        // Raggruppa gli snap per timestamp
        const snapGroups = {};
        modalSnaps.forEach(snap => {
            const timestamp = Math.floor(snap.timestamp);
            if (!snapGroups[timestamp]) {
                snapGroups[timestamp] = [];
            }
            snapGroups[timestamp].push(snap);
        });

        // Crea i marker per ogni gruppo
        Object.keys(snapGroups).forEach(timestamp => {
            const snaps = snapGroups[timestamp];
            const marker = document.createElement('div');
            marker.className = 'snap-marker';
            marker.style.position = 'absolute';
            marker.style.bottom = '0';
            marker.style.left = `${(timestamp / modalVideoDuration) * 100}%`;
            marker.style.width = '4px';
            marker.style.height = '100%';
            marker.style.backgroundColor = snaps.length > 1 ? '#ff6b6b' : '#4ecdc4';
            marker.style.cursor = 'pointer';
            marker.title = `${snaps.length} snap${snaps.length > 1 ? 's' : ''} a ${Math.floor(timestamp / 60)}:${(timestamp % 60).toString().padStart(2, '0')}`;

            marker.addEventListener('click', () => {
                const videoPlayer = document.getElementById('modalVideoPlayer');
                if (videoPlayer) {
                    videoPlayer.currentTime = timestamp;
                }
            });

            markersContainer.appendChild(marker);
        });
    }
    </script>
@endsection
