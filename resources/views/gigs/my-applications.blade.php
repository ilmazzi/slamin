@extends('layout.master')

@section('title', __('gigs.applications.my_applications'))

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ __('gigs.applications.my_applications') }}</h4>
                <div class="page-title-right">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph ph-user-plus me-2"></i>{{ __('gigs.applications.my_applications') }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary">{{ $stats['total_applications'] }} {{ __('gigs.applications.title') }}</span>
                            <span class="badge bg-warning">{{ $stats['pending_applications_count'] }} {{ __('gigs.applications.pending_applications') }}</span>
                            <span class="badge bg-success">{{ $stats['accepted_applications_count'] }} {{ __('gigs.applications.accepted_applications') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>{{ __('gigs.fields.title') }}</th>
                                        <th>{{ __('gigs.fields.event') }}</th>
                                        <th>{{ __('gigs.applications.message') }}</th>
                                        <th>{{ __('gigs.status.title') }}</th>
                                        <th>{{ __('common.created_at') }}</th>
                                        <th>{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">Gig</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('gigs.show', $application->gig) }}" class="text-decoration-none">
                                                    {{ $application->title }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $application->gig->event->title ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $application->message }}">
                                                    {{ Str::limit($application->message, 100) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($application->status === 'pending')
                                                    <span class="badge bg-warning">{{ __('gigs.applications.pending') }}</span>
                                                @elseif($application->status === 'accepted')
                                                    <span class="badge bg-success">{{ __('gigs.applications.accepted') }}</span>
                                                @elseif($application->status === 'rejected')
                                                    <span class="badge bg-danger">{{ __('gigs.applications.rejected') }}</span>
                                                @elseif($application->status === 'withdrawn')
                                                    <span class="badge bg-secondary">{{ __('gigs.applications.withdrawn') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if($application->type === 'translation')
                                                        <a href="{{ route('poems.show', $application->poem->slug) }}" class="btn btn-sm btn-light">
                                                            <i class="ph ph-eye"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('gigs.show', $application->gig) }}" class="btn btn-sm btn-light">
                                                            <i class="ph ph-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if($application->gig->gig_type === 'translation' && in_array($application->status, ['pending', 'accepted']))
                                                        <a href="{{ route('translations.negotiation.show', $application) }}" class="btn btn-sm btn-info">
                                                            <i class="ph ph-chat-circle"></i>
                                                        </a>
                                                    @endif

                                                    @if($application->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-danger" onclick="withdrawApplication('{{ $application->id }}', '{{ $application->type }}')">
                                                            <i class="ph ph-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph ph-user-plus f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('gigs.messages.no_my_applications') }}</h5>
                            <p class="text-muted">{{ __('gigs.messages.no_my_applications_description') }}</p>
                            <a href="{{ route('gigs.index') }}" class="btn btn-primary">
                                <i class="ph ph-search me-2"></i>{{ __('gigs.browse_all') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function withdrawApplication(applicationId, type) {
    Swal.fire({
        title: '{{ __("gigs.applications.withdraw_confirm") }}',
        text: '{{ __("common.confirm_action") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("gigs.applications.withdraw") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            let url;
            url = `/gig-applications/${applicationId.replace('gig_', '')}/withdraw`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("common.success") }}',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("common.error") }}',
                        text: data.error,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: '{{ __("common.error") }}',
                    text: '{{ __("common.unexpected_error") }}',
                    icon: 'error'
                });
            });
        }
    });
}
</script>
@endpush
@endsection
