@extends('layout.master')

@section('title', __('events_general.edit_event'))

@section('css')
<!-- Leaflet CSS is loaded by EventMap component -->
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('main-content')
    <livewire:event-edit :eventId="$event->id" />
@endsection

@push('scripts')
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>

<script>
document.addEventListener('livewire:initialized', () => {
    // Get the Livewire component
    const component = Livewire.first();

    // Initialize Flatpickr for datetime fields
    const startDateInput = document.getElementById('start_datetime');
    const endDateInput = document.getElementById('end_datetime');
    const regDeadlineInput = document.getElementById('registration_deadline');
    const invDeadlineInput = document.getElementById('invitation_deadline');
    const availDeadlineInput = document.getElementById('availability_deadline');

    // Start Datetime Picker
    if (startDateInput) {
        flatpickr(startDateInput, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            locale: 'it',
            onChange: function(selectedDates, dateStr) {
                component.set('start_datetime', dateStr);
                console.log('✅ Start datetime set:', dateStr);
            }
        });
    }

    // End Datetime Picker
    if (endDateInput) {
        flatpickr(endDateInput, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            locale: 'it',
            onChange: function(selectedDates, dateStr) {
                component.set('end_datetime', dateStr);
                console.log('✅ End datetime set:', dateStr);
            }
        });
    }

    // Registration Deadline Picker
    if (regDeadlineInput) {
        flatpickr(regDeadlineInput, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            locale: 'it',
            onChange: function(selectedDates, dateStr) {
                component.set('registration_deadline', dateStr);
            }
        });
    }

    // Invitation Deadline Picker
    if (invDeadlineInput) {
        flatpickr(invDeadlineInput, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            locale: 'it',
            onChange: function(selectedDates, dateStr) {
                component.set('invitation_deadline', dateStr);
            }
        });
    }

    // Availability Deadline Picker
    if (availDeadlineInput) {
        flatpickr(availDeadlineInput, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            locale: 'it',
            onChange: function(selectedDates, dateStr) {
                component.set('availability_deadline', dateStr);
            }
        });
    }

    console.log('✅ Flatpickr initialized for all datetime fields');
});
</script>
@endpush

