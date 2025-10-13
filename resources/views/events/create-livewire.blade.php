@extends('layout.master')

@section('title', __('events_general.create_event'))

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datepikar/flatpickr.min.css') }}">
@endsection

@section('main-content')
    @livewire('event-creation')
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/datepikar/flatpickr.js') }}"></script>

<script>
// Wait for everything to load
window.addEventListener('load', () => {
    setTimeout(() => {
        initializeFlatpickr();
    }, 1000);
});

// Initialize Flatpickr
function initializeFlatpickr() {
    if (typeof flatpickr === 'undefined') {
        setTimeout(initializeFlatpickr, 500);
        return;
    }

    // Get Livewire component
    const getLivewireComponent = () => {
        const element = document.querySelector('[wire\\:id]');
        return element ? Livewire.find(element.getAttribute('wire:id')) : null;
    };
    
    // Start datetime
    flatpickr("#start_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        onChange: function(selectedDates, dateStr) {
            const component = getLivewireComponent();
            if (component) {
                component.set('start_datetime', dateStr);
                console.log('✅ Start datetime set:', dateStr);
            }
        }
    });

    // End datetime
    flatpickr("#end_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        onChange: function(selectedDates, dateStr) {
            const component = getLivewireComponent();
            if (component) {
                component.set('end_datetime', dateStr);
                console.log('✅ End datetime set:', dateStr);
            }
        }
    });

    // Availability deadline
    flatpickr("#availability_deadline", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        onChange: function(selectedDates, dateStr) {
            const component = getLivewireComponent();
            if (component) {
                component.set('availability_deadline', dateStr);
                console.log('✅ Availability deadline set:', dateStr);
            }
        }
    });

    console.log('✅ Flatpickr initialized');
}

// ========================================
// ALPINE.JS COMPONENT - Availability Options
// ========================================
document.addEventListener('alpine:init', () => {
    Alpine.data('availabilityOptions', () => ({
        options: [],
        
        init() {
            this.options = this.$wire.get('availability_options') || [];
            
            this.$watch('options', value => {
                this.$wire.set('availability_options', value);
                
                // Initialize flatpickr for new inputs
                this.$nextTick(() => {
                    document.querySelectorAll('.availability-picker').forEach((input, idx) => {
                        if (!input._flatpickr) {
                            flatpickr(input, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                                onChange: (dates, dateStr) => {
                                    this.options[idx].datetime = dateStr;
                                }
                            });
                        }
                    });
                });
            });
        },
        
        addOption() {
            this.options.push({ datetime: '', description: '' });
        },
        
        removeOption(index) {
            this.options.splice(index, 1);
        }
    }));
});
</script>
@endpush
