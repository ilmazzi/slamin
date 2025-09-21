@extends('layout.master')

@section('title', __('admin.help_management'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-{{ $type === 'faq' ? 'chat-circle-question' : 'question' }} me-2"></i>
                {{ $type === 'faq' ? __('admin.faq_management') : __('admin.help_management') }}
            </h4>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.help.index', ['type' => 'help']) }}"
                                   class="btn {{ $type === 'help' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ph ph-question me-2"></i>
                                    {{ __('admin.help_pages') }}
                                </a>
                                <a href="{{ route('admin.help.index', ['type' => 'faq']) }}"
                                   class="btn {{ $type === 'faq' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="ph ph-chat-circle-question me-2"></i>
                                    {{ __('admin.faq_pages') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.help.create', ['type' => $type]) }}" class="btn btn-success">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('admin.add_new') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($helps->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.title') }}</th>
                                        <th>{{ __('admin.type') }}</th>
                                        <th>{{ __('admin.order') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                        <th>{{ __('admin.created_at') }}</th>
                                        <th>{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($helps as $help)
                                        <tr>
                                            <td>
                                                <strong>{{ $help->title }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $help->type === 'faq' ? 'info' : 'primary' }}">
                                                    {{ $help->type === 'faq' ? __('admin.faq') : __('admin.help') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $help->order }}</span>
                                            </td>
                                            <td>
                                                @if($help->is_active)
                                                    <span class="badge bg-success">{{ __('admin.active') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('admin.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $help->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.help.show', $help) }}"
                                                       class="btn btn-outline-info" title="{{ __('admin.view') }}">
                                                        <i class="ph ph-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.help.edit', $help) }}"
                                                       class="btn btn-outline-primary" title="{{ __('admin.edit') }}">
                                                        <i class="ph ph-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.help.toggle', $help) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn btn-outline-{{ $help->is_active ? 'warning' : 'success' }}"
                                                                title="{{ $help->is_active ? __('admin.deactivate') : __('admin.activate') }}">
                                                            <i class="ph ph-{{ $help->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.help.destroy', $help) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="{{ __('admin.delete') }}">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $helps->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph ph-{{ $type === 'faq' ? 'chat-circle-question' : 'question' }} f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin.no_content_found') }}</h5>
                            <p class="text-muted">{{ $type === 'faq' ? __('admin.no_faq_description') : __('admin.no_help_description') }}</p>
                            <a href="{{ route('admin.help.create', ['type' => $type]) }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('admin.add_first') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
