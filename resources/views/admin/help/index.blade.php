@extends('layout.master')

@section('title', __('admin_general.help_management'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-{{ $type === 'faq' ? 'chat-circle-question' : 'question' }} me-2"></i>
                {{ $type === 'faq' ? __('admin_general.faq_management') : __('admin_general.help_management') }}
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
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.help.index', ['type' => 'help']) }}"
                                   class="btn {{ $type === 'help' ? 'btn-primary' : 'btn-light-primary' }}">
                                    <i class="ph ph-question me-2"></i>
                                    {{ __('admin_general.help_pages') }}
                                </a>
                                <a href="{{ route('admin.help.index', ['type' => 'faq']) }}"
                                   class="btn {{ $type === 'faq' ? 'btn-primary' : 'btn-light-primary' }}">
                                    <i class="ph ph-chat-circle me-2"></i>
                                    {{ __('admin_general.faq_pages') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.help.create', ['type' => $type]) }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('admin_general.add_new') }}
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
                                        <th>{{ __('admin_general.title') }}</th>
                                        <th>{{ __('admin_general.type') }}</th>
                                        <th>{{ __('admin_general.order') }}</th>
                                        <th>{{ __('admin_general.status') }}</th>
                                        <th>{{ __('admin_general.created_at') }}</th>
                                        <th>{{ __('admin_general.actions') }}</th>
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
                                                    {{ $help->type === 'faq' ? __('admin_general.faq') : __('admin_general.help') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $help->order }}</span>
                                            </td>
                                            <td>
                                                @if($help->is_active)
                                                    <span class="badge bg-success">{{ __('admin_general.active') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('admin_general.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $help->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.help.show', $help) }}"
                                                       class="btn btn-sm btn-light-info" title="{{ __('admin_general.view') }}">
                                                        <i class="ph ph-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.help.edit', $help) }}"
                                                       class="btn btn-sm btn-light-primary" title="{{ __('admin_general.edit') }}">
                                                        <i class="ph ph-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.help.toggle', $help) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-light-{{ $help->is_active ? 'warning' : 'success' }}"
                                                                title="{{ $help->is_active ? __('admin_general.deactivate') : __('admin_general.activate') }}">
                                                            <i class="ph ph-{{ $help->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-light-danger" 
                                                            onclick="deleteHelp({{ $help->id }})"
                                                            title="{{ __('admin_general.delete') }}">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
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
                            <h5 class="text-muted">{{ __('admin_general.no_content_found') }}</h5>
                            <p class="text-muted">{{ $type === 'faq' ? __('admin_general.no_faq_description') : __('admin_general.no_help_description') }}</p>
                            <a href="{{ route('admin.help.create', ['type' => $type]) }}" class="btn btn-primary btn-lg">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('admin_general.add_first') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteHelp(helpId) {
    Swal.fire({
        title: '{{ __("common.are_you_sure") }}',
        text: '{{ __('admin_general.confirm_delete') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("common.yes_delete") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/help/${helpId}`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
