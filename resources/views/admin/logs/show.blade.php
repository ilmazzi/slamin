@extends('layout.master')

@section('title', 'Log Details - Admin Panel')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Page Title and Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">📋 Log Details #{{ $log->id }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.logs.index') }}">System Logs</a></li>
                            <li class="breadcrumb-item active">Log #{{ $log->id }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Details Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Log Entry #{{ $log->id }}
                </h5>
                <div class="btn-group">
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Logs
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Basic Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">ID:</td>
                                <td><span class="badge bg-secondary">{{ $log->id }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date & Time:</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }} ({{ $log->created_at->diffForHumans() }})</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Action:</td>
                                <td><code>{{ $log->action }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Category:</td>
                                <td><span class="{{ $log->category_badge_class }}">{{ $log->category }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Level:</td>
                                <td><span class="{{ $log->level_badge_class }}">{{ $log->level }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Description:</td>
                                <td>{{ $log->description }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- User Information -->
                    <div class="col-md-6">
                        <h6 class="mb-3">User Information</h6>
                        @if($log->user)
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">User:</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $log->user->profile_photo_url }}" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div>{{ $log->user->name }}</div>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">User ID:</td>
                                    <td>{{ $log->user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Roles:</td>
                                    <td>
                                        @foreach($log->user->roles as $role)
                                            <span class="badge bg-info me-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        <span class="badge {{ $log->user->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $log->user->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <div class="text-muted">
                                <i class="bi bi-person-x me-2"></i>Guest User
                            </div>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Request Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Request Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">IP Address:</td>
                                <td><code>{{ $log->ip_address ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">URL:</td>
                                <td>
                                    @if($log->url)
                                        <a href="{{ $log->url }}" target="_blank" class="text-decoration-none">
                                            {{ $log->url }}
                                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Method:</td>
                                <td>
                                    @if($log->method)
                                        <span class="badge bg-primary">{{ $log->method }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status Code:</td>
                                <td>
                                    @if($log->status_code)
                                        <span class="badge {{ $log->status_code >= 400 ? 'bg-danger' : ($log->status_code >= 300 ? 'bg-warning' : 'bg-success') }}">
                                            {{ $log->status_code }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Response Time:</td>
                                <td>
                                    @if($log->response_time)
                                        <span class="badge bg-info">{{ $log->response_time }}ms</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Related Model Information -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Related Information</h6>
                        @if($log->related_model && $log->related_id)
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Related Model:</td>
                                    <td><code>{{ $log->related_model }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Related ID:</td>
                                    <td>{{ $log->related_id }}</td>
                                </tr>
                                @if($log->related_model_instance)
                                    <tr>
                                        <td class="fw-bold">Model Status:</td>
                                        <td>
                                            <span class="badge bg-success">Found</span>
                                            <small class="text-muted ms-2">Model instance exists</small>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="fw-bold">Model Status:</td>
                                        <td>
                                            <span class="badge bg-warning">Not Found</span>
                                            <small class="text-muted ms-2">Model instance may have been deleted</small>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        @else
                            <div class="text-muted">
                                <i class="bi bi-info-circle me-2"></i>No related model information
                            </div>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Additional Details -->
                @if($log->details && !empty($log->details))
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Additional Details</h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0" style="white-space: pre-wrap; font-size: 0.875rem;">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Agent -->
                @if($log->user_agent)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">User Agent</h6>
                            <div class="bg-light p-3 rounded">
                                <code class="small">{{ $log->user_agent }}</code>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Logs -->
        @if($log->user_id)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Recent Activity by {{ $log->user->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $recentLogs = \App\Models\ActivityLog::where('user_id', $log->user_id)
                            ->where('id', '!=', $log->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($recentLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Level</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLogs as $recentLog)
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $recentLog->created_at->format('Y-m-d H:i:s') }}
                                                </small>
                                            </td>
                                            <td><code class="small">{{ $recentLog->action }}</code></td>
                                            <td>
                                                <span class="{{ $recentLog->category_badge_class }}">{{ $recentLog->category }}</span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $recentLog->description }}">
                                                    {{ $recentLog->short_description }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="{{ $recentLog->level_badge_class }}">{{ $recentLog->level }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.logs.show', $recentLog) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">No other activity found for this user.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 