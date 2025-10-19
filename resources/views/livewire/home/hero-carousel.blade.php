<div>
    @if ($carousels && $carousels->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
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
                                            <video class="d-block w-100" autoplay muted loop style="height: 400px; object-fit: cover;">
                                                <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                            </video>
                                            <div class="carousel-caption d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                <h5 class="f-w-600 f-s-24 mb-3 text-dark">{{ $carousel->content_title ?? $carousel->title }}</h5>
                                                @if ($carousel->content_description ?? $carousel->description)
                                                    <p class="mb-4 f-s-16 text-primary">{{ $carousel->content_description ?? $carousel->description }}</p>
                                                @endif
                                                @if ($carousel->content_url ?? $carousel->link_url)
                                                    <a href="{{ $carousel->content_url ?? $carousel->link_url }}" class="btn btn-primary btn-lg">
                                                        <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                        {{ $carousel->link_text ?? 'Visualizza' }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($carousel->image_path && $carousel->imageUrl)
                                            <img src="{{ $carousel->imageUrl }}" class="d-block w-100" alt="{{ $carousel->title }}" style="height: 400px; object-fit: cover;">
                                            <div class="carousel-caption d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                <h5 class="f-w-600 f-s-24 mb-3 text-dark">{{ $carousel->content_title ?? $carousel->title }}</h5>
                                                @if ($carousel->content_description ?? $carousel->description)
                                                    <p class="mb-4 f-s-16 text-primary">{{ $carousel->content_description ?? $carousel->description }}</p>
                                                @endif
                                                @if ($carousel->content_url ?? $carousel->link_url)
                                                    <a href="{{ $carousel->content_url ?? $carousel->link_url }}" class="btn btn-primary btn-lg">
                                                        <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                        {{ $carousel->link_text ?? 'Visualizza' }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if ($carousels && $carousels->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
