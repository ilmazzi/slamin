@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kanban Board</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ph ph-kanban me-2"></i>Gestione Task di Sviluppo
                </h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
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
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-play-circle text-info f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Corso</h6>
                            <h4 class="mb-0">{{ $stats['in_progress_tasks'] }}</h4>
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
                            <i class="ph ph-eye text-warning f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Review</h6>
                            <h4 class="mb-0">{{ $stats['review_tasks'] }}</h4>
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
                    <div class="kanban-board-container app-scroll">
                        <div class="board">
                            <!-- TODO COLUMN -->
                            <div class="board-column app-scroll" data-status="todo">
                                <div class="board-column-header">
                                    <i class="ph-fill ph-list-bullets me-2 f-s-16"></i> TODO
                                    <span class="badge bg-secondary ms-2">{{ $data['todo_tasks']->count() }}</span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        @foreach($data['todo_tasks'] as $task)
                                        <div class="board-item" data-task-id="{{ $task->id }}" data-status="todo">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">{{ Str::limit($task->title, 35) }}</h6>
                                                @if($task->description)
                                                <p class="text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                                @endif
                                                <div class="board-footer">
                                                    <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-14">
                                                        <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedTo)
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                    </span>
                                                    @endif
                                                    @if($task->due_date)
                                                    <span class="badge text-bg-{{ $task->isOverdue() ? 'danger' : 'warning' }} f-s-14 ms-2">
                                                        <i class="ph ph-calendar me-1"></i>{{ $task->due_date->format('M d') }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- IN PROGRESS COLUMN -->
                            <div class="board-column app-scroll" data-status="in_progress">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-chart-line-up me-2 f-s-16"></i> IN PROGRESS
                                    <span class="badge bg-primary ms-2">{{ $data['in_progress_tasks']->count() }}</span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        @foreach($data['in_progress_tasks'] as $task)
                                        <div class="board-item" data-task-id="{{ $task->id }}" data-status="in_progress">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">{{ Str::limit($task->title, 35) }}</h6>
                                                @if($task->description)
                                                <p class="text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                                @endif
                                                <div class="board-footer">
                                                    <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-14">
                                                        <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedTo)
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                    </span>
                                                    @endif
                                                    @if($task->progress_percentage > 0)
                                                    <span class="badge text-bg-{{ $task->getProgressBarColor() }} f-s-14 ms-2">
                                                        <i class="ph ph-percent me-1"></i>{{ $task->progress_percentage }}%
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- REVIEW COLUMN -->
                            <div class="board-column app-scroll" data-status="review">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-eye me-2 f-s-16"></i> REVIEW
                                    <span class="badge bg-warning ms-2">{{ $data['review_tasks']->count() }}</span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        @foreach($data['review_tasks'] as $task)
                                        <div class="board-item" data-task-id="{{ $task->id }}" data-status="review">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">{{ Str::limit($task->title, 35) }}</h6>
                                                @if($task->description)
                                                <p class="text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                                @endif
                                                <div class="board-footer">
                                                    <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-14">
                                                        <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedTo)
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                    </span>
                                                    @endif
                                                    @if($task->reviewedBy)
                                                    <span class="badge text-bg-success f-s-14 ms-2">
                                                        <i class="ph ph-check me-1"></i>{{ Str::limit($task->reviewedBy->getDisplayName(), 15) }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- TESTING COLUMN -->
                            <div class="board-column app-scroll" data-status="testing">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-check-square-offset me-2 f-s-16"></i> TESTING
                                    <span class="badge bg-info ms-2">{{ $data['testing_tasks']->count() }}</span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        @foreach($data['testing_tasks'] as $task)
                                        <div class="board-item" data-task-id="{{ $task->id }}" data-status="testing">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">{{ Str::limit($task->title, 35) }}</h6>
                                                @if($task->description)
                                                <p class="text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                                @endif
                                                <div class="board-footer">
                                                    <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-14">
                                                        <i class="ph ph-flag me-1"></i>{{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedTo)
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                    </span>
                                                    @endif
                                                    @if($task->estimated_hours)
                                                    <span class="badge text-bg-secondary f-s-14 ms-2">
                                                        <i class="ph ph-clock me-1"></i>{{ $task->getEstimatedTimeFormatted() }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- DONE COLUMN -->
                            <div class="board-column app-scroll" data-status="done">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-check-circle me-2 f-s-16"></i> DONE
                                    <span class="badge bg-success ms-2">{{ $data['done_tasks']->count() }}</span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        @foreach($data['done_tasks']->take(10) as $task)
                                        <div class="board-item" data-task-id="{{ $task->id }}" data-status="done">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">{{ Str::limit($task->title, 35) }}</h6>
                                                @if($task->description)
                                                <p class="text-muted mb-2">{{ Str::limit($task->description, 60) }}</p>
                                                @endif
                                                <div class="board-footer">
                                                    @if($task->assignedTo)
                                                    <span class="badge text-bg-info f-s-14">
                                                        <i class="ph ph-user me-1"></i>{{ Str::limit($task->assignedTo->getDisplayName(), 15) }}
                                                    </span>
                                                    @endif
                                                    @if($task->completed_at)
                                                    <span class="badge text-bg-success f-s-14 ms-2">
                                                        <i class="ph ph-check me-1"></i>{{ $task->completed_at->format('M d') }}
                                                    </span>
                                                    @endif
                                                    @if($task->actual_hours)
                                                    <span class="badge text-bg-dark f-s-14 ms-2">
                                                        <i class="ph ph-timer me-1"></i>{{ $task->getActualTimeFormatted() }}
                                                    </span>
                                                    @endif
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
@endsection

@push('scripts')
<!-- Kanban Board JS -->
<script src="{{ asset('assets/vendor/kanban_board/hammer.min.js') }}"></script>
<script src="{{ asset('assets/vendor/kanban_board/muuri.min.js') }}"></script>
<script src="{{ asset('assets/js/kanban_board.js') }}"></script>

<script>
// Simple notification function
function showNotification(message, type = 'info') {
    Swal.fire({
        title: type === 'success' ? 'Successo!' : 'Errore!',
        text: message,
        icon: type,
        timer: 3000,
        showConfirmButton: false
    });
}
</script>
@endpush
