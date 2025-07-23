@extends('layout.master')

@section('title', 'Kanban Board - Admin')

@section('main-content')
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
                                            <div class="board-item-content card shadow-sm border-0 hover-effect">
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
                                            <div class="board-item-content card shadow-sm border-0 hover-effect">
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
                                            <div class="board-item-content card shadow-sm border-0 hover-effect">
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
                                            <div class="board-item-content card shadow-sm border-0 hover-effect">
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
                                            <div class="board-item-content card shadow-sm border-0 hover-effect">
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

<!-- Task Details Modal -->
<div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailsModalLabel">Dettagli Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="taskDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-success" onclick="approveTask()">
                    <i class="ph ph-check me-2"></i>Completa
                </button>
                <button type="button" class="btn btn-warning" onclick="rejectTask()">
                    <i class="ph ph-arrow-left me-2"></i>Riporta a TODO
                </button>
            </div>
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
            <form id="addTaskForm">
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
</style>

<!-- Kanban Board JS -->
<script src="{{ asset('assets/vendor/kanban_board/hammer.min.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/vendor/kanban_board/muuri.min.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/kanban_board.js') }}?v={{ time() }}"></script>

<script>
let currentTaskId = null;

// Initialize custom kanban functionality
$(document).ready(function() {
    // Add click handler for task items
    $('.board-item').on('click', function(e) {
        // Don't trigger click when dragging
        if ($(this).hasClass('dragging')) {
            return;
        }

        const taskId = $(this).data('task-id');
        currentTaskId = taskId;
        loadTaskDetails(taskId);
    });

    // Initialize Muuri drag and drop
    const itemContainers = Array.from(document.querySelectorAll('.board-column-content'));
    const columnGrids = [];
    let boardGrid;

    itemContainers.forEach((container) => {
        const grid = new Muuri(container, {
            items: '.board-item',
            layoutDuration: 400,
            layoutEasing: 'ease',
            dragEnabled: true,
            dragSort: () => columnGrids,
            dragSortInterval: 0,
            dragContainer: document.body,
            dragReleaseDuration: 400,
            dragReleaseEasing: 'ease'
        })
        .on('dragStart', (item) => {
            const el = item.getElement();
            el.style.width = `${item.getWidth()}px`;
            el.style.height = `${item.getHeight()}px`;
            el.classList.add('dragging');
        })
        .on('dragReleaseEnd', (item) => {
            const el = item.getElement();
            el.style.width = '';
            el.style.height = '';
            el.classList.remove('dragging');

            // Get the new column and update task status
            const newColumn = el.closest('.board-column');
            const columnHeader = newColumn.querySelector('.board-column-header h6').textContent.trim();
            let newStatus = 'todo';

            // Map column headers to status values
            if (columnHeader.includes('TODO')) newStatus = 'todo';
            else if (columnHeader.includes('IN PROGRESS')) newStatus = 'in_progress';
            else if (columnHeader.includes('REVIEW')) newStatus = 'review';
            else if (columnHeader.includes('TESTING')) newStatus = 'testing';
            else if (columnHeader.includes('DONE')) newStatus = 'done';

            const taskId = el.dataset.taskId;
            updateTaskStatus(taskId, newStatus);

            columnGrids.forEach((grid) => {
                grid.refreshItems();
            });
        })
        .on('layoutStart', () => {
            if (boardGrid) {
                boardGrid.refreshItems().layout();
            }
        });

        columnGrids.push(grid);
    });

    boardGrid = new Muuri('.board', {
        layout: {
            horizontal: true,
        },
        layoutDuration: 400,
        layoutEasing: 'ease',
        dragEnabled: false, // Disable column dragging
        dragReleaseDuration: 400,
        dragReleaseEasing: 'ease'
    });
});

function loadTaskDetails(taskId) {
    $.ajax({
        url: '{{ route("admin.kanban.task-details") }}',
        method: 'POST',
        data: {
            task_id: taskId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                displayTaskDetails(response.task);
                $('#taskDetailsModal').modal('show');
            }
        },
        error: function() {
            alert('Errore nel caricamento dei dettagli');
        }
    });
}

function displayTaskDetails(task) {
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6>Informazioni Task</h6>
                <p><strong>Titolo:</strong> ${task.title}</p>
                <p><strong>Descrizione:</strong> ${task.description || 'N/A'}</p>
                <p><strong>Categoria:</strong> <span class="badge bg-info">${task.category}</span></p>
                <p><strong>Priorità:</strong> <span class="badge bg-${getPriorityColor(task.priority)}">${task.priority}</span></p>
                <p><strong>Stato:</strong> <span class="badge bg-${getStatusColor(task.status)}">${task.status}</span></p>
                <p><strong>Progresso:</strong> <span class="badge bg-${getProgressColor(task.progress_percentage)}">${task.progress_percentage}%</span></p>
            </div>
            <div class="col-md-6">
                <h6>Assegnazione e Date</h6>
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
                <h6>Time Tracking</h6>
                <p><strong>Ore stimate:</strong> ${task.estimated_hours || 'Non specificato'}</p>
                <p><strong>Ore effettive:</strong> ${task.actual_hours || 'Non registrato'}</p>
            </div>
            <div class="col-md-6">
                <h6>Note</h6>
                <p>${task.notes || 'Nessuna nota'}</p>
            </div>
        </div>
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

function updateTaskStatus(taskId, newStatus) {
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
                // Refresh the page to show updated data
                location.reload();
            }
        },
        error: function() {
            alert('Errore nell\'aggiornamento dello stato');
        }
    });
}

function approveTask() {
    if (currentTaskId) {
        updateTaskStatus(currentTaskId, 'done');
        $('#taskDetailsModal').modal('hide');
    }
}

function rejectTask() {
    if (currentTaskId) {
        updateTaskStatus(currentTaskId, 'todo');
        $('#taskDetailsModal').modal('hide');
    }
}

// Setup CSRF token per tutte le richieste AJAX
$(document).ready(function() {
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
                    location.reload();
                } else {
                    alert('Errore nella creazione del task: ' + response.message);
                }
            },
            error: function() {
                alert('Errore nella creazione del task');
            }
        });
    });
});
</script>
@endsection
