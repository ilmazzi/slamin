// **------calendar js**

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        navLinks: true,
        editable: true,
        dayMaxEvents: true,
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            // Fetch events from API
            fetch('/api/events/calendar')
                .then(response => response.json())
                .then(data => {
                    const events = data.map(event => ({
                        ...event,
                        className: event.className || 'event-participant'
                    }));
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching calendar events:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            // Navigate to event details
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        selectable: true,
        selectMirror: true,
        select: function (arg) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Nuovo Evento', // TODO: Usare traduzione
                    input: 'text',
                    inputLabel: 'Titolo Evento', // TODO: Usare traduzione
                    inputPlaceholder: 'Inserisci il titolo dell\'evento', // TODO: Usare traduzione
                    showCancelButton: true,
                    confirmButtonText: 'Aggiungi', // TODO: Usare traduzione
                    cancelButtonText: 'Annulla', // TODO: Usare traduzione
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Devi inserire un titolo!' // TODO: Usare traduzione
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        calendar.addEvent({
                            title: result.value,
                            start: arg.start,
                            end: arg.end,
                            allDay: arg.allDay
                        });
                    }
                    calendar.unselect();
                });
            } else {
                const title = prompt('Titolo Evento:');
                if (title) {
                    calendar.addEvent({
                        title: title,
                        start: arg.start,
                        end: arg.end,
                        allDay: arg.allDay
                    });
                }
                calendar.unselect();
            }
        },

        droppable: true,
        drop: function (arg) {
            if (document.getElementById('drop-remove').checked) {
                arg.draggedEl.parentNode.removeChild(arg.draggedEl);
            }
        }
    });

    const containerEl = document.getElementById('events-list');
    new FullCalendar.Draggable(containerEl, {
        itemSelector: '.list-event',
        eventData: function (eventEl) {
            return {
                title: eventEl.innerText.trim(),
                start: new Date(),
                className: eventEl.getAttribute("data-class")
            };
        }
    });

    calendar.render();
});

// **------slider js**

$('.slider').slick({
    dots: false,
    speed: 1000,
    slidesToShow: 3,
    centerMode: true,
    arrows: false,
    vertical: true,
    verticalSwiping: true,
    focusOnSelect: true,
    autoplay: true,
    autoplaySpeed: 1000,
});
