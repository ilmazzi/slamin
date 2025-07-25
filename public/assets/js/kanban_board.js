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
            const columnHeader = newColumn.querySelector('.board-column-header h6').textContent.trim();
            let newStatus = 'todo';

            // Map column headers to status values
            if (columnHeader.includes('TODO')) newStatus = 'todo';
            else if (columnHeader.includes('IN PROGRESS')) newStatus = 'in_progress';
            else if (columnHeader.includes('REVIEW')) newStatus = 'review';
            else if (columnHeader.includes('TESTING')) newStatus = 'testing';
            else if (columnHeader.includes('DONE')) newStatus = 'done';

            const taskId = el.dataset.taskId;
            if (taskId && typeof updateTaskStatus === 'function') {
                updateTaskStatus(taskId, newStatus);
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
