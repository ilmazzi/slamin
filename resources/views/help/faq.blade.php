@extends('layout.master')

@section('title', __('faq.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-chat-circle me-2"></i>
                {{ __('faq.title') }}
            </h4>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="row">
        @if($faqs->count() > 0)
            <div class="col-12">
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $faq->id }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $faq->id }}">
                                    <i class="ph ph-question text-primary me-2"></i>
                                    {{ $faq->title }}
                                </button>
                            </h2>
                            <div id="collapse{{ $faq->id }}"
                                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                 aria-labelledby="heading{{ $faq->id }}"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <div class="faq-content">
                                        {!! $faq->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-chat-circle f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('faq.no_content') }}</h5>
                        <p class="text-muted">{{ __('faq.no_content_description') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('help.index') }}" class="btn btn-light-primary">
                            <i class="ph ph-question me-2"></i>
                            {{ __('help.title') }}
                        </a>
                        <a href="{{ route('faq.index') }}" class="btn btn-primary">
                            <i class="ph ph-chat-circle me-2"></i>
                            {{ __('faq.title') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
