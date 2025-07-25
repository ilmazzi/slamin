@extends('layout.master')

@section('title', 'Kanban Board')
@section('css')
<!-- Force cache refresh -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endsection
@section('main-content')
    <div class="container-fluid">

        <div class="row m-1">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="main-title">Kanban Board</h4>
                        <ul class="app-line-breadcrumbs mb-3">
                            <li class="">
                                <a href="#" class="f-s-14 f-w-500">
                                    <span>
                                        <i class="ph-duotone ph-stack f-s-16"></i> Apps
                                    </span>
                                </a>
                            </li>
                            <li class="active">
                                <a href="#" class="f-s-14 f-w-500">Kanban Board</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="ph-bold ph-plus me-2"></i>Nuovo Task
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanban Board start -->
        <div class="row">
            <div class="col-12">
                <div class="kanban-board-container app-scroll">
                    <div class="board">
                        <!-- TO DO Column -->
                        <div class="board-column app-scroll" data-status="todo">
                            <div class="board-column-header">
                                <i class="ph-fill ph-list-bullets me-2 f-s-16"></i> To Do
                                <span class="badge bg-secondary ms-2">{{ $tasksByStatus['todo']->count() }}</span>
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($tasksByStatus['todo'] as $task)
                                        @include('kanban.task-item', ['task' => $task])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- IN PROGRESS Column -->
                        <div class="board-column app-scroll" data-status="in_progress">
                            <div class="board-column-header">
                                <i class="ph-bold ph-chart-line-up me-2 f-s-16"></i> IN PROGRESS
                                <span class="badge bg-primary ms-2">{{ $tasksByStatus['in_progress']->count() }}</span>
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($tasksByStatus['in_progress'] as $task)
                                        @include('kanban.task-item', ['task' => $task])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- REVIEW Column -->
                        <div class="board-column app-scroll" data-status="review">
                            <div class="board-column-header">
                                <i class="ph-bold ph-eye me-2 f-s-16"></i> REVIEW
                                <span class="badge bg-warning ms-2">{{ $tasksByStatus['review']->count() }}</span>
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($tasksByStatus['review'] as $task)
                                        @include('kanban.task-item', ['task' => $task])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- TESTING Column -->
                        <div class="board-column app-scroll" data-status="testing">
                            <div class="board-column-header">
                                <i class="ph-bold ph-test-tube me-2 f-s-16"></i> TESTING
                                <span class="badge bg-info ms-2">{{ $tasksByStatus['testing']->count() }}</span>
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($tasksByStatus['testing'] as $task)
                                        @include('kanban.task-item', ['task' => $task])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- DONE Column -->
                        <div class="board-column app-scroll" data-status="done">
                            <div class="board-column-header">
                                <i class="ph-bold ph-check-square-offset me-2 f-s-16"></i> DONE
                                <span class="badge bg-success ms-2">{{ $tasksByStatus['done']->count() }}</span>
                            </div>
                            <div class="board-column-content-wrapper">
                                <div class="board-column-content">
                                    @foreach($tasksByStatus['done'] as $task)
                                        @include('kanban.task-item', ['task' => $task])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kanban Board end -->
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">
                        <i class="ph-bold ph-plus-circle me-2"></i>Nuovo Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addTaskForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titolo *</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priorità *</label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low">Bassa</option>
                                        <option value="medium" selected>Media</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Categoria *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="frontend">Frontend</option>
                                        <option value="backend">Backend</option>
                                        <option value="database">Database</option>
                                        <option value="design">Design</option>
                                        <option value="testing">Testing</option>
                                        <option value="deployment">Deployment</option>
                                        <option value="documentation">Documentazione</option>
                                        <option value="bug_fix">Bug Fix</option>
                                        <option value="feature" selected>Feature</option>
                                        <option value="maintenance">Manutenzione</option>
                                        <option value="optimization">Ottimizzazione</option>
                                        <option value="security">Sicurezza</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Assegnato a</label>
                                    <select class="form-select" id="assigned_to" name="assigned_to">
                                        <option value="">Nessuno</option>
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Scadenza</label>
                                    <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estimated_hours" class="form-label">Ore stimate</label>
                                    <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" step="0.5" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">
                                <i class="ph-bold ph-image me-2"></i>Immagini
                            </label>
                            <div class="border-2 border-dashed border-secondary rounded p-3 text-center" style="border-style: dashed;">
                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" style="display: none;">
                                <div class="upload-area" onclick="document.getElementById('images').click()" style="cursor: pointer;">
                                    <i class="ph-bold ph-upload-simple f-s-48 text-muted mb-2"></i>
                                    <p class="text-muted mb-2">Clicca per selezionare le immagini</p>
                                    <p class="text-muted small">o trascina qui i file</p>
                                    <p class="text-muted small">Formati: JPEG, PNG, JPG, GIF, WebP (max 2MB ciascuna)</p>
                                </div>
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <h6>Immagini selezionate:</h6>
                                    <div id="imageList" class="row g-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-bold ph-check me-2"></i>Salva Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">
                        <i class="ph-bold ph-pencil me-2"></i>Modifica Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTaskForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_task_id" name="task_id">
                    <div class="modal-body">
                        <!-- Stesso contenuto del form di aggiunta -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="edit_title" class="form-label">Titolo *</label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_priority" class="form-label">Priorità *</label>
                                    <select class="form-select" id="edit_priority" name="priority" required>
                                        <option value="low">Bassa</option>
                                        <option value="medium">Media</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_category" class="form-label">Categoria *</label>
                                    <select class="form-select" id="edit_category" name="category" required>
                                        <option value="frontend">Frontend</option>
                                        <option value="backend">Backend</option>
                                        <option value="database">Database</option>
                                        <option value="design">Design</option>
                                        <option value="testing">Testing</option>
                                        <option value="deployment">Deployment</option>
                                        <option value="documentation">Documentazione</option>
                                        <option value="bug_fix">Bug Fix</option>
                                        <option value="feature">Feature</option>
                                        <option value="maintenance">Manutenzione</option>
                                        <option value="optimization">Ottimizzazione</option>
                                        <option value="security">Sicurezza</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_assigned_to" class="form-label">Assegnato a</label>
                                    <select class="form-select" id="edit_assigned_to" name="assigned_to">
                                        <option value="">Nessuno</option>
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_due_date" class="form-label">Scadenza</label>
                                    <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_estimated_hours" class="form-label">Ore stimate</label>
                                    <input type="number" class="form-control" id="edit_estimated_hours" name="estimated_hours" step="0.5" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_progress_percentage" class="form-label">Progresso (%)</label>
                                    <input type="number" class="form-control" id="edit_progress_percentage" name="progress_percentage" min="0" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_images" class="form-label">
                                <i class="ph-bold ph-image me-2"></i>Aggiungi immagini
                            </label>
                            <div class="border-2 border-dashed border-secondary rounded p-3 text-center" style="border-style: dashed;">
                                <input type="file" class="form-control" id="edit_images" name="images[]" multiple accept="image/*" style="display: none;">
                                <div class="upload-area-edit" onclick="document.getElementById('edit_images').click()" style="cursor: pointer;">
                                    <i class="ph-bold ph-upload-simple f-s-48 text-muted mb-2"></i>
                                    <p class="text-muted mb-2">Clicca per selezionare le immagini</p>
                                    <p class="text-muted small">o trascina qui i file</p>
                                    <p class="text-muted small">Formati: JPEG, PNG, JPG, GIF, WebP (max 2MB ciascuna)</p>
                                </div>
                                <div id="editImagePreview" class="mt-3" style="display: none;">
                                    <h6>Nuove immagini selezionate:</h6>
                                    <div id="editImageList" class="row g-2"></div>
                                </div>
                            </div>
                        </div>

                        <div id="currentImages" class="mb-3">
                            <label class="form-label">Immagini attuali</label>
                            <div id="imagesContainer" class="row g-2">
                                <!-- Le immagini esistenti verranno caricate qui -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger me-auto" id="deleteTaskBtn">
                            <i class="ph-bold ph-trash me-2"></i>Elimina Task
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-bold ph-check me-2"></i>Salva Modifiche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<!-- kanban_board hammer js-->
<script src="{{ asset('assets/vendor/kanban_board/hammer.min.js') }}?v={{ time() }}"></script>

<!-- kanban_board muuri js-->
<script src="{{ asset('assets/vendor/kanban_board/muuri.min.js') }}?v={{ time() }}"></script>

<!-- kanban_board js-->
<script src="{{ asset('assets/js/kanban_board.js') }}?v={{ time() }}"></script>

<script>
// Task management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Image upload preview functionality
    setupImageUpload('images', 'imagePreview', 'imageList');
    setupImageUpload('edit_images', 'editImagePreview', 'editImageList');
    
    // Drag and drop functionality
    setupDragAndDrop('images', 'imagePreview', 'imageList');
    setupDragAndDrop('edit_images', 'editImagePreview', 'editImageList');
    
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
    // Add Task Form
    const addTaskForm = document.getElementById('addTaskForm');
    if (addTaskForm) {
        addTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("tasks.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Chiudi modal e ricarica la pagina
                    bootstrap.Modal.getInstance(document.getElementById('addTaskModal')).hide();
                    location.reload();
                } else {
                    alert('Errore: ' + (data.message || 'Errore sconosciuto'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore durante il salvataggio del task');
            });
        });
    }

    // Edit Task Form
    const editTaskForm = document.getElementById('editTaskForm');
    if (editTaskForm) {
        editTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const taskId = document.getElementById('edit_task_id').value;
            const formData = new FormData(this);
            
            fetch(`/tasks/${taskId}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
                    location.reload();
                } else {
                    alert('Errore: ' + (data.message || 'Errore sconosciuto'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore durante l\'aggiornamento del task');
            });
        });
    }

    // Delete Task
    const deleteTaskBtn = document.getElementById('deleteTaskBtn');
    if (deleteTaskBtn) {
        deleteTaskBtn.addEventListener('click', function() {
            if (confirm('Sei sicuro di voler eliminare questo task?')) {
                const taskId = document.getElementById('edit_task_id').value;
                
                fetch(`/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
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
        });
    }

    // Delete Image
    window.deleteImage = function(taskId, imageIndex) {
        if (confirm('Sei sicuro di voler eliminare questa immagine?')) {
            fetch(`/tasks/${taskId}/image`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image_index: imageIndex })
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
                alert('Errore durante l\'eliminazione dell\'immagine');
            });
        }
    };

    // Reset form when modal is closed
    const addTaskModal = document.getElementById('addTaskModal');
    if (addTaskModal) {
        addTaskModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('addTaskForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('imageList').innerHTML = '';
        });
    }
    
    const editTaskModal = document.getElementById('editTaskModal');
    if (editTaskModal) {
        editTaskModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('editTaskForm').reset();
            document.getElementById('editImagePreview').style.display = 'none';
            document.getElementById('editImageList').innerHTML = '';
            document.getElementById('imagesContainer').innerHTML = '';
        });
    }
    
    // Load task for editing
    window.loadTaskForEdit = function(taskId) {
        fetch(`/tasks/${taskId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const task = data.task;
                
                // Popola i campi del form
                document.getElementById('edit_task_id').value = task.id;
                document.getElementById('edit_title').value = task.title;
                document.getElementById('edit_description').value = task.description || '';
                document.getElementById('edit_priority').value = task.priority;
                document.getElementById('edit_category').value = task.category;
                document.getElementById('edit_assigned_to').value = task.assigned_to || '';
                document.getElementById('edit_estimated_hours').value = task.estimated_hours || '';
                document.getElementById('edit_progress_percentage').value = task.progress_percentage || 0;
                
                if (task.due_date) {
                    const dueDate = new Date(task.due_date);
                    document.getElementById('edit_due_date').value = dueDate.toISOString().slice(0, 16);
                }

                // Carica le immagini esistenti
                const imagesContainer = document.getElementById('imagesContainer');
                imagesContainer.innerHTML = '';
                
                if (task.attachments && task.attachments.length > 0) {
                    task.attachments.forEach((attachment, index) => {
                        if (attachment.type === 'image') {
                            const imageDiv = document.createElement('div');
                            imageDiv.className = 'col-md-3';
                            imageDiv.innerHTML = `
                                <div class="position-relative">
                                    <img src="/storage/${attachment.path}" class="img-fluid rounded" alt="${attachment.original_name}" style="height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                            onclick="deleteImage(${task.id}, ${index})" style="margin: 2px;">
                                        <i class="ph-bold ph-x"></i>
                                    </button>
                                </div>
                            `;
                            imagesContainer.appendChild(imageDiv);
                        }
                    });
                } else {
                    imagesContainer.innerHTML = '<div class="col-12"><p class="text-muted">Nessuna immagine caricata</p></div>';
                }

                // Mostra il modal
                const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                editModal.show();
            } else {
                alert('Errore nel caricamento del task');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante il caricamento del task');
        });
    };
});
</script>
@endsection
