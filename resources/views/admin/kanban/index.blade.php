@extends('layout.master')

@section('title', 'Kanban Board - Admin')

@section('main-content')
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="col-12">
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-gauge f-s-16"></i> Admin
                        </span>
                    </a>
                </li>
                <li class="active">
                    <span class="f-s-14 f-w-500">
                        <i class="ph-duotone ph-kanban f-s-16"></i> Kanban Board
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-list text-primary f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Totali</h6>
                            <h4 class="mb-0">{{ $stats['total_tasks'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-check-circle text-success f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Completati</h6>
                            <h4 class="mb-0">{{ $stats['completed_tasks'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-warning text-warning f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scaduti</h6>
                            <h4 class="mb-0">{{ $stats['overdue_tasks'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-calendar text-info f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Oggi</h6>
                            <h4 class="mb-0">{{ $stats['tasks_due_today'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-clock text-secondary f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Ore Stimate</h6>
                            <h4 class="mb-0">{{ $stats['total_hours_estimated'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-timer text-dark f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Ore Effettive</h6>
                            <h4 class="mb-0">{{ $stats['total_hours_actual'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="ph ph-kanban me-2"></i>Gestione Task di Sviluppo</h4>
                        <p class="mb-0 opacity-75">Trascina le card per cambiare lo stato dei task</p>
                    </div>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="ph ph-plus me-2"></i>Nuovo Task
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="kanban-board-container" style="min-height: 600px;">
                        <div class="board">
                            <!-- TODO COLUMN -->
                            <div class="board-column">
                                <div class="board-column-header bg-light border-bottom p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-list me-2 text-secondary f-s-18"></i>
                                            <h6 class="mb-0 text-secondary f-w-600">TODO</h6>
                                        </div>
                                        <span class="badge bg-secondary rounded-pill">{{ $data['todo_tasks']->count() }}</span>
                                    </div>
                                </div>
                                <div class="board-column-content-wrapper p-3" style="min-height: 500px;">
                                    <div class="board-column-content">
                                        @foreach($data['todo_tasks'] as $task)
                                        <div class="board-item mb-3" data-task-id="{{ $task->id }}" data-status="todo">
                                            <div class="board-item-content card shadow-sm border-0 hover-effect {{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-0' : '' }}">
                                                @if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0)
                                                    <div class="position-relative">
                                                        @php
                                                            $images = array_filter($task->attachments, fn($a) => $a['type'] === 'image');
                                                            $firstImage = reset($images);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $firstImage['path']) }}" class="img-fluid" alt="{{ $firstImage['original_name'] }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                                        @if(count($images) > 1)
                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                <span class="badge bg-dark">{{ count($images) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="{{ $task->getCategoryIcon() }} f-s-20"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-1 f-w-600">{{ Str::limit($task->title, 35) }}</h6>
                                                            @if($task->description)
                                                            <p class="text-muted mb-2 f-s-12">{{ Str::limit($task->description, 60) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-11">
                                                            <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedTo)
                                                        <span class="badge text-bg-info f-s-11">
                                                            <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                        </span>
                                                        @endif
                                                        @if($task->due_date)
                                                        <span class="badge text-bg-{{ $task->isOverdue() ? 'danger' : 'warning' }} f-s-11">
                                                            <i class="ph ph-calendar me-1"></i>{{ $task->due_date->format('M d') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- IN PROGRESS COLUMN -->
                            <div class="board-column">
                                <div class="board-column-header bg-light border-bottom p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-arrow-clockwise me-2 text-primary f-s-18"></i>
                                            <h6 class="mb-0 text-primary f-w-600">IN PROGRESS</h6>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $data['in_progress_tasks']->count() }}</span>
                                    </div>
                                </div>
                                <div class="board-column-content-wrapper p-3" style="min-height: 500px;">
                                    <div class="board-column-content">
                                        @foreach($data['in_progress_tasks'] as $task)
                                        <div class="board-item mb-3" data-task-id="{{ $task->id }}" data-status="in_progress">
                                            <div class="board-item-content card shadow-sm border-0 hover-effect {{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-0' : '' }}">
                                                @if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0)
                                                    <div class="position-relative">
                                                        @php
                                                            $images = array_filter($task->attachments, fn($a) => $a['type'] === 'image');
                                                            $firstImage = reset($images);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $firstImage['path']) }}" class="img-fluid" alt="{{ $firstImage['original_name'] }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                                        @if(count($images) > 1)
                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                <span class="badge bg-dark">{{ count($images) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="{{ $task->getCategoryIcon() }} f-s-20"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-1 f-w-600">{{ Str::limit($task->title, 35) }}</h6>
                                                            @if($task->description)
                                                            <p class="text-muted mb-2 f-s-12">{{ Str::limit($task->description, 60) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-11">
                                                            <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedTo)
                                                        <span class="badge text-bg-info f-s-11">
                                                            <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                        </span>
                                                        @endif
                                                        @if($task->progress_percentage > 0)
                                                        <span class="badge text-bg-{{ $task->getProgressBarColor() }} f-s-11">
                                                            <i class="ph ph-percent me-1"></i>{{ $task->progress_percentage }}%
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- REVIEW COLUMN -->
                            <div class="board-column">
                                <div class="board-column-header bg-light border-bottom p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-eye me-2 text-warning f-s-18"></i>
                                            <h6 class="mb-0 text-warning f-w-600">REVIEW</h6>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">{{ $data['review_tasks']->count() }}</span>
                                    </div>
                                </div>
                                <div class="board-column-content-wrapper p-3" style="min-height: 500px;">
                                    <div class="board-column-content">
                                        @foreach($data['review_tasks'] as $task)
                                        <div class="board-item mb-3" data-task-id="{{ $task->id }}" data-status="review">
                                            <div class="board-item-content card shadow-sm border-0 hover-effect {{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-0' : '' }}">
                                                @if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0)
                                                    <div class="position-relative">
                                                        @php
                                                            $images = array_filter($task->attachments, fn($a) => $a['type'] === 'image');
                                                            $firstImage = reset($images);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $firstImage['path']) }}" class="img-fluid" alt="{{ $firstImage['original_name'] }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                                        @if(count($images) > 1)
                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                <span class="badge bg-dark">{{ count($images) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="{{ $task->getCategoryIcon() }} f-s-20"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-1 f-w-600">{{ Str::limit($task->title, 35) }}</h6>
                                                            @if($task->description)
                                                            <p class="text-muted mb-2 f-s-12">{{ Str::limit($task->description, 60) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-11">
                                                            <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedTo)
                                                        <span class="badge text-bg-info f-s-11">
                                                            <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                        </span>
                                                        @endif
                                                        @if($task->reviewedBy)
                                                        <span class="badge text-bg-success f-s-11">
                                                            <i class="ph ph-check me-1"></i>{{ Str::limit($task->reviewedBy->getDisplayName(), 15) }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- TESTING COLUMN -->
                            <div class="board-column">
                                <div class="board-column-header bg-light border-bottom p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-test-tube me-2 text-info f-s-18"></i>
                                            <h6 class="mb-0 text-info f-w-600">TESTING</h6>
                                        </div>
                                        <span class="badge bg-info rounded-pill">{{ $data['testing_tasks']->count() }}</span>
                                    </div>
                                </div>
                                <div class="board-column-content-wrapper p-3" style="min-height: 500px;">
                                    <div class="board-column-content">
                                        @foreach($data['testing_tasks'] as $task)
                                        <div class="board-item mb-3" data-task-id="{{ $task->id }}" data-status="testing">
                                            <div class="board-item-content card shadow-sm border-0 hover-effect {{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-0' : '' }}">
                                                @if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0)
                                                    <div class="position-relative">
                                                        @php
                                                            $images = array_filter($task->attachments, fn($a) => $a['type'] === 'image');
                                                            $firstImage = reset($images);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $firstImage['path']) }}" class="img-fluid" alt="{{ $firstImage['original_name'] }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                                        @if(count($images) > 1)
                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                <span class="badge bg-dark">{{ count($images) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="{{ $task->getCategoryIcon() }} f-s-20"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-1 f-w-600">{{ Str::limit($task->title, 35) }}</h6>
                                                            @if($task->description)
                                                            <p class="text-muted mb-2 f-s-12">{{ Str::limit($task->description, 60) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-11">
                                                            <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedTo)
                                                        <span class="badge text-bg-info f-s-11">
                                                            <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                        </span>
                                                        @endif
                                                        @if($task->estimated_hours)
                                                        <span class="badge text-bg-secondary f-s-11">
                                                            <i class="ph ph-clock me-1"></i>{{ $task->getEstimatedTimeFormatted() }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- DONE COLUMN -->
                            <div class="board-column">
                                <div class="board-column-header bg-light border-bottom p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-check-circle me-2 text-success f-s-18"></i>
                                            <h6 class="mb-0 text-success f-w-600">DONE</h6>
                                        </div>
                                        <span class="badge bg-success rounded-pill">{{ $data['done_tasks']->count() }}</span>
                                    </div>
                                </div>
                                <div class="board-column-content-wrapper p-3" style="min-height: 500px;">
                                    <div class="board-column-content">
                                        @foreach($data['done_tasks']->take(10) as $task)
                                        <div class="board-item mb-3" data-task-id="{{ $task->id }}" data-status="done">
                                            <div class="board-item-content card shadow-sm border-0 hover-effect {{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-0' : '' }}">
                                                @if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0)
                                                    <div class="position-relative">
                                                        @php
                                                            $images = array_filter($task->attachments, fn($a) => $a['type'] === 'image');
                                                            $firstImage = reset($images);
                                                        @endphp
                                                        <img src="{{ asset('storage/' . $firstImage['path']) }}" class="img-fluid" alt="{{ $firstImage['original_name'] }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                                        @if(count($images) > 1)
                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                <span class="badge bg-dark">{{ count($images) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="{{ $task->getCategoryIcon() }} f-s-20"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-1 f-w-600">{{ Str::limit($task->title, 35) }}</h6>
                                                            @if($task->description)
                                                            <p class="text-muted mb-2 f-s-12">{{ Str::limit($task->description, 60) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @if($task->assignedTo)
                                                        <span class="badge text-bg-info f-s-11">
                                                            <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                        </span>
                                                        @endif
                                                        @if($task->completed_at)
                                                        <span class="badge text-bg-success f-s-11">
                                                            <i class="ph ph-check me-1"></i>{{ $task->completed_at->format('M d') }}
                                                        </span>
                                                        @endif
                                                        @if($task->actual_hours)
                                                        <span class="badge text-bg-dark f-s-11">
                                                            <i class="ph ph-timer me-1"></i>{{ $task->getActualTimeFormatted() }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Details Overlay (Complete Modal) -->
<div id="taskDetailsOverlay" class="task-overlay" style="display: none;">
    <div class="task-overlay-content">
        <div class="task-overlay-header">
            <h5 class="task-overlay-title">Dettagli Task</h5>
            <button type="button" class="task-overlay-close" onclick="closeTaskOverlay()">
                <i class="ph ph-x"></i>
            </button>
        </div>
        <div class="task-overlay-body" id="taskDetailsContent">
            <!-- Content will be loaded here -->
        </div>
        <div class="task-overlay-footer">
            <button type="button" class="btn btn-secondary" onclick="closeTaskOverlay()">Chiudi</button>
            <button type="button" class="btn btn-success" onclick="approveTask()">
                <i class="ph ph-check me-2"></i>Completa
            </button>
            <button type="button" class="btn btn-warning" onclick="rejectTask()">
                <i class="ph ph-arrow-left me-2"></i>Riporta a TODO
            </button>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Nuovo Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTaskForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="taskTitle" class="form-label">Titolo *</label>
                                <input type="text" class="form-control" id="taskTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="taskPriority" class="form-label">Priorità</label>
                                <select class="form-select" id="taskPriority" name="priority">
                                    <option value="low">Bassa</option>
                                    <option value="medium" selected>Media</option>
                                    <option value="high">Alta</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskCategory" class="form-label">Categoria</label>
                                <select class="form-select" id="taskCategory" name="category">
                                    <option value="frontend">Frontend</option>
                                    <option value="backend">Backend</option>
                                    <option value="database">Database</option>
                                    <option value="design">Design</option>
                                    <option value="testing">Testing</option>
                                    <option value="deployment">Deployment</option>
                                    <option value="documentation">Documentazione</option>
                                    <option value="bug_fix">Bug Fix</option>
                                    <option value="feature">Nuova Feature</option>
                                    <option value="maintenance">Manutenzione</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskStatus" class="form-label">Stato Iniziale</label>
                                <select class="form-select" id="taskStatus" name="status">
                                    <option value="todo" selected>TODO</option>
                                    <option value="in_progress">IN PROGRESS</option>
                                    <option value="review">REVIEW</option>
                                    <option value="testing">TESTING</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskAssignedTo" class="form-label">Assegna a</label>
                                <select class="form-select" id="taskAssignedTo" name="assigned_to">
                                    <option value="">Seleziona utente...</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->getDisplayName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskDueDate" class="form-label">Data Scadenza</label>
                                <input type="date" class="form-control" id="taskDueDate" name="due_date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskEstimatedHours" class="form-label">Ore Stimate</label>
                                <input type="number" class="form-control" id="taskEstimatedHours" name="estimated_hours" min="0" step="0.5">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskProgress" class="form-label">Progresso (%)</label>
                                <input type="number" class="form-control" id="taskProgress" name="progress_percentage" min="0" max="100" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="taskDescription" name="description" rows="4" placeholder="Descrivi il task in dettaglio..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="taskNotes" class="form-label">Note</label>
                        <textarea class="form-control" id="taskNotes" name="notes" rows="3" placeholder="Note aggiuntive..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="taskTags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="taskTags" name="tags" placeholder="tag1, tag2, tag3...">
                    </div>

                    <div class="mb-3">
                        <label for="taskImages" class="form-label">
                            <i class="ph-bold ph-image me-2"></i>Immagini
                        </label>
                        <div class="border-2 border-dashed border-secondary rounded p-3 text-center" style="border-style: dashed;">
                            <input type="file" class="form-control" id="taskImages" name="images[]" multiple accept="image/*" style="display: none;">
                            <div class="upload-area" onclick="document.getElementById('taskImages').click()" style="cursor: pointer;">
                                <i class="ph-bold ph-upload-simple f-s-48 text-muted mb-2"></i>
                                <p class="text-muted mb-2">Clicca per selezionare le immagini</p>
                                <p class="text-muted small">o trascina qui i file</p>
                                <p class="text-muted small">Formati: JPEG, PNG, JPG, GIF, WebP (max 2MB ciascuna)</p>
                            </div>
                            <div id="taskImagePreview" class="mt-3" style="display: none;">
                                <h6>Immagini selezionate:</h6>
                                <div id="taskImageList" class="row g-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>Crea Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
.kanban-board-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0.5rem;
    padding: 1rem;
}

.board {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding: 0.5rem;
    min-height: 600px;
}

.board-column {
    min-width: 320px;
    max-width: 350px;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.board-column:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.board-column-header {
    border-radius: 0.5rem 0.5rem 0 0;
    border-bottom: 2px solid #e9ecef;
    background: #f8f9fa;
}

.board-column-content-wrapper {
    min-height: 500px;
    max-height: 700px;
    overflow-y: auto;
}

.board-column-content {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.board-item {
    cursor: grab;
    transition: all 0.3s ease;
    user-select: none;
}

.board-item:active {
    cursor: grabbing;
}

.board-item:hover {
    transform: translateY(-2px);
}

.board-item.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.board-item-content {
    transition: all 0.3s ease;
    cursor: pointer;
}

.board-item-content:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
}

.board-item-content.drag-over {
    border-color: #28a745;
    background-color: #f8fff9;
}

.hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.f-s-11 { font-size: 0.75rem !important; }
.f-s-12 { font-size: 0.875rem !important; }
.f-s-18 { font-size: 1.125rem !important; }
.f-s-20 { font-size: 1.25rem !important; }
.f-w-600 { font-weight: 600 !important; }

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.gap-1 { gap: 0.25rem !important; }

/* Drag and Drop Styles */
.drag-placeholder {
    background: #e9ecef;
    border: 2px dashed #6c757d;
    border-radius: 8px;
    height: 100px;
    margin: 10px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-style: italic;
}

.board-column.drag-over {
    background-color: #f8fff9;
    border-color: #28a745;
}

/* Custom Overlay Styles */
.task-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.task-overlay-content {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 800px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    animation: slideIn 0.3s ease;
}

.task-overlay-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 0.5rem 0.5rem 0 0;
}

.task-overlay-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.task-overlay-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.task-overlay-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.task-overlay-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
}

.task-overlay-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .task-overlay-content {
        width: 95%;
        margin: 1rem;
    }

    .task-overlay-body {
        padding: 1rem;
    }

    .task-overlay-footer {
        flex-direction: column;
    }

    .task-overlay-footer .btn {
        width: 100%;
    }
}
</style>

<!-- Kanban Board JS -->
<script src="{{ asset('assets/vendor/kanban_board/hammer.min.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/kanban_board/muuri.min.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/kanban_board.js') }}?v={{ time() }}"></script>

<script>
let currentTaskId = null;

// Initialize custom kanban functionality
$(document).ready(function() {
    console.log('Document ready!');
    console.log('jQuery version:', $.fn.jquery);

    // Add click handler for task items with better timing
    $(document).on('click', '.board-item-content', function(e) {
        console.log('Board item content clicked!');

        // Don't trigger if dragging
        if ($(this).closest('.board-item').hasClass('dragging')) {
            console.log('Item is dragging, ignoring click');
            return;
        }

        // Don't trigger if it's a drag operation
        if (e.which !== 1) { // Only left click
            return;
        }

        const $boardItem = $(this).closest('.board-item');
        const taskId = $boardItem.data('task-id');

        console.log('Task content clicked, ID:', taskId);

        if (taskId) {
            currentTaskId = taskId;
            showTaskOverlay(taskId);
        }
    });

    // Muuri is already initialized in kanban_board.js
    // The drag and drop functionality is handled there

    // Overlay event handlers
    $('#taskDetailsOverlay').on('click', function(e) {
        if (e.target === this) {
            closeTaskOverlay();
        }
    });
});

// Show task overlay
function showTaskOverlay(taskId) {
    console.log('Showing task overlay for ID:', taskId);

    // Show loading state
    $('#taskDetailsContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Caricamento...</span>
            </div>
            <p class="mt-2">Caricamento dettagli task...</p>
        </div>
    `);

    // Show overlay
    $('#taskDetailsOverlay').show();

    // Load task details
    loadTaskDetails(taskId);
}

function closeTaskOverlay() {
    $('#taskDetailsOverlay').hide();
    $('#taskDetailsContent').empty();
    currentTaskId = null;
}

function loadTaskDetails(taskId) {
    console.log('Loading task details for ID:', taskId);

    $.ajax({
        url: '{{ route("admin.kanban.task-details") }}',
        method: 'POST',
        data: {
            task_id: taskId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Task details response:', response);
            if (response.success) {
                displayTaskDetails(response.task);
            } else {
                $('#taskDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="ph ph-warning me-2"></i>
                        Errore nel caricamento dei dettagli: ${response.message}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.log('Task details error:', xhr);
            let errorMessage = 'Errore nel caricamento dei dettagli';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            $('#taskDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="ph ph-warning me-2"></i>
                    ${errorMessage}
                </div>
            `);
        }
    });
}

function displayTaskDetails(task) {
    // Preparazione sezione immagini
    let imagesSection = '';
    if (task.attachments && task.attachments.length > 0) {
        const images = task.attachments.filter(att => att.type === 'image');
        if (images.length > 0) {
            imagesSection = `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="ph ph-image me-2"></i>Immagini (${images.length})
                        </h6>
                        <div class="row g-3">
                            ${images.map((image, index) => `
                                <div class="col-md-4 col-lg-3">
                                    <div class="position-relative">
                                        <img src="/storage/${image.path}" 
                                             class="img-fluid rounded shadow-sm" 
                                             alt="${image.original_name}"
                                             style="width: 100%; height: 150px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('/storage/${image.path}', '${image.original_name}')">
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="deleteTaskImage(${task.id}, ${index})"
                                                    title="Elimina immagine">
                                                <i class="ph ph-x"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">${image.original_name}</small>
                                            <small class="text-muted">${formatFileSize(image.size)}</small>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        }
    }

    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="ph ph-info me-2"></i>Informazioni Task
                </h6>
                <p><strong>Titolo:</strong> ${task.title}</p>
                <p><strong>Descrizione:</strong> ${task.description || 'N/A'}</p>
                <p><strong>Categoria:</strong> <span class="badge bg-info">${task.category}</span></p>
                <p><strong>Priorità:</strong> <span class="badge bg-${getPriorityColor(task.priority)}">${task.priority}</span></p>
                <p><strong>Stato:</strong> <span class="badge bg-${getStatusColor(task.status)}">${task.status}</span></p>
                <p><strong>Progresso:</strong> <span class="badge bg-${getProgressColor(task.progress_percentage)}">${task.progress_percentage}%</span></p>
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="ph ph-users me-2"></i>Assegnazione e Date
                </h6>
                <p><strong>Creato da:</strong> ${task.created_by ? task.created_by.name : 'N/A'}</p>
                <p><strong>Assegnato a:</strong> ${task.assigned_to ? task.assigned_to.name : 'Non assegnato'}</p>
                <p><strong>Data creazione:</strong> ${new Date(task.created_at).toLocaleDateString()}</p>
                <p><strong>Data scadenza:</strong> ${task.due_date ? new Date(task.due_date).toLocaleDateString() : 'Non specificata'}</p>
                <p><strong>Iniziato il:</strong> ${task.started_at ? new Date(task.started_at).toLocaleDateString() : 'Non iniziato'}</p>
                <p><strong>Completato il:</strong> ${task.completed_at ? new Date(task.completed_at).toLocaleDateString() : 'Non completato'}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="ph ph-clock me-2"></i>Time Tracking
                </h6>
                <p><strong>Ore stimate:</strong> ${task.estimated_hours || 'Non specificato'}</p>
                <p><strong>Ore effettive:</strong> ${task.actual_hours || 'Non registrato'}</p>
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">
                    <i class="ph ph-note me-2"></i>Note
                </h6>
                <p>${task.notes || 'Nessuna nota'}</p>
            </div>
        </div>
        ${imagesSection}
    `;

    $('#taskDetailsContent').html(content);
}

function getPriorityColor(priority) {
    switch(priority) {
        case 'urgent': return 'danger';
        case 'high': return 'warning';
        case 'medium': return 'info';
        case 'low': return 'success';
        default: return 'secondary';
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'todo': return 'secondary';
        case 'in_progress': return 'primary';
        case 'review': return 'warning';
        case 'testing': return 'info';
        case 'done': return 'success';
        default: return 'secondary';
    }
}

function getProgressColor(progress) {
    if (progress >= 100) return 'success';
    if (progress >= 75) return 'info';
    if (progress >= 50) return 'warning';
    return 'danger';
}

// Funzione per formattare la dimensione del file
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Funzione per aprire il modal dell'immagine
function openImageModal(imageSrc, imageName) {
    const modal = `
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">
                            <i class="ph ph-image me-2"></i>${imageName}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageSrc}" class="img-fluid" alt="${imageName}" style="max-height: 70vh;">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Rimuovi modal esistenti
    $('#imageModal').remove();
    
    // Aggiungi nuovo modal
    $('body').append(modal);
    
    // Mostra modal
    $('#imageModal').modal('show');
    
    // Rimuovi modal quando viene chiuso
    $('#imageModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}

// Funzione per eliminare un'immagine dal task
function deleteTaskImage(taskId, imageIndex) {
    if (confirm('Sei sicuro di voler eliminare questa immagine?')) {
        $.ajax({
            url: '{{ route("admin.kanban.delete-image") }}',
            method: 'POST',
            data: {
                task_id: taskId,
                image_index: imageIndex,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Immagine eliminata con successo!', 'success');
                    // Ricarica i dettagli del task
                    loadTaskDetails(taskId);
                } else {
                    showNotification('Errore nell\'eliminazione: ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Errore nell\'eliminazione dell\'immagine';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification(errorMessage, 'error');
            }
        });
    }
}

function updateTaskStatus(taskId, newStatus) {
    // Show loading indicator
    const taskElement = $(`.board-item[data-task-id="${taskId}"]`);
    const originalContent = taskElement.html();

    taskElement.html(`
        <div class="board-item-content card shadow-sm border-0">
            <div class="card-body p-3 text-center">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Aggiornamento...</span>
                </div>
                <p class="mt-2 mb-0 small text-muted">Aggiornamento stato...</p>
            </div>
        </div>
    `);

    $.ajax({
        url: '{{ route("admin.kanban.update-status") }}',
        method: 'POST',
        data: {
            task_id: taskId,
            new_status: newStatus,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Update the task element with new data
                updateTaskElement(taskElement, response.task);

                // Show success message
                showNotification('Stato aggiornato con successo!', 'success');
            } else {
                // Restore original content on error
                taskElement.html(originalContent);
                showNotification('Errore nell\'aggiornamento dello stato: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            // Restore original content on error
            taskElement.html(originalContent);

            let errorMessage = 'Errore nell\'aggiornamento dello stato';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        }
    });
}

function updateTaskElement(taskElement, task) {
    // Update the task element with new data without page reload
    const newContent = `
        <div class="board-item-content card shadow-sm border-0 hover-effect">
            <div class="card-body p-3">
                <div class="d-flex align-items-start mb-3">
                    <div class="flex-shrink-0">
                        <i class="${getCategoryIcon(task.category)} f-s-20"></i>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <h6 class="mb-1 f-w-600">${task.title.length > 35 ? task.title.substring(0, 35) + '...' : task.title}</h6>
                        ${task.description ? `<p class="text-muted mb-2 f-s-12">${task.description.length > 60 ? task.description.substring(0, 60) + '...' : task.description}</p>` : ''}
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge text-bg-${getPriorityColor(task.priority)} f-s-11">
                        <i class="ph ph-flag me-1"></i>${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}
                    </span>
                    ${task.assigned_to ? `<span class="badge text-bg-info f-s-11">
                        <i class="ph ph-user me-1"></i>${task.assigned_to.name.length > 15 ? task.assigned_to.name.substring(0, 15) + '...' : task.assigned_to.name}
                    </span>` : ''}
                    ${task.due_date ? `<span class="badge text-bg-${isOverdue(task.due_date) ? 'danger' : 'warning'} f-s-11">
                        <i class="ph ph-calendar me-1"></i>${new Date(task.due_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})}
                    </span>` : ''}
                    ${task.progress_percentage > 0 ? `<span class="badge text-bg-${getProgressColor(task.progress_percentage)} f-s-11">
                        <i class="ph ph-percent me-1"></i>${task.progress_percentage}%
                    </span>` : ''}
                </div>
            </div>
        </div>
    `;

    taskElement.html(newContent);
}

function getCategoryIcon(category) {
    const icons = {
        'frontend': 'ph ph-browser',
        'backend': 'ph ph-gear',
        'database': 'ph ph-database',
        'design': 'ph ph-palette',
        'testing': 'ph ph-test-tube',
        'deployment': 'ph ph-rocket',
        'documentation': 'ph ph-file-text',
        'bug_fix': 'ph ph-bug',
        'feature': 'ph ph-star',
        'maintenance': 'ph ph-wrench'
    };
    return icons[category] || 'ph ph-list';
}

function isOverdue(dueDate) {
    return new Date(dueDate) < new Date();
}

function showNotification(message, type) {
    // Create notification element
    const notification = $(`
        <div class="alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="ph ph-${type === 'error' ? 'warning' : 'check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    // Add to body
    $('body').append(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}

function approveTask() {
    if (currentTaskId) {
        updateTaskStatus(currentTaskId, 'done');
        closeTaskOverlay();
    }
}

function rejectTask() {
    if (currentTaskId) {
        updateTaskStatus(currentTaskId, 'todo');
        closeTaskOverlay();
    }
}

// Keyboard event handler for ESC key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape' && $('#taskDetailsOverlay').is(':visible')) {
        closeTaskOverlay();
    }
});

// Image upload preview functionality
function setupImageUpload(inputId, previewId, listId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const list = document.getElementById(listId);
    
    if (input) {
        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                preview.style.display = 'block';
                list.innerHTML = '';
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'col-md-3';
                            div.innerHTML = `
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="img-fluid rounded" alt="${file.name}" style="height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                            onclick="removeImage(${index}, '${inputId}')" style="margin: 2px;">
                                        <i class="ph-bold ph-x"></i>
                                    </button>
                                    <small class="d-block text-muted mt-1">${file.name}</small>
                                </div>
                            `;
                            list.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.style.display = 'none';
            }
        });
    }
}

function setupDragAndDrop(inputId, previewId, listId) {
    const uploadArea = document.querySelector(`#${inputId}`).parentElement;
    
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--primary)';
        uploadArea.style.backgroundColor = 'rgba(var(--primary-rgb), 0.1)';
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--secondary)';
        uploadArea.style.backgroundColor = 'transparent';
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--secondary)';
        uploadArea.style.backgroundColor = 'transparent';
        
        const files = Array.from(e.dataTransfer.files);
        const input = document.getElementById(inputId);
        
        // Create a new FileList-like object
        const dt = new DataTransfer();
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    });
}

// Remove image function
window.removeImage = function(index, inputId) {
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.splice(index, 1);
    files.forEach(file => dt.items.add(file));
    
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
};

// Setup CSRF token per tutte le richieste AJAX
$(document).ready(function() {
    // Setup image upload functionality
    setupImageUpload('taskImages', 'taskImagePreview', 'taskImageList');
    setupDragAndDrop('taskImages', 'taskImagePreview', 'taskImageList');
    
    // Reset form when modal is closed
    $('#addTaskModal').on('hidden.bs.modal', function() {
        $('#addTaskForm')[0].reset();
        $('#taskImagePreview').hide();
        $('#taskImageList').empty();
    });
    
    // Setup CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Test if libraries are loaded
    console.log('Testing Kanban Admin libraries...');
    if (typeof Hammer !== 'undefined') {
        console.log('✅ Hammer.js loaded successfully');
    } else {
        console.log('❌ Hammer.js not loaded');
    }

    if (typeof Muuri !== 'undefined') {
        console.log('✅ Muuri.js loaded successfully');
    } else {
        console.log('❌ Muuri.js not loaded');
    }

    // Add Task Form Handler
    $('#addTaskForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Show loading state
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Creazione in corso...
        `);

        $.ajax({
            url: '{{ route("admin.kanban.store-task") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#addTaskModal').modal('hide');
                    $('#addTaskForm')[0].reset();
                    showNotification('Task creato con successo!', 'success');

                    // Refresh only the specific column where the task was added
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Errore nella creazione del task: ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Errore nella creazione del task';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification(errorMessage, 'error');
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Overlay event handlers
    $('#taskDetailsOverlay').on('click', function(e) {
        if (e.target === this) {
            closeTaskOverlay();
        }
    });
});
</script>
@endsection


