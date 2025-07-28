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
            <div class="">
                <div class=" kanban-board-container app-scroll ">
                    <div class="board">
                        <div class="board-column app-scroll" data-status="todo">
                            <div class="board-column-header">
                                <i class="ph-fill  ph-list-bullets me-2 f-s-16"></i> To Do
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($data['todo_tasks'] as $task)
                                    <div class="board-item">
                                        <div class="board-item-content">
                                            <h6 class="mb-0">{{ $task->title }}</h6>
                                            <div class="board-footer">
                                                <span class="badge text-bg-danger f-s-14">
                                                    <i class="ph-bold  ph-clock-afternoon"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'No date' }}
                                                </span>
                                                <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                <span class="f-s-14 me-2">
                                                    <i class="ph-bold  ph-chat-text"></i>
                                                    <span>{{ $task->comments_count ?? 0 }}</span>
                                                </span>
                                                <span class="badge text-bg-primary f-s-14">
                                                    <i class="ph-bold  ph-check-square-offset"></i> {{ $task->completed_subtasks ?? 0 }}/{{ $task->total_subtasks ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="board-column app-scroll" data-status="in_progress">
                            <div class="board-column-header">
                                <i class="ph-bold  ph-chart-line-up me-2 f-s-16"></i> IN PROGRESS
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($data['in_progress_tasks'] as $task)
                                    <div class="board-item">
                                        <div class="board-item-content">
                                            <h6 class="mb-0">{{ $task->title }}</h6>
                                            <div class="board-footer">
                                                <span class="badge text-bg-danger f-s-14">
                                                    <i class="ph-bold  ph-clock-afternoon"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'No date' }}
                                                </span>
                                                <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                <span class="f-s-14 me-2">
                                                    <i class="ph-bold  ph-chat-text"></i>
                                                    <span>{{ $task->comments_count ?? 0 }}</span>
                                                </span>
                                                <span class="badge text-bg-primary f-s-14">
                                                    <i class="ph-bold  ph-check-square-offset"></i> {{ $task->completed_subtasks ?? 0 }}/{{ $task->total_subtasks ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="board-column app-scroll" data-status="review">
                            <div class="board-column-header">
                                <i class="ph-bold  ph-eye me-2 f-s-16"></i> REVIEW
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($data['review_tasks'] as $task)
                                    <div class="board-item">
                                        <div class="board-item-content">
                                            <h6 class="mb-0">{{ $task->title }}</h6>
                                            <div class="board-footer">
                                                <span class="badge text-bg-danger f-s-14">
                                                    <i class="ph-bold  ph-clock-afternoon"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'No date' }}
                                                </span>
                                                <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                <span class="f-s-14 me-2">
                                                    <i class="ph-bold  ph-chat-text"></i>
                                                    <span>{{ $task->comments_count ?? 0 }}</span>
                                                </span>
                                                <span class="badge text-bg-primary f-s-14">
                                                    <i class="ph-bold  ph-check-square-offset"></i> {{ $task->completed_subtasks ?? 0 }}/{{ $task->total_subtasks ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="board-column app-scroll" data-status="done">
                            <div class="board-column-header">
                                <i class="ph-bold ph-check-square-offset me-2 f-s-16"></i> DONE
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($data['done_tasks'] as $task)
                                    <div class="board-item">
                                        <div class="board-item-content">
                                            <h6 class="mb-0">{{ $task->title }}</h6>
                                            <div class="board-footer">
                                                <span class="badge text-bg-danger f-s-14">
                                                    <i class="ph-bold  ph-clock-afternoon"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'No date' }}
                                                </span>
                                                <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                <span class="f-s-14 me-2">
                                                    <i class="ph-bold  ph-chat-text"></i>
                                                    <span>{{ $task->comments_count ?? 0 }}</span>
                                                </span>
                                                <span class="badge text-bg-primary f-s-14">
                                                    <i class="ph-bold  ph-check-square-offset"></i> {{ $task->completed_subtasks ?? 0 }}/{{ $task->total_subtasks ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="board-column app-scroll" data-status="tested">
                            <div class="board-column-header">
                                <i class="ph-bold  ph-check-circle me-2 f-s-16"></i> TESTED
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($data['tested_tasks'] ?? [] as $task)
                                    <div class="board-item">
                                        <div class="board-item-content">
                                            <h6 class="mb-0">{{ $task->title }}</h6>
                                            <div class="board-footer">
                                                <span class="badge text-bg-danger f-s-14">
                                                    <i class="ph-bold  ph-clock-afternoon"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'No date' }}
                                                </span>
                                                <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                <span class="f-s-14 me-2">
                                                    <i class="ph-bold  ph-chat-text"></i>
                                                    <span>{{ $task->comments_count ?? 0 }}</span>
                                                </span>
                                                <span class="badge text-bg-primary f-s-14">
                                                    <i class="ph-bold  ph-check-square-offset"></i> {{ $task->completed_subtasks ?? 0 }}/{{ $task->total_subtasks ?? 0 }}
                                                </span>
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
