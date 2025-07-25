@extends('layout.master')

@section('title', 'System Logs - Admin Panel')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Page Title and Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">📊 System Logs</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">System Logs</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-12 col-lg-3">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ number_format($stats['total_logs']) }}</h4>
                                <p class="mb-0 text-muted">Total Logs</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-journal-text fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card card-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ number_format($stats['today_logs']) }}</h4>
                                <p class="mb-0 text-muted">Today's Logs</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-day fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card card-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ number_format($stats['error_count']) }}</h4>
                                <p class="mb-0 text-muted">Errors ({{ $stats['error_rate'] }}%)</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $stats['avg_response_time'] }}ms</h4>
                                <p class="mb-0 text-muted">Avg Response Time</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="bi bi-speedometer2 fs-1 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filters
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.logs.index') }}" id="logFiltersForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                @foreach($filterOptions['categories'] as $key => $name)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Level</label>
                            <select class="form-select" name="level">
                                <option value="">All Levels</option>
                                @foreach($filterOptions['levels'] as $key => $name)
                                    <option value="{{ $key }}" {{ request('level') == $key ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">User</label>
                            <select class="form-select" name="user_id">
                                <option value="">All Users</option>
                                @foreach($filterOptions['users'] as $id => $name)
                                    <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                   placeholder="Search in description, action, IP...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status Code</label>
                            <select class="form-select" name="status_code">
                                <option value="">All Codes</option>
                                @foreach($filterOptions['status_codes'] as $code => $description)
                                    <option value="{{ $code }}" {{ request('status_code') == $code ? 'selected' : '' }}>
                                        {{ $description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Action</label>
                            <input type="text" class="form-control" name="action" value="{{ request('action') }}" 
                                   placeholder="Filter by action...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>Activity Logs
                    <span class="badge bg-secondary ms-2">{{ $logs->total() }}</span>
                </h5>
                <div class="btn-group">
                    <a href="{{ route('admin.logs.export') }}?{{ http_build_query(request()->all()) }}" 
                       class="btn btn-outline-success btn-sm">
                        <i class="bi bi-download me-1"></i>Export CSV
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                        <i class="bi bi-trash me-1"></i>Clear Old Logs
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Level</th>
                                    <th>IP</th>
                                    <th>Response</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $log->id }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $log->user->profile_photo_url }}" 
                                                         class="rounded-circle me-2" width="24" height="24">
                                                    <span>{{ $log->user->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Guest</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code class="small">{{ $log->action }}</code>
                                        </td>
                                        <td>
                                            <span class="{{ $log->category_badge_class }}">{{ $log->category }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $log->description }}">
                                                {{ $log->short_description }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="{{ $log->level_badge_class }}">{{ $log->level }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->ip_address }}</small>
                                        </td>
                                        <td>
                                            @if($log->status_code)
                                                <span class="badge {{ $log->status_code >= 400 ? 'bg-danger' : ($log->status_code >= 300 ? 'bg-warning' : 'bg-success') }}">
                                                    {{ $log->status_code }}
                                                </span>
                                                @if($log->response_time)
                                                    <br><small class="text-muted">{{ $log->response_time }}ms</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.logs.show', $log) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->appends(request()->all())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted"></i>
                        <h5 class="mt-3">No logs found</h5>
                        <p class="text-muted">No activity logs match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear Old Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.logs.clear') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>This will permanently delete log entries older than the specified number of days.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Delete logs older than (days)</label>
                        <input type="number" class="form-control" name="days" value="30" min="1" max="365">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Category (optional)</label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            @foreach($filterOptions['categories'] as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Level (optional)</label>
                        <select class="form-select" name="level">
                            <option value="">All Levels</option>
                            @foreach($filterOptions['levels'] as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Clear Logs</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#logFiltersForm select, #logFiltersForm input[type="date"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('logFiltersForm').submit();
        });
    });

    // Debounced search
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('logFiltersForm').submit();
            }, 500);
        });
    }
});
</script>
@endpush 