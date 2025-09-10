@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.layout_preview') }}</h4>
                        <div>
                            <a href="{{ route('articles.layout.index') }}" class="btn btn-outline-primary">
                                <i class="ti ti-arrow-left"></i> {{ __('articles.back_to_layout') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Layout Preview -->
                        <div class="col-12">
                            <div class="layout-preview">
                                <!-- Banner principale -->
                                @if(isset($layoutArticles['banner']))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">{{ __('articles.banner') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        @if($layoutArticles['banner']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['banner']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['banner']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-1">{{ $layoutArticles['banner']->title }}</h4>
                                                            <p class="text-muted mb-2">{{ Str::limit($layoutArticles['banner']->excerpt, 150) }}</p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i>{{ $layoutArticles['banner']->user->name }}
                                                                <i class="ti ti-calendar ms-3 me-1"></i>{{ $layoutArticles['banner']->created_at->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Prima riga: 2 colonne -->
                                <div class="row mb-4">
                                    @if(isset($layoutArticles['column1']))
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">{{ __('articles.column1') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        @if($layoutArticles['column1']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['column1']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['column1']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $layoutArticles['column1']->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ Str::limit($layoutArticles['column1']->excerpt, 100) }}</p>
                                                            <small class="text-muted">{{ $layoutArticles['column1']->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(isset($layoutArticles['column2']))
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">{{ __('articles.column2') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        @if($layoutArticles['column2']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['column2']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['column2']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $layoutArticles['column2']->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ Str::limit($layoutArticles['column2']->excerpt, 100) }}</p>
                                                            <small class="text-muted">{{ $layoutArticles['column2']->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Articolo orizzontale 1 -->
                                @if(isset($layoutArticles['horizontal1']))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">{{ __('articles.horizontal1') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        @if($layoutArticles['horizontal1']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['horizontal1']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['horizontal1']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1">{{ $layoutArticles['horizontal1']->title }}</h5>
                                                            <p class="text-muted mb-2">{{ Str::limit($layoutArticles['horizontal1']->excerpt, 200) }}</p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i>{{ $layoutArticles['horizontal1']->user->name }}
                                                                <i class="ti ti-calendar ms-3 me-1"></i>{{ $layoutArticles['horizontal1']->created_at->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Articolo orizzontale 2 -->
                                @if(isset($layoutArticles['horizontal2']))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">{{ __('articles.horizontal2') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        @if($layoutArticles['horizontal2']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['horizontal2']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['horizontal2']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1">{{ $layoutArticles['horizontal2']->title }}</h5>
                                                            <p class="text-muted mb-2">{{ Str::limit($layoutArticles['horizontal2']->excerpt, 200) }}</p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i>{{ $layoutArticles['horizontal2']->user->name }}
                                                                <i class="ti ti-calendar ms-3 me-1"></i>{{ $layoutArticles['horizontal2']->created_at->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Seconda riga: 2 colonne -->
                                <div class="row mb-4">
                                    @if(isset($layoutArticles['column3']))
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">{{ __('articles.column3') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        @if($layoutArticles['column3']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['column3']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['column3']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $layoutArticles['column3']->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ Str::limit($layoutArticles['column3']->excerpt, 100) }}</p>
                                                            <small class="text-muted">{{ $layoutArticles['column3']->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(isset($layoutArticles['column4']))
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">{{ __('articles.column4') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        @if($layoutArticles['column4']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['column4']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['column4']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $layoutArticles['column4']->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ Str::limit($layoutArticles['column4']->excerpt, 100) }}</p>
                                                            <small class="text-muted">{{ $layoutArticles['column4']->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Articolo orizzontale 3 -->
                                @if(isset($layoutArticles['horizontal3']))
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">{{ __('articles.horizontal3') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        @if($layoutArticles['horizontal3']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['horizontal3']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['horizontal3']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1">{{ $layoutArticles['horizontal3']->title }}</h5>
                                                            <p class="text-muted mb-2">{{ Str::limit($layoutArticles['horizontal3']->excerpt, 200) }}</p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i>{{ $layoutArticles['horizontal3']->user->name }}
                                                                <i class="ti ti-calendar ms-3 me-1"></i>{{ $layoutArticles['horizontal3']->created_at->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Terza riga: 2 colonne -->
                                <div class="row mb-4">
                                    @if(isset($layoutArticles['column5']))
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">{{ __('articles.column5') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        @if($layoutArticles['column5']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['column5']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['column5']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $layoutArticles['column5']->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ Str::limit($layoutArticles['column5']->excerpt, 100) }}</p>
                                                            <small class="text-muted">{{ $layoutArticles['column5']->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(isset($layoutArticles['column6']))
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">{{ __('articles.column6') }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        @if($layoutArticles['column6']->featured_image)
                                                            <img src="{{ Storage::url($layoutArticles['column6']->featured_image) }}"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="{{ $layoutArticles['column6']->title }}">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $layoutArticles['column6']->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ Str::limit($layoutArticles['column6']->excerpt, 100) }}</p>
                                                            <small class="text-muted">{{ $layoutArticles['column6']->user->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.layout-preview {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
    border: 2px dashed #dee2e6;
}

.article-preview-card {
    transition: all 0.3s ease;
}

.article-preview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .layout-preview {
        padding: 1rem;
    }

    .article-preview-card .d-flex {
        flex-direction: column;
        text-align: center;
    }

    .article-preview-card img,
    .article-preview-card .bg-light {
        margin: 0 auto 1rem auto;
    }
}
</style>
@endpush
