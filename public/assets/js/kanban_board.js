const itemContainers = Array.from(document.querySelectorAll('.board-column-content'));
const columnGrids = [];
let boardGrid;

itemContainers.forEach((container) => {
    // **------Instantiate column grid.**
    const grid = new Muuri(container, {
        items: '.board-item',
        layoutDuration: 400,
        layoutEasing: 'ease',
        dragEnabled: true,
        dragSort: () => columnGrids,
        dragSortInterval: 0,
        dragContainer: document.body,
        dragReleaseDuration: 400,
        dragReleaseEasing: 'ease',
        // Add drag start predicate to only start dragging after a delay
        dragStartPredicate: {
            delay: 200, // 200ms delay before drag starts
            distance: 10 // 10px minimum distance to start drag
        }
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
            const columnStatus = newColumn.dataset.status;
            const taskId = el.dataset.taskId;

            if (taskId && columnStatus) {
                updateTaskStatus(taskId, columnStatus);
            }

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
    dragEnabled: false, // Disable column dragging for admin kanban
    dragReleaseDuration: 400,
    dragReleaseEasing: 'ease'
});

// Function to load task details for editing
function loadTaskForEdit(taskId) {
    // Determina se siamo nel kanban admin o pubblico
    const isAdminKanban = window.location.pathname.includes('/admin/kanban');
    
    let url;
    if (isAdminKanban) {
        url = `/admin/kanban/task/${taskId}/details`;
    } else {
        url = `/tasks/${taskId}/details`;
    }

    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                           document.querySelector('input[name="_token"]')?.value
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Popola il modal con i dati del task
            populateTaskModal(data.task);
            // Mostra il modal
            const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            modal.show();
        } else {
            throw new Error(data.message || 'Errore nel caricamento dei dettagli del task');
        }
    })
    .catch(error => {
        console.error('Error loading task details:', error);
        alert('Errore di caricamento dei dettagli del task: ' + error.message);
    });
}

// Function to populate task modal with data
function populateTaskModal(task) {
    // Popola i campi del modal con i dati del task
    document.getElementById('edit_task_id').value = task.id;
    document.getElementById('edit_title').value = task.title;
    document.getElementById('edit_description').value = task.description || '';
    document.getElementById('edit_priority').value = task.priority;
    document.getElementById('edit_category').value = task.category;
    document.getElementById('edit_status').value = task.status;
    document.getElementById('edit_assigned_to').value = task.assigned_to || '';
    document.getElementById('edit_due_date').value = task.due_date ? task.due_date.split(' ')[0] : '';
    document.getElementById('edit_estimated_hours').value = task.estimated_hours || '';
    document.getElementById('edit_progress_percentage').value = task.progress_percentage || 0;
    document.getElementById('edit_notes').value = task.notes || '';
    document.getElementById('edit_tags').value = task.tags || '';
    
    // Aggiorna le immagini esistenti se presenti
    const existingImagesContainer = document.getElementById('existing-images');
    if (existingImagesContainer) {
        existingImagesContainer.innerHTML = '';
        if (task.attachments && task.attachments.length > 0) {
            task.attachments.forEach((attachment, index) => {
                if (attachment.type === 'image') {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'position-relative d-inline-block me-2 mb-2';
                    imageDiv.innerHTML = `
                        <img src="/storage/${attachment.path}" alt="${attachment.original_name}" 
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                onclick="deleteTaskImage(${task.id}, ${index})" 
                                style="margin: 2px;">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    `;
                    existingImagesContainer.appendChild(imageDiv);
                }
            });
        }
    }
}

// Function to delete task image
function deleteTaskImage(taskId, imageIndex) {
    if (confirm('Sei sicuro di voler eliminare questa immagine?')) {
        fetch('/admin/kanban/delete-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({
                task_id: taskId,
                image_index: imageIndex
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ricarica i dettagli del task per aggiornare le immagini
                loadTaskForEdit(taskId);
            } else {
                alert('Errore: ' + (data.message || 'Errore sconosciuto'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante l\'eliminazione dell\'immagine');
        });
    }
}

// Function to update task status via AJAX
function updateTaskStatus(taskId, newStatus) {
    // Determina se siamo nel kanban admin o pubblico
    const isAdminKanban = window.location.pathname.includes('/admin/kanban');

    let url, method, body;

    if (isAdminKanban) {
        // Kanban Admin
        url = '/admin/kanban/update-status';
        method = 'POST';
        body = JSON.stringify({
            task_id: taskId,
            new_status: newStatus,
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value
        });
    } else {
        // Kanban Pubblico
        url = `/tasks/${taskId}/status`;
        method = 'PATCH';
        body = JSON.stringify({
            status: newStatus,
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value
        });
    }

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                           document.querySelector('input[name="_token"]')?.value
        },
        body: body
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log(`Task ${taskId} status updated to ${newStatus}`);

            // Update the task count badges
            updateColumnCounts();
        } else {
            console.error('Error updating task status:', data.message);
            // Revert the drag if there was an error
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error updating task status:', error);
        // Revert the drag if there was an error
        location.reload();
    });
}

// Function to update column counts
function updateColumnCounts() {
    const columns = document.querySelectorAll('.board-column');
    columns.forEach(column => {
        const status = column.dataset.status;
        const content = column.querySelector('.board-column-content');
        const count = content.querySelectorAll('.board-item').length;
        const badge = column.querySelector('.board-column-header .badge');

        if (badge) {
            badge.textContent = count;
        }
    });
}

// Initialize column counts on page load
document.addEventListener('DOMContentLoaded', function() {
    updateColumnCounts();
});
