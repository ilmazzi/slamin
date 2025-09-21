@extends('layout.master')

@section('title', $help->title)

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-{{ $help->type === 'faq' ? 'chat-circle-question' : 'question' }} me-2"></i>
                {{ $help->title }}
            </h4>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $help->title }}</h5>
                            <small class="text-muted">
                                {{ $help->type === 'faq' ? __('faq.title') : __('help.title') }}
                            </small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $help->type === 'faq' ? 'info' : 'primary' }}">
                                {{ $help->type === 'faq' ? __('faq.title') : __('help.title') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="help-content">
                        {!! $help->content !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                {{ __('help.last_updated') }}: {{ $help->updated_at->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ $help->type === 'faq' ? route('faq.index') : route('help.index') }}"
                                   class="btn btn-outline-secondary">
                                    <i class="ph ph-arrow-left me-2"></i>
                                    {{ __('help.back') }}
                                </a>
                                @if($help->type === 'faq')
                                    <a href="{{ route('help.index') }}" class="btn btn-outline-primary">
                                        <i class="ph ph-question me-2"></i>
                                        {{ __('help.title') }}
                                    </a>
                                @else
                                    <a href="{{ route('faq.index') }}" class="btn btn-outline-info">
                                        <i class="ph ph-chat-circle me-2"></i>
                                        {{ __('faq.title') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.help-content {
    line-height: 1.6;
    font-size: 1.1rem;
}

.help-content h1,
.help-content h2,
.help-content h3,
.help-content h4,
.help-content h5,
.help-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
    font-weight: 600;
}

.help-content h1:first-child,
.help-content h2:first-child,
.help-content h3:first-child {
    margin-top: 0;
}

.help-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.help-content ul,
.help-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.help-content li {
    margin-bottom: 0.5rem;
}

.help-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #666;
    background-color: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
}

.help-content code {
    background-color: #f8f9fa;
    padding: 0.3rem 0.6rem;
    border-radius: 0.375rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    color: #e83e8c;
}

.help-content pre {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    border: 1px solid #e9ecef;
    margin: 1.5rem 0;
}

.help-content pre code {
    background: none;
    padding: 0;
    color: inherit;
}

.help-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
}

.help-content table th,
.help-content table td {
    border: 1px solid #dee2e6;
    padding: 1rem;
    text-align: left;
}

.help-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.help-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem 0;
}

.help-content a {
    color: #007bff;
    text-decoration: none;
}

.help-content a:hover {
    color: #0056b3;
    text-decoration: underline;
}

.help-content strong {
    font-weight: 600;
    color: #333;
}

.help-content em {
    font-style: italic;
    color: #666;
}
</style>
@endsection
