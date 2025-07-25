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

// Function to update task status via AJAX
function updateTaskStatus(taskId, newStatus) {
    fetch(`/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                           document.querySelector('input[name="_token"]')?.value
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
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
