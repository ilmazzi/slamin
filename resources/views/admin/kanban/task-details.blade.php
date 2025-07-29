<div class="task-details-grid">
    <!-- Main Task Info -->
    <div class="task-main-info">
        <h4 class="mb-3">{{ $task->title }}</h4>

        @if($task->description)
        <div class="mb-4">
            <h6>Descrizione</h6>
            <p class="text-muted">{{ $task->description }}</p>
        </div>
        @endif

        @if($task->notes)
        <div class="mb-4">
            <h6>Note</h6>
            <p class="text-muted">{{ $task->notes }}</p>
        </div>
        @endif

        <!-- Attachments Section -->
        @if($task->attachments && count($task->attachments) > 0)
        <div class="mb-4">
            <h6>Allegati</h6>
            <div class="row g-2">
                @foreach($task->attachments as $index => $attachment)
                    @if($attachment['type'] === 'image')
                    <div class="col-md-4 col-sm-6">
                        <div class="position-relative">
                            <img src="{{ Storage::url($attachment['path']) }}"
                                 alt="{{ $attachment['original_name'] }}"
                                 class="img-fluid rounded border"
                                 style="max-height: 150px; object-fit: cover; width: 100%;">
                            <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                    onclick="deleteImage({{ $task->id }}, {{ $index }})"
                                    title="Elimina immagine">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">{{ $attachment['original_name'] }}</small>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Comments Section -->
        <div class="task-comments">
            <h6 class="mb-3">{{ __('common.comments_section') }}</h6>

            @if($task->comments->count() > 0)
                @foreach($task->comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author">{{ $comment->user->name ?? '{{ __('permissions.user') }}' }}</span>
                        <span class="comment-date">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="comment-content">{{ $comment->content }}</div>
                </div>
                @endforeach
            @else
                <p class="text-muted">Nessun commento ancora.</p>
            @endif

            <!-- Add Comment Form -->
            <div class="mt-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="commentText" placeholder="Aggiungi un commento...">
                    <button class="btn btn-primary" type="button" onclick="addComment({{ $task->id }})">
                        <i class="ph ph-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Sidebar -->
    <div class="task-sidebar">
        <!-- Status & Priority -->
        <div class="task-sidebar-section">
            <h6>Status & Priorità</h6>
            <div class="mb-2">
                <span class="badge text-bg-{{ $task->getStatusColor() }}">{{ ucfirst($task->status) }}</span>
            </div>
            <div class="mb-2">
                <span class="badge text-bg-{{ $task->getPriorityColor() }}">{{ ucfirst($task->priority) }}</span>
            </div>
            @if($task->category)
            <div>
                <span class="badge text-bg-secondary">{{ ucfirst($task->category) }}</span>
            </div>
            @endif
        </div>

        <!-- Assignment -->
        <div class="task-sidebar-section">
            <h6>Assegnazione</h6>
            @if($task->assignedTo)
                <p class="mb-1"><strong>Assegnato a:</strong></p>
                <p class="text-muted">{{ $task->assignedTo->name }}</p>
            @else
                <p class="text-muted">Non assegnato</p>
            @endif

            <p class="mb-1"><strong>Creato da:</strong></p>
            <p class="text-muted">{{ $task->createdBy->name ?? 'N/A' }}</p>
        </div>

        <!-- Dates -->
        <div class="task-sidebar-section">
            <h6>Date</h6>
            <p class="mb-1"><strong>Creato:</strong></p>
            <p class="text-muted">{{ $task->created_at->format('d/m/Y H:i') }}</p>

            @if($task->due_date)
            <p class="mb-1"><strong>Scadenza:</strong></p>
            <p class="text-muted {{ $task->isOverdue() ? 'text-danger' : '' }}">
                {{ $task->due_date->format('d/m/Y') }}
                @if($task->isOverdue())
                    <i class="ph ph-warning ms-1"></i>
                @endif
            </p>
            @endif

            @if($task->started_at)
            <p class="mb-1"><strong>Iniziato:</strong></p>
            <p class="text-muted">{{ $task->started_at->format('d/m/Y H:i') }}</p>
            @endif

            @if($task->completed_at)
            <p class="mb-1"><strong>Completato:</strong></p>
            <p class="text-muted">{{ $task->completed_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>

        <!-- Time Tracking -->
        <div class="task-sidebar-section">
            <h6>Time Tracking</h6>
            @if($task->estimated_hours)
            <p class="mb-1"><strong>Ore stimate:</strong></p>
            <p class="text-muted">{{ $task->estimated_hours }}h</p>
            @endif

            @if($task->actual_hours)
            <p class="mb-1"><strong>Ore effettive:</strong></p>
            <p class="text-muted">{{ $task->actual_hours }}h</p>
            @endif

            @if($task->progress_percentage > 0)
            <p class="mb-1"><strong>Progresso:</strong></p>
            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar bg-{{ $task->getProgressBarColor() }}"
                     style="width: {{ $task->progress_percentage }}%"></div>
            </div>
            <p class="text-muted">{{ $task->progress_percentage }}%</p>
            @endif
        </div>

        <!-- Actions -->
        <div class="task-sidebar-section">
            <h6>{{ __('invitations.actions') }}</h6>
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="editTask({{ $task->id }})">
                    <i class="ph ph-pencil me-2"></i>{{ __('permissions.modify') }}
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteTask({{ $task->id }})">
                    <i class="ph ph-trash me-2"></i>Elimina
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Delete image function
function deleteImage(taskId, imageIndex) {
    Swal.fire({
        title: 'Elimina immagine?',
        text: "Questa azione non può essere annullata!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/admin/kanban/delete-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    task_id: taskId,
                    image_index: imageIndex
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Immagine eliminata con successo', 'success');
                    // Refresh task details
                    openTaskDetails(taskId);
                } else {
                    showNotification('Errore nell\'eliminazione dell\'immagine', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Errore nell\'eliminazione dell\'immagine', 'error');
            });
        }
    });
}

// Delete task function
function deleteTask(taskId) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Questa azione non può essere annullata!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/admin/kanban/task/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    task_id: taskId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Task eliminato con successo', 'success');
                    closeTaskOverlay();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Errore nell\'eliminazione del task', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Errore nell\'eliminazione del task', 'error');
            });
        }
    });
}
</script>
