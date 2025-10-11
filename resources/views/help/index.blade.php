@extends('layout.master')

@section('title', __('help.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-question me-2"></i>
                {{ __('help.title') }}
            </h4>
        </div>
    </div>

    <!-- Help Content -->
    <div class="row">
        @if($helps->count() > 0)
            @foreach($helps as $help)
                <div class="col-lg-6 mb-4">
                    <div class="card hover-effect h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="ph ph-question-circle text-primary me-2"></i>
                                {{ $help->title }}
                            </h5>
                            <div class="help-preview">
                                {!! Str::limit(strip_tags($help->content), 150) !!}
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('help.show', $help) }}" class="btn btn-sm btn-light-primary">
                                    <i class="ph ph-arrow-right me-2"></i>
                                    {{ __('help.read_more') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-question f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('help.no_content') }}</h5>
                        <p class="text-muted">{{ __('help.no_content_description') }}</p>
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
                        <a href="{{ route('help.index') }}" class="btn btn-primary">
                            <i class="ph ph-question me-2"></i>
                            {{ __('help.title') }}
                        </a>
                        <a href="{{ route('faq.index') }}" class="btn btn-light-primary">
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
