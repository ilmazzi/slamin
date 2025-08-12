@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.reports_management') }}</h4>
                        <div>
                            <span class="badge bg-warning me-2">{{ __('articles.pending_reports') }}: {{ $pendingCount }}</span>
                            <button class="btn btn-outline-secondary" onclick="refreshStats()">
                                <i class="ti ti-refresh"></i> {{ __('articles.refresh') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtri -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">{{ __('articles.all_statuses') }}</option>
                                <option value="pending">{{ __('articles.pending') }}</option>
                                <option value="reviewed">{{ __('articles.reviewed') }}</option>
                                <option value="resolved">{{ __('articles.resolved') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="reasonFilter">
                                <option value="">{{ __('articles.all_reasons') }}</option>
                                <option value="inappropriate">{{ __('articles.inappropriate') }}</option>
                                <option value="spam">{{ __('articles.spam') }}</option>
                                <option value="copyright">{{ __('articles.copyright') }}</option>
                                <option value="misleading">{{ __('articles.misleading') }}</option>
                                <option value="other">{{ __('articles.other') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="dateFilter" placeholder="{{ __('articles.filter_by_date') }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="ti ti-filter"></i> {{ __('articles.apply_filters') }}
                            </button>
                        </div>
                    </div>

                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('articles.article') }}</th>
                                        <th>{{ __('articles.reporter') }}</th>
                                        <th>{{ __('articles.reason') }}</th>
                                        <th>{{ __('articles.status') }}</th>
                                        <th>{{ __('articles.reported_at') }}</th>
                                        <th>{{ __('articles.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr class="{{ $report->status === 'pending' ? 'table-warning' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($report->article->featured_image)
                                                        <img src="{{ Storage::url($report->article->featured_image) }}" 
                                                             class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ Str::limit($report->article->title, 50) }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ __('articles.by') }} {{ $report->article->user->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $report->user->profile->avatar_url ?? asset('assets/images/avatar/default.png') }}" 
                                                         class="rounded-circle me-2" style="width: 30px; height: 30px;">
                                                    <span>{{ $report->user->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $report->reason === 'inappropriate' ? 'danger' : ($report->reason === 'spam' ? 'warning' : 'secondary') }}">
                                                    {{ __('articles.' . $report->reason) }}
                                                </span>
                                                @if($report->details)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($report->details, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->status === 'pending')
                                                    <span class="badge bg-warning">{{ __('articles.pending') }}</span>
                                                @elseif($report->status === 'reviewed')
                                                    <span class="badge bg-info">{{ __('articles.reviewed') }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ __('articles.resolved') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info" 
                                                            onclick="viewReport({{ $report->id }})">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    @if($report->status === 'pending')
                                                        <button class="btn btn-sm btn-outline-success" 
                                                                onclick="reviewReport({{ $report->id }}, 'reviewed')">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-warning" 
                                                                onclick="reviewReport({{ $report->id }}, 'resolved')">
                                                            <i class="ti ti-shield-check"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($reports->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $reports->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-flag-off display-1 text-muted"></i>
                            <h5 class="mt-3">{{ __('articles.no_reports') }}</h5>
                            <p class="text-muted">{{ __('articles.no_reports_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dettagli Segnalazione -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('articles.report_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reportModalContent">
                <!-- Contenuto del modal -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('articles.close') }}
                </button>
                <div id="reportActions" style="display: none;">
                    <button type="button" class="btn btn-success" onclick="reviewReportFromModal('reviewed')">
                        <i class="ti ti-check"></i> {{ __('articles.mark_reviewed') }}
                    </button>
                    <button type="button" class="btn btn-warning" onclick="reviewReportFromModal('resolved')">
                        <i class="ti ti-shield-check"></i> {{ __('articles.mark_resolved') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentReportId = null;

function viewReport(reportId) {
    currentReportId = reportId;
    
    fetch(`/admin/article-reports/${reportId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const report = data.report;
                const article = report.article;
                const reporter = report.user;
                
                const modalContent = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __('articles.reported_article') }}</h6>
                            <div class="card mb-3">
                                ${article.featured_image ? `<img src="/storage/${article.featured_image}" class="card-img-top" style="height: 200px; object-fit: cover;">` : ''}
                                <div class="card-body">
                                    <h5 class="card-title">${article.title}</h5>
                                    <p class="card-text text-muted">${article.excerpt || ''}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ __('articles.by') }} ${article.user.name}</small>
                                        <small class="text-muted">${article.created_at}</small>
                                    </div>
                                    <a href="/articles/${article.slug}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="ti ti-external-link"></i> {{ __('articles.view_article') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('articles.report_details') }}</h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>{{ __('articles.reporter') }}:</strong>
                                        <div class="d-flex align-items-center mt-1">
                                            <img src="${reporter.profile?.avatar_url || '/assets/images/avatar/default.png'}" 
                                                 class="rounded-circle me-2" style="width: 30px; height: 30px;">
                                            <span>${reporter.name}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('articles.reason') }}:</strong>
                                        <span class="badge bg-${report.reason === 'inappropriate' ? 'danger' : (report.reason === 'spam' ? 'warning' : 'secondary')} ms-2">
                                            {{ __('articles.${report.reason}') }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('articles.details') }}:</strong>
                                        <p class="mt-1">${report.details || '{{ __("articles.no_details_provided") }}'}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('articles.status') }}:</strong>
                                        <span class="badge bg-${report.status === 'pending' ? 'warning' : (report.status === 'reviewed' ? 'info' : 'success')} ms-2">
                                            {{ __('articles.${report.status}') }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ __('articles.reported_at') }}:</strong>
                                        <span class="ms-2">${report.created_at}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('reportModalContent').innerHTML = modalContent;
                
                // Mostra/nascondi azioni in base allo stato
                const actionsDiv = document.getElementById('reportActions');
                if (report.status === 'pending') {
                    actionsDiv.style.display = 'block';
                } else {
                    actionsDiv.style.display = 'none';
                }
                
                new bootstrap.Modal(document.getElementById('reportModal')).show();
            } else {
                showNotification(data.message || '{{ __("articles.error_loading_report") }}', 'error');
            }
        })
        .catch(error => {
            showNotification('{{ __("articles.error_loading_report") }}', 'error');
        });
}

function reviewReport(reportId, status) {
    const statusText = status === 'reviewed' ? '{{ __("articles.reviewed") }}' : '{{ __("articles.resolved") }}';
    
    if (confirm(`{{ __("articles.confirm_mark_as") }} ${statusText}?`)) {
        fetch(`/admin/article-reports/${reportId}/review`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`{{ __("articles.report_marked_as") }} ${statusText}`, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showNotification(data.message || '{{ __("articles.review_error") }}', 'error');
            }
        })
        .catch(error => {
            showNotification('{{ __("articles.review_error") }}', 'error');
        });
    }
}

function reviewReportFromModal(status) {
    if (currentReportId) {
        reviewReport(currentReportId, status);
        bootstrap.Modal.getInstance(document.getElementById('reportModal')).hide();
    }
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const reason = document.getElementById('reasonFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    let url = new URL(window.location);
    if (status) url.searchParams.set('status', status);
    if (reason) url.searchParams.set('reason', reason);
    if (date) url.searchParams.set('date', date);
    
    window.location.href = url.toString();
}

function refreshStats() {
    fetch('/admin/article-reports/pending-count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector('.badge.bg-warning');
                if (badge) {
                    badge.textContent = `{{ __('articles.pending_reports') }}: ${data.count}`;
                }
            }
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
        });
}

function showNotification(message, type = 'info') {
    Swal.fire({
        title: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

// Applica filtri all'avvio se presenti nell'URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('status')) {
        document.getElementById('statusFilter').value = urlParams.get('status');
    }
    if (urlParams.has('reason')) {
        document.getElementById('reasonFilter').value = urlParams.get('reason');
    }
    if (urlParams.has('date')) {
        document.getElementById('dateFilter').value = urlParams.get('date');
    }
});
</script>
@endpush
