<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\LoggingService;

class EventCreation extends Component
{
    use WithFileUploads;

    // ========================================
    // STEP MANAGEMENT
    // ========================================
    public $currentStep = 1;
    public $totalSteps = 5;

    // ========================================
    // STEP 1: BASIC INFORMATION
    // ========================================
    public $title = '';
    public $has_subtitle = false;
    public $subtitle = '';
    public $description = '';
    public $requirements = '';
    public $category = '';
    public $is_public = true;

    // ========================================
    // STEP 2: DATE & LOCATION
    // ========================================
    // Dates
    public $start_datetime = '';
    public $end_datetime = '';
    public $registration_deadline = '';
    public $invitation_deadline = '';

    // Availability-based settings
    public $is_availability_based = false;
    public $availability_deadline = '';
    public $availability_instructions = '';
    public $availability_options = [];

    // Location
    public $is_online = false;
    public $online_url = '';
    public $timezone = 'Europe/Rome';
    public $venue_name = '';
    public $venue_address = '';
    public $city = '';
    public $postcode = '';
    public $country = 'IT';
    public $latitude = '';
    public $longitude = '';

    // Recurrence
    public $is_recurring = false;
    public $recurrence_type = '';
    public $recurrence_interval = 1;
    public $recurrence_count = '';
    public $recurrence_weekdays = [];
    public $recurrence_monthday = '';

    // ========================================
    // STEP 3: DETAILS
    // ========================================
    // Media
    public $event_image;
    public $promotional_video = '';

    // Payment
    public $is_paid_event = false;
    public $ticket_price = 0;
    public $ticket_currency = 'EUR';

    // Groups
    public $is_linked_to_group = false;
    public $selected_groups = [];

    // Festival
    public $is_festival_event = false;
    public $festival_id = '';
    public $selected_festival_events = [];

    // Gig Positions
    public $gig_positions = [];

    // ========================================
    // STEP 4: INVITATIONS & SETTINGS
    // ========================================
    // Registration deadline
    public $has_registration_deadline = false;
    public $registration_deadline_date = '';
    public $registration_deadline_time = '';

    // Status
    public $status = 'published';

    // Event settings
    public $max_participants = '';
    public $allow_requests = false;

    // Invitations
    public $invitation_role = 'performer';
    public $private_invited_users = [];
    public $artist_invited_users = [];
    public $invitations = [];

    // Tags
    public $tags = [];

    // ========================================
    // STEP 5: PREVIEW (auto-generated)
    // ========================================

    // ========================================
    // VALIDATION RULES
    // ========================================
    protected function rules()
    {
        return [
            // Step 1: Basic Information
            'title' => 'required|string|max:255',
            'has_subtitle' => 'boolean',
            'subtitle' => $this->has_subtitle ? 'required|string|max:255' : 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Event::getCategories())),
            'is_public' => 'required|boolean',

            // Step 2: Date & Location
            'start_datetime' => $this->is_availability_based ? 'nullable|date' : 'required|date|after:now',
            'end_datetime' => $this->is_availability_based ? 'nullable|date|after:start_datetime' : 'required|date|after:start_datetime',
            'is_online' => 'boolean',
            'online_url' => $this->is_online ? 'required|url' : 'nullable|url',
            'timezone' => $this->is_online ? 'required|string' : 'nullable|string',
            'venue_name' => !$this->is_online ? 'nullable|string|max:255' : 'nullable',
            'venue_address' => !$this->is_online ? 'nullable|string' : 'nullable',
            'city' => !$this->is_online ? 'nullable|string|max:255' : 'nullable',
            'postcode' => 'nullable|string|max:10',
            'country' => 'nullable|string|size:2',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Recurrence
            'is_recurring' => 'boolean',
            'recurrence_type' => $this->is_recurring ? 'required|in:daily,weekly,monthly,yearly' : 'nullable',
            'recurrence_interval' => $this->is_recurring ? 'required|integer|min:1' : 'nullable',
            'recurrence_count' => $this->is_recurring ? 'nullable|integer|min:1|max:100' : 'nullable',

            // Step 3: Details
            'event_image' => 'nullable|image|max:2048',
            'promotional_video' => 'nullable|url',
            'ticket_price' => 'nullable|numeric|min:0',
            'ticket_currency' => 'nullable|string|size:3',

            // Step 4: Settings
            'max_participants' => 'nullable|integer|min:1',
            'allow_requests' => 'boolean',
            'status' => 'required|in:draft,published',
        ];
    }

    protected $messages = [
        'title.required' => 'Il titolo è obbligatorio',
        'title.max' => 'Il titolo non può superare 255 caratteri',
        'category.required' => 'La categoria è obbligatoria',
        'category.in' => 'La categoria selezionata non è valida',
        'start_datetime.required' => 'La data di inizio è obbligatoria',
        'start_datetime.after' => 'La data di inizio deve essere futura',
        'end_datetime.required' => 'La data di fine è obbligatoria',
        'end_datetime.after' => 'La data di fine deve essere dopo la data di inizio',
        'online_url.required' => 'L\'URL dell\'evento online è obbligatorio',
        'online_url.url' => 'L\'URL deve essere un link valido',
        'timezone.required' => 'Il fuso orario è obbligatorio per eventi online',
        'event_image.image' => 'Il file deve essere un\'immagine',
        'event_image.max' => 'L\'immagine non può superare 2MB',
        'promotional_video.url' => 'L\'URL del video deve essere un link valido',
        'recurrence_type.required' => 'Il tipo di ricorrenza è obbligatorio',
        'recurrence_interval.required' => 'L\'intervallo di ricorrenza è obbligatorio',
        'recurrence_interval.min' => 'L\'intervallo deve essere almeno 1',
        'recurrence_count.max' => 'Il numero di occorrenze non può superare 100',
    ];

    // ========================================
    // LIFECYCLE METHODS
    // ========================================
    public function mount()
    {
        // Set default values
        $this->timezone = config('app.timezone', 'Europe/Rome');
        $this->country = 'IT';
        $this->is_public = true;
        $this->status = 'published';
        $this->ticket_currency = 'EUR';
        $this->invitation_role = 'performer';

        // Check permissions - simplified for testing
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Non hai i permessi per creare eventi');
        }

        // Simplified permission check - allow all authenticated users for now
        // TODO: Implement proper permission checks later
    }

    // ========================================
    // STEP NAVIGATION
    // ========================================
    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    // ========================================
    // TOGGLE METHODS
    // ========================================
    public function toggleSubtitle()
    {
        $this->has_subtitle = !$this->has_subtitle;

        // Se disattiviamo il sottotitolo, svuotiamo il campo
        if (!$this->has_subtitle) {
            $this->subtitle = '';
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }

    // ========================================
    // VALIDATION
    // ========================================
    public function validateCurrentStep()
    {
        switch ($this->currentStep) {
            case 1:
                return $this->validate([
                    'title' => 'required|string|max:255',
                    'has_subtitle' => 'boolean',
                    'subtitle' => $this->has_subtitle ? 'required|string|max:255' : 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'requirements' => 'nullable|string',
                    'category' => 'required|string|in:' . implode(',', array_keys(Event::getCategories())),
                    'is_public' => 'required|boolean',
                ]);

            case 2:
                $rules = [
                    'is_online' => 'boolean',
                    'registration_deadline' => 'nullable|date|before:start_datetime',
                    'invitation_deadline' => 'nullable|date|before:start_datetime',
                    'is_availability_based' => 'boolean',
                    'availability_deadline' => 'nullable|date|after:now',
                    'availability_instructions' => 'nullable|string|max:500',
                    'is_recurring' => 'boolean',
                    'recurrence_type' => 'required_if:is_recurring,true|nullable|string|in:daily,weekly,monthly,yearly',
                    'recurrence_interval' => 'required_if:is_recurring,true|nullable|integer|min:1|max:365',
                    'recurrence_count' => 'nullable|integer|min:1|max:100',
                    'recurrence_weekdays' => 'required_if:recurrence_type,weekly|nullable|array|min:1',
                    'recurrence_monthday' => 'required_if:recurrence_type,monthly|nullable|integer|min:1|max:31',
                ];

                if ($this->is_availability_based) {
                    $rules['start_datetime'] = 'nullable|date';
                    $rules['end_datetime'] = 'nullable|date|after:start_datetime';
                } else {
                    $rules['start_datetime'] = 'required|date|after:now';
                    $rules['end_datetime'] = 'required|date|after:start_datetime';
                }

                if ($this->is_online) {
                    $rules['online_url'] = 'required|url';
                    $rules['timezone'] = 'required|string';
                } else {
                    $rules['venue_name'] = 'nullable|string|max:255';
                    $rules['venue_address'] = 'nullable|string|max:500';
                    $rules['city'] = 'nullable|string|max:100';
                    $rules['postcode'] = 'nullable|string|max:10';
                    $rules['country'] = 'nullable|string|size:2';
                    $rules['latitude'] = 'nullable|numeric|between:-90,90';
                    $rules['longitude'] = 'nullable|numeric|between:-180,180';
                }

                return $this->validate($rules);

            case 3:
                return $this->validate([
                    'event_image' => 'nullable|image|max:2048',
                    'promotional_video' => 'nullable|url',
                    'ticket_price' => 'nullable|numeric|min:0',
                ]);

            case 4:
                return $this->validate([
                    'max_participants' => 'nullable|integer|min:1',
                    'status' => 'required|in:draft,published',
                ]);

            default:
                return true;
        }
    }

    // ========================================
    // REAL-TIME VALIDATION
    // ========================================
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // ========================================
    // SAVE EVENT
    // ========================================
    public function save()
    {
        // Final validation
        $this->validate();

        try {
            // Handle image upload
            $imagePath = null;
            if ($this->event_image) {
                $imagePath = $this->event_image->store('events', 'public');
            }

            // Prepare gig positions
            $gigPositions = !empty($this->gig_positions) ? array_values($this->gig_positions) : null;

            // Prepare tags
            $tagsArray = !empty($this->tags) ? $this->tags : null;

            // Create event
            $event = Event::create([
                // Basic Information
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'description' => $this->description,
                'requirements' => $this->requirements,
                'category' => $this->category,
                'is_public' => $this->is_public,

                // Date & Time
                'start_datetime' => $this->start_datetime ?: null,
                'end_datetime' => $this->end_datetime ?: null,
                'registration_deadline' => $this->has_registration_deadline && $this->registration_deadline_date && $this->registration_deadline_time
                    ? $this->registration_deadline_date . ' ' . $this->registration_deadline_time
                    : null,
                'invitation_deadline' => $this->invitation_deadline ?: null,

                // Availability
                'is_availability_based' => $this->is_availability_based,
                'availability_deadline' => $this->availability_deadline ?: null,
                'availability_instructions' => $this->availability_instructions,

                // Location
                'is_online' => $this->is_online,
                'online_url' => $this->online_url,
                'timezone' => $this->timezone,
                'venue_name' => $this->venue_name,
                'venue_address' => $this->venue_address,
                'city' => $this->city,
                'postcode' => $this->postcode,
                'country' => $this->country,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,

                // Recurrence
                'is_recurring' => $this->is_recurring,
                'recurrence_type' => $this->recurrence_type,
                'recurrence_interval' => $this->recurrence_interval,
                'recurrence_count' => $this->recurrence_count,
                'recurrence_weekdays' => !empty($this->recurrence_weekdays) ? $this->recurrence_weekdays : null,
                'recurrence_monthday' => $this->recurrence_monthday,

                // Details
                'image_url' => $imagePath ? Storage::url($imagePath) : null,
                'entry_fee' => $this->is_paid_event ? $this->ticket_price : 0,
                'gig_positions' => $gigPositions,
                'tags' => $tagsArray,

                // Settings
                'max_participants' => $this->max_participants ?: null,
                'allow_requests' => $this->allow_requests,
                'status' => $this->status,

                // Festival
                'festival_id' => $this->festival_id ?: null,
                'festival_events' => !empty($this->selected_festival_events) ? $this->selected_festival_events : null,

                // Organizer
                'organizer_id' => Auth::id(),
            ]);

            // Attach groups if selected
            if ($this->is_linked_to_group && !empty($this->selected_groups)) {
                $event->groups()->attach($this->selected_groups);
            }

            // TODO: Handle invitations
            // TODO: Handle availability options
            // TODO: Generate recurring events

            // Log event creation
            LoggingService::logEvent('create', [
                'event_id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'is_public' => $event->is_public,
            ], Event::class, $event->id);

            session()->flash('success', 'Evento creato con successo!');
            return redirect()->route('events.show', $event->id);

        } catch (\Exception $e) {
            LoggingService::logError('validation_failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            session()->flash('error', 'Errore durante la creazione dell\'evento: ' . $e->getMessage());
        }
    }

    // ========================================
    // RENDER
    // ========================================
    public function render()
    {
        return view('livewire.event-creation', [
            'categories' => Event::getCategories(),
        ]);
    }
}
