<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Event;
use App\Models\User;
use App\Models\Group;
use App\Models\RecentVenue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;

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
    
    // User search for invitations
    public $userSearchQuery = '';
    public $searchResults = [];
    public $searching = false;

    // Tags
    public $tags = [];

    // ========================================
    // RECENT VENUES
    // ========================================
    public $recentVenues = [];
    public $selectedRecentVenue = '';

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

        // Load recent venues - convert to array for Livewire compatibility
        $this->recentVenues = RecentVenue::getPopularVenues(8)->toArray();

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
                ];

                // Only validate recurrence fields if recurring is enabled
                if ($this->is_recurring) {
                    $rules['recurrence_type'] = 'required|string|in:daily,weekly,monthly,yearly';
                    $rules['recurrence_interval'] = 'required|integer|min:1|max:365';
                    $rules['recurrence_count'] = 'nullable|integer|min:1|max:100';
                    
                    // Validate weekdays only if recurrence type is weekly
                    if ($this->recurrence_type === 'weekly') {
                        $rules['recurrence_weekdays'] = 'required|array|min:1';
                    }
                    
                    // Validate monthday only if recurrence type is monthly
                    if ($this->recurrence_type === 'monthly') {
                        $rules['recurrence_monthday'] = 'required|integer|min:1|max:31';
                    }
                }

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
        
        // Emit event when is_online changes
        if ($propertyName === 'is_online') {
            $this->dispatch('location-mode-changed', isOnline: $this->is_online);
        }
        
        // Auto-geocode when address fields change
        if (in_array($propertyName, ['venue_address', 'city', 'postcode', 'country'])) {
            $this->geocodeAddress();
        }
    }

    #[On('map-clicked')]
    public function handleMapClick($latitude, $longitude, $city, $address, $postcode, $country)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->city = $city;
        $this->venue_address = $address;
        $this->postcode = $postcode;
        $this->country = $country;
    }

    public function geocodeAddress()
    {
        // Need at least city or address to geocode
        if (empty($this->city) && empty($this->venue_address)) {
            return;
        }
        
        // Build full address for geocoding - in correct order for Nominatim
        $addressParts = array_filter([
            $this->venue_address,
            $this->postcode,
            $this->city,
            $this->country
        ]);
        
        $fullAddress = implode(', ', $addressParts);
        
        // Only geocode if we have a meaningful address (at least 5 chars)
        if (strlen($fullAddress) < 5) {
            return;
        }
        
        // Dispatch to frontend to geocode
        $this->dispatch('geocode-address', address: $fullAddress);
    }

    // Load a recent venue from select dropdown
    public function loadRecentVenueFromSelect()
    {
        if (empty($this->selectedRecentVenue) || $this->selectedRecentVenue === '') {
            return;
        }

        $venueIndex = (int) $this->selectedRecentVenue;
        
        if (!isset($this->recentVenues[$venueIndex])) {
            return;
        }

        $venue = $this->recentVenues[$venueIndex];
        
        // Populate venue fields (venue is now an array)
        $this->venue_name = $venue['venue_name'] ?? '';
        $this->venue_address = $venue['venue_address'] ?? '';
        $this->city = $venue['city'] ?? '';
        $this->postcode = $venue['postcode'] ?? '';
        $this->country = $venue['country'] ?? 'IT';
        $this->latitude = $venue['latitude'] ?? '';
        $this->longitude = $venue['longitude'] ?? '';

        // Dispatch event to update map
        if ($this->latitude && $this->longitude) {
            $this->dispatch('update-map-location', 
                latitude: floatval($this->latitude), 
                longitude: floatval($this->longitude)
            );
        }

        // Show success message
        session()->flash('success', __('events.venue_loaded_successfully'));
    }

    // ========================================
    // USER SEARCH & INVITATIONS
    // ========================================
    public function updatedUserSearchQuery()
    {
        if (strlen($this->userSearchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searching = true;

        $this->searchResults = User::where(function ($q) {
                $q->where('name', 'like', "%{$this->userSearchQuery}%")
                  ->orWhere('email', 'like', "%{$this->userSearchQuery}%")
                  ->orWhere('nickname', 'like', "%{$this->userSearchQuery}%");
            })
            ->where('id', '!=', Auth::id())
            ->whereNotIn('id', collect($this->invitations)->pluck('user_id'))
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nickname' => $user->nickname,
                    'avatar' => $user->avatar_url ?? null,
                ];
            })
            ->toArray();

        $this->searching = false;
    }

    public function addInvitation($userId, $role = 'performer')
    {
        // Check if user already invited
        if (collect($this->invitations)->contains('user_id', $userId)) {
            session()->flash('warning', __('events.user_already_invited'));
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $this->invitations[] = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role,
        ];

        // Clear search
        $this->userSearchQuery = '';
        $this->searchResults = [];

        session()->flash('success', __('events.invitation_added_for', ['name' => $user->name]));
    }

    public function removeInvitation($index)
    {
        if (isset($this->invitations[$index])) {
            $userName = $this->invitations[$index]['name'];
            unset($this->invitations[$index]);
            $this->invitations = array_values($this->invitations); // Re-index array
            session()->flash('success', __('events.invitation_removed_for', ['name' => $userName]));
        }
    }

    public function updateInvitationRole($index, $role)
    {
        if (isset($this->invitations[$index])) {
            $this->invitations[$index]['role'] = $role;
        }
    }

    // Gig Positions Management
    public function addGigPosition()
    {
        $this->gig_positions[] = [
            'type' => '',
            'quantity' => 1,
            'language' => '',
            'has_cachet' => false,
            'cachet_amount' => '',
            'cachet_currency' => 'EUR',
            'has_travel' => false,
            'travel_max' => '',
            'travel_currency' => 'EUR',
            'has_accommodation' => false,
            'accommodation_details' => '',
        ];
    }

    public function removeGigPosition($index)
    {
        if (isset($this->gig_positions[$index])) {
            unset($this->gig_positions[$index]);
            $this->gig_positions = array_values($this->gig_positions); // Re-index
        }
    }

    // ========================================
    // SAVE EVENT
    // ========================================
    public function save()
    {
        try {
            // Final validation
            $this->validate();
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
                'recurrence_type' => $this->is_recurring && $this->recurrence_type ? $this->recurrence_type : null,
                'recurrence_interval' => $this->is_recurring ? $this->recurrence_interval : null,
                'recurrence_count' => $this->is_recurring && $this->recurrence_count ? $this->recurrence_count : null,
                'recurrence_weekdays' => $this->is_recurring && !empty($this->recurrence_weekdays) ? $this->recurrence_weekdays : null,
                'recurrence_monthday' => $this->is_recurring && $this->recurrence_monthday ? $this->recurrence_monthday : null,

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

            // Handle invitations
            if (!empty($this->invitations)) {
                foreach ($this->invitations as $invitation) {
                    $event->invitations()->create([
                        'invited_user_id' => $invitation['user_id'],
                        'inviter_id' => Auth::id(),
                        'role' => $invitation['role'],
                        'status' => 'pending',
                    ]);
                }
            }

            // Handle availability options
            if ($this->is_availability_based && !empty($this->availability_options)) {
                foreach ($this->availability_options as $option) {
                    if (is_array($option) && !empty($option['datetime'])) {
                        $event->availabilityOptions()->create([
                            'datetime' => $option['datetime'],
                            'description' => $option['description'] ?? null,
                        ]);
                    }
                }
            }

            // Save recent venue if it's a physical event
            if (!$this->is_online && $this->venue_name) {
                \App\Models\RecentVenue::saveRecentVenue([
                    'venue_name' => $this->venue_name,
                    'venue_address' => $this->venue_address,
                    'city' => $this->city,
                    'postcode' => $this->postcode,
                    'country' => $this->country,
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                ]);
            }

            // Log event creation
            LoggingService::logEvent('create', [
                'event_id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'is_public' => $event->is_public,
            ], Event::class, $event->id);

            session()->flash('success', __('events.event_created_successfully'));
            return redirect()->route('events.show', $event->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors - let Livewire handle them
            throw $e;
        } catch (\Exception $e) {
            Log::error('Event creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            LoggingService::logError('event_creation_failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            session()->flash('error', __('events.error_creating_event', ['message' => $e->getMessage()]));
        }
    }

    // ========================================
    // RENDER
    // ========================================
    public function render()
    {
        return view('livewire.events.event-creation', [
            'categories' => Event::getCategories(),
        ])->extends('layout.master')->section('main-content');
    }
}
