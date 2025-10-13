@extends('layout.master')

@section('title', __('admin_general.edit') . ' - ' . $help->title)

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-pencil me-2"></i>
                {{ __('admin_general.edit') }} {{ $help->type === 'faq' ? __('admin_general.faq') : __('admin_general.help') }}
            </h4>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.help.update', $help) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="title" class="form-label">{{ __('admin_general.title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $help->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="content" class="form-label">{{ __('admin_general.content') }} <span class="text-danger">*</span></label>
                                    <div id="quill-editor" style="height: 400px;"></div>
                                    <textarea class="form-control d-none @error('content') is-invalid @enderror"
                                              id="content" name="content" required>{{ old('content', $help->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">{{ __('admin_general.content_help') }}</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="type" class="form-label">{{ __('admin_general.type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="help" {{ (old('type', $help->type) === 'help') ? 'selected' : '' }}>
                                            {{ __('admin_general.help') }}
                                        </option>
                                        <option value="faq" {{ (old('type', $help->type) === 'faq') ? 'selected' : '' }}>
                                            {{ __('admin_general.faq') }}
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="order" class="form-label">{{ __('admin_general.order') }}</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                           id="order" name="order" value="{{ old('order', $help->order) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">{{ __('admin_general.order_help') }}</div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               {{ old('is_active', $help->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('admin_general.is_active') }}
                                        </label>
                                    </div>
                                    <div class="form-text">{{ __('admin_general.is_active_help') }}</div>
                                </div>

                                <!-- Info -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ __('admin_general.info') }}</h6>
                                        <small class="text-muted">
                                            <strong>{{ __('admin_general.created_at') }}:</strong> {{ $help->created_at->format('d/m/Y H:i') }}<br>
                                            <strong>{{ __('admin_general.updated_at') }}:</strong> {{ $help->updated_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.help.index', ['type' => $help->type]) }}" class="btn btn-light">
                                        <i class="ph ph-arrow-left me-2"></i>
                                        {{ __('admin_general.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="ph ph-check me-2"></i>
                                        {{ __('admin_general.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quill Editor (Free, No API Key Required) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: '{{ __('admin_general.content_help') }}'
    });

    // Load existing content
    const existingContent = {!! json_encode(old('content', $help->content)) !!};
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Sync Quill content with hidden textarea
    quill.on('text-change', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });

    // Sync on form submit
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });
</script>
@endsection
