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
                                {{ $help->type === 'faq' ? __('admin_general.faq') : __('admin_general.help') }} •
                                {{ __('admin_general.order') }}: {{ $help->order }}
                            </small>
                        </div>
                        <div>
                            @if($help->is_active)
                                <span class="badge bg-success">{{ __('admin_general.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('admin_general.inactive') }}</span>
                            @endif
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
                                <strong>{{ __('admin_general.created_at') }}:</strong> {{ $help->created_at->format('d/m/Y H:i') }}<br>
                                <strong>{{ __('admin_general.updated_at') }}:</strong> {{ $help->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.help.edit', $help) }}" class="btn btn-primary">
                                    <i class="ph ph-pencil me-2"></i>
                                    {{ __('admin_general.edit') }}
                                </a>
                                <a href="{{ route('admin.help.index', ['type' => $help->type]) }}" class="btn btn-secondary">
                                    <i class="ph ph-arrow-left me-2"></i>
                                    {{ __('admin_general.back_to_list') }}
                                </a>
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
}

.help-content h1,
.help-content h2,
.help-content h3,
.help-content h4,
.help-content h5,
.help-content h6 {
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.help-content p {
    margin-bottom: 1rem;
}

.help-content ul,
.help-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.help-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #666;
}

.help-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
}

.help-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
}

.help-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.help-content table th,
.help-content table td {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    text-align: left;
}

.help-content table th {
    background-color: #f8f9fa;
    font-weight: bold;
}
</style>
@endsection
