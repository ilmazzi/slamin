<div>
    @if ($recentEvents && $recentEvents->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <a href="{{ route('events.index') }}" class="text-decoration-none text-primary d-flex align-items-center">
                            <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                            Eventi in arrivo
                        </a>
                    </h5>
                    @if ($recentEvents->chunk(2)->count() > 1)
                        <div class="d-flex">
                            <button class="btn btn-sm bg-light-primary text-primary me-2 border-0" type="button" data-bs-target="#eventsCarousel" data-bs-slide="prev">
                                <span class="f-s-12">‹</span>
                            </button>
                            <button class="btn btn-sm bg-light-primary text-primary border-0" type="button" data-bs-target="#eventsCarousel" data-bs-slide="next">
                                <span class="f-s-12">›</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                        @if ($recentEvents->chunk(2)->count() > 1)
                            <div class="carousel-indicators">
                                @foreach ($recentEvents->chunk(2) as $index => $eventChunk)
                                    <button type="button" data-bs-target="#eventsCarousel" data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                        <div class="carousel-inner">
                            @foreach ($recentEvents->chunk(2) as $chunkIndex => $eventChunk)
                                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                    <div class="row g-3 p-3">
                                        @foreach ($eventChunk as $event)
                                            <div class="col-12 col-md-6">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="position-relative">
                                                        @if ($event->image_url)
                                                            <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                                        @else
                                                            <div class="card-img-top bg-light-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                                                                <i class="ph-duotone ph-calendar f-s-48 text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="position-absolute top-0 end-0 m-2">
                                                            <span class="badge bg-primary">{{ $event->start_datetime->format('d M') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <h6 class="card-title f-s-14 f-w-600 mb-2">{{ Str::limit($event->title, 50) }}</h6>
                                                        <p class="card-text text-muted f-s-12 mb-2">
                                                            <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                            {{ $event->venue_name ?? 'Luogo da definire' }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                                {{ $event->start_datetime->format('H:i') }}
                                                            </small>
                                                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                                Vedi
                                                            </a>
                                                        </div>
                                                        <!-- Social Actions -->
                                                        <div class="d-flex justify-content-end mt-2">
                                                            <livewire:social.social-view-counter :model="$event" :size="'sm'" />
                                                            <livewire:social.social-like-button :model="$event" :size="'sm'" />
                                                            <livewire:social.social-comment-button :model="$event" :size="'sm'" />
                                                        </div>
                                                        <!-- Avatar utente cliccabile -->
                                                        <div class="d-flex align-items-center mt-3 pt-2 border-top">
                                                            <a href="{{ route('profile.show', $event->organizer->id) }}" class="text-decoration-none d-flex align-items-center">
                                                                <img src="{{ $event->organizer->profile_photo_url }}" class="rounded-circle me-2" alt="{{ $event->organizer->name }}" style="width: 32px; height: 32px; object-fit: cover;">
                                                                <span class="text-muted f-s-12">{{ $event->organizer->name }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
