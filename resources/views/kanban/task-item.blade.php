<div class="board-item" data-task-id="{{ $task->id }}">
    <div class="board-item-content {{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-0' : '' }}">
        @if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0)
            <div class="board-images">
                @php
                    $images = array_filter($task->attachments, fn($a) => $a['type'] === 'image');
                    $firstImage = reset($images);
                @endphp
                <img src="{{ asset('storage/' . $firstImage['path']) }}" class="img-fluid" alt="{{ $firstImage['original_name'] }}" style="width: 100%; height: 150px; object-fit: cover;">
                @if(count($images) > 1)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark">{{ count($images) }}</span>
                    </div>
                @endif
            </div>
        @endif
        
        <div class="{{ $task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0 ? 'p-3' : '' }}">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-0 flex-grow-1" style="cursor: pointer;" onclick="loadTaskForEdit({{ $task->id }})">
                    {{ $task->title }}
                </h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                        <i class="ph-bold ph-dots-three-outline"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="loadTaskForEdit({{ $task->id }})">
                            <i class="ph-bold ph-pencil me-2"></i>Modifica
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask({{ $task->id }})">
                            <i class="ph-bold ph-trash me-2"></i>Elimina
                        </a></li>
                    </ul>
                </div>
            </div>

            @if($task->description)
                <p class="text-muted small mb-2" style="font-size: 12px; line-height: 1.3;">
                    {{ Str::limit($task->description, 80) }}
                </p>
            @endif

            <div class="board-footer">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge text-bg-{{ $task->getPriorityColor() }} f-s-12">
                        <i class="ph-bold ph-{{ $task->priority === 'urgent' ? 'warning' : 'circle' }}"></i>
                        {{ ucfirst($task->priority) }}
                    </span>
                    
                    @if($task->due_date)
                        <span class="badge text-bg-{{ $task->isOverdue() ? 'danger' : ($task->isDueToday() ? 'warning' : 'secondary') }} f-s-12">
                            <i class="ph-bold ph-clock-afternoon"></i>
                            {{ $task->due_date->format('M d') }}
                        </span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($task->assignedTo)
                            <div class="d-flex align-items-center me-2">
                                @if($task->assignedTo->profile_photo)
                                    <img src="{{ asset('storage/' . $task->assignedTo->profile_photo) }}" 
                                         class="rounded-circle" width="20" height="20" 
                                         alt="{{ $task->assignedTo->name }}">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                         style="width: 20px; height: 20px;">
                                        <span class="text-white f-s-10">{{ substr($task->assignedTo->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <span class="badge text-bg-light f-s-12">
                            <i class="ph-bold {{ $task->getCategoryIcon() }}"></i>
                            {{ ucfirst($task->category) }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center">
                        @if($task->comments_count > 0)
                            <span class="f-s-12 me-2">
                                <i class="ph-bold ph-chat-text"></i>
                                <span>{{ $task->comments_count }}</span>
                            </span>
                        @endif

                        @if($task->progress_percentage > 0)
                            <span class="badge text-bg-{{ $task->getProgressBarColor() }} f-s-12">
                                <i class="ph-bold ph-check-square-offset"></i> 
                                {{ $task->progress_percentage }}%
                            </span>
                        @endif
                    </div>
                </div>

                @if($task->estimated_hours)
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="ph-bold ph-clock"></i>
                            {{ $task->getEstimatedTimeFormatted() }}
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function deleteTask(taskId) {
    if (confirm('Sei sicuro di voler eliminare questo task?')) {
        fetch(`/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore: ' + (data.message || 'Errore sconosciuto'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante l\'eliminazione del task');
        });
    }
}
</script> 