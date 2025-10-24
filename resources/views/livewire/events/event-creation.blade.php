<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="card mb-3">
                <div class="card-body text-center">
                        <h2 class="mb-2">
                            <i class="ph ph-calendar-plus me-2"></i>{{ __('events.create_event') }}
                        </h2>
                    <p class="text-secondary mb-0">{{ __('events.create_event_help') }}</p>
                </div>
                    </div>

                    <!-- Wizard Steps Progress -->
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Desktop Progress -->
                            <div class="d-none d-lg-flex align-items-center justify-content-center">
                                @for($i = 1; $i <= $totalSteps; $i++)
                            <div class="text-center cursor-pointer" wire:click="goToStep({{ $i }})">
                                <i class="ph ph-{{ $i == 1 ? 'info' : ($i == 2 ? 'calendar-check' : ($i == 3 ? 'gear' : ($i == 4 ? 'users' : 'eye'))) }} {{ $i <= $currentStep ? 'text-primary' : 'text-secondary' }} f-s-40 mb-2"></i>
                                <div class="f-w-600 {{ $i <= $currentStep ? 'text-primary' : 'text-secondary' }}">
                                            {{ __('events.step') }} {{ $i }}
                                        </div>
                                    </div>
                                    @if($i < $totalSteps)
                                <i class="ph ph-arrow-right text-secondary mx-3 f-s-24"></i>
                                    @endif
                                @endfor
                            </div>

                            <!-- Mobile Progress -->
                            <div class="d-lg-none text-center">
                                <h6 class="mb-2">{{ __('events.step') }} {{ $currentStep }} {{ __('events.step_of') }} {{ $totalSteps }}</h6>
                                <div class="progress">
                            <div class="progress-bar bg-primary"
                                 role="progressbar"
                                 style="width: {{ ($currentStep / $totalSteps) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="save">
                <!-- ========================================
                     STEP 1: BASIC INFORMATION
                     ======================================== -->
                <div class="card" style="display: {{ $currentStep == 1 ? 'block' : 'none' }}">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-info me-2"></i>{{ __('events.step_basic_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('events.title_event') }} *</label>
                                <input type="text"
                                       wire:model.live="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       placeholder="{{ __('events.title_placeholder') }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(strlen($title) > 0)
                                    <small class="text-secondary">{{ strlen($title) }}/255 {{ __('events.characters') }}</small>
                                @endif
                            </div>

                            <!-- Subtitle Toggle -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox"
                                           wire:click="toggleSubtitle"
                                           class="form-check-input"
                                           id="subtitle-toggle"
                                           {{ $has_subtitle ? 'checked' : '' }}>
                                    <label class="form-check-label" for="subtitle-toggle">
                                        <i class="ph ph-{{ $has_subtitle ? 'check-circle' : 'plus-circle' }} me-1"></i>
                                        {{ $has_subtitle ? __('events.subtitle_active') : __('events.add_subtitle') }}
                                    </label>
                                </div>

                                @if($has_subtitle)
                                    <div class="mt-3">
                                        <label class="form-label">{{ __('events.subtitle') }} *</label>
                                        <input type="text"
                                               wire:model.live="subtitle"
                                               class="form-control @error('subtitle') is-invalid @enderror"
                                               placeholder="{{ __('events.subtitle_placeholder') }}"
                                               required>
                                        @error('subtitle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if(strlen($subtitle) > 0)
                                                <small class="text-secondary">{{ strlen($subtitle) }}/255 {{ __('events.characters') }}</small>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('events.description') }}</label>
                                <textarea wire:model.live="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="{{ __('events.description_placeholder') }}"
                                          rows="5"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Requirements -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('events.requirements') }}</label>
                                <textarea wire:model.live="requirements"
                                          class="form-control @error('requirements') is-invalid @enderror"
                                          placeholder="{{ __('events.requirements_placeholder') }}"
                                          rows="3"></textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-secondary">{{ __('events.requirements_help') }}</small>
                            </div>

                            <!-- Category & Public/Private -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                <label class="form-label">{{ __('events.category') }} *</label>
                                <select wire:model.live="category"
                                        class="form-select @error('category') is-invalid @enderror"
                                        required>
                                    <option value="">{{ __('events.select_category') }}</option>
                                    @foreach($categories as $key => $categoryName)
                                        <option value="{{ $key }}">{{ $categoryName }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                    </div>
                            </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                <label class="form-label">{{ __('events.event_type') }} *</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               wire:model.live="is_public"
                                               value="1"
                                               id="public"
                                               checked>
                                        <label class="form-check-label" for="public">
                                            <i class="ph ph-globe me-1"></i>{{ __('events.public') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               wire:model.live="is_public"
                                               value="0"
                                               id="private">
                                        <label class="form-check-label" for="private">
                                            <i class="ph ph-lock me-1"></i>{{ __('events.private') }}
                                        </label>
                                    </div>
                                </div>
                                        <small class="text-secondary d-block mt-2">
                                    @if($is_public)
                                            <i class="ph ph-info text-info me-1"></i>{{ __('events.public_event_help') }}
                                    @else
                                        <i class="ph ph-info text-warning me-1"></i>{{ __('events.private_event_help') }}
                                    @endif
                                        </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- ========================================
                     STEP 2: DATE & LOCATION
                     ======================================== -->
                <div class="card" style="display: {{ $currentStep == 2 ? 'block' : 'none' }}">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-calendar-check me-2"></i>{{ __('events.step_date_location') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="app-form">
                            <!-- DATE & TIME SECTION -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-calendar me-2"></i>{{ __('events.date_and_time') }}
                                </h6>

                                <div class="row">
                                    <!-- Start DateTime -->
                                    <div class="col-md-6">
                                        <div class="mb-3" wire:ignore>
                                            <label class="form-label">{{ __('events.start_datetime') }} *</label>
                                            <input type="text"
                                                   id="start_datetime"
                                                   class="form-control @error('start_datetime') is-invalid @enderror"
                                                   placeholder="{{ __('events.start_datetime_placeholder') }}"
                                                   {{ !$is_availability_based ? 'required' : '' }}
                                                   readonly>
                                        </div>
                                        @error('start_datetime')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- End DateTime -->
                                    <div class="col-md-6">
                                        <div class="mb-3" wire:ignore>
                                            <label class="form-label">{{ __('events.end_datetime') }} *</label>
                                            <input type="text"
                                                   id="end_datetime"
                                                   class="form-control @error('end_datetime') is-invalid @enderror"
                                                   placeholder="{{ __('events.end_datetime_placeholder') }}"
                                                   {{ !$is_availability_based ? 'required' : '' }}
                                                   readonly>
                                        </div>
                                        @error('end_datetime')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Availability Based Event -->
                                    <div class="col-12">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox"
                                                           wire:model.live="is_availability_based"
                                                           class="form-check-input"
                                                           id="is_availability_based">
                                                    <label class="form-check-label f-w-600" for="is_availability_based">
                                                        <i class="ph ph-users-three me-1"></i>{{ __('events.availability_based_event') }}
                                                    </label>
                                                </div>
                                                <small class="text-secondary">
                                                    {{ __('events.availability_based_help') }}
                                                </small>

                                                @if($is_availability_based)
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-2" wire:ignore>
                                                                <label class="form-label">{{ __('events.availability_deadline') }}</label>
                                                                <input type="text"
                                                                       id="availability_deadline"
                                                                       class="form-control"
                                                                       placeholder="{{ __('events.availability_deadline_placeholder') }}"
                                                                       readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">{{ __('events.availability_instructions') }}</label>
                                                            <textarea wire:model.live="availability_instructions"
                                                                      class="form-control"
                                                                      rows="2"
                                                                      placeholder="{{ __('events.availability_instructions_placeholder') }}"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Multiple Dates for Availability -->
                                                    <div class="card mt-3" 
                                                         x-data="availabilityOptions" 
                                                         x-init="init()">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0">
                                                                        <i class="ph ph-calendar-plus me-2"></i>{{ __('events.availability_multiple_dates') }}
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body">
                                                            <p class="text-secondary mb-3">
                                                                        {{ __('events.availability_multiple_dates_help') }}
                                                                    </p>

                                                            <template x-for="(option, index) in options" :key="index">
                                                                <div class="card mb-3">
                                                                    <div class="card-body">
                                                                        <div class="row align-items-end">
                                                                            <div class="col-md-5 mb-2">
                                                                                <label class="form-label">{{ __('events.availability_option_datetime') }} *</label>
                                                                                <input type="text"
                                                                                       :id="'availability_option_' + index"
                                                                                       x-model="options[index].datetime"
                                                                                       class="form-control availability-picker"
                                                                                       placeholder="{{ __('events.availability_option_datetime') }}"
                                                                                       required
                                                                                       readonly>
                                                                    </div>
                                                                            <div class="col-md-6 mb-2">
                                                                                <label class="form-label">{{ __('events.availability_option_description') }}</label>
                                                                                <input type="text"
                                                                                       x-model="options[index].description"
                                                                                       class="form-control"
                                                                                       placeholder="{{ __('events.availability_option_description') }}">
                                                                            </div>
                                                                            <div class="col-md-1 mb-2">
                                                                                <button type="button" 
                                                                                        @click="removeOption(index)"
                                                                                        class="btn btn-danger"
                                                                                        title="{{ __('events.remove_availability_option') }}">
                                                                                    <i class="ph ph-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                                    <div class="text-center mt-3">
                                                                <button type="button" 
                                                                        @click="addOption()"
                                                                        class="btn btn-primary">
                                                                            <i class="ph ph-plus me-2"></i>{{ __('events.add_availability_option') }}
                                                                        </button>
                                                                    </div>

                                                                    <div class="alert alert-info mt-3">
                                                                        <i class="ph ph-info me-2"></i>
                                                                        <strong>{{ __('events.availability_multiple_dates_notice') }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LOCATION SECTION -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-map-pin me-2"></i>{{ __('events.location') }}
                                </h6>

                                <!-- Online/In Person Toggle -->
                                <div class="mb-3">
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                                wire:click="$set('is_online', false)"
                                                class="btn btn-{{ !$is_online ? 'primary' : 'secondary' }} flex-fill">
                                                <i class="ph ph-map-pin me-2"></i>{{ __('events.in_person') }}
                                        </button>
                                        <button type="button"
                                                wire:click="$set('is_online', true)"
                                                class="btn btn-{{ $is_online ? 'primary' : 'secondary' }} flex-fill">
                                                <i class="ph ph-globe me-2"></i>{{ __('events.online') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- ONLINE EVENT FIELDS -->
                                <div class="row" style="display: {{ $is_online ? 'flex' : 'none' }}">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('events.online_url') }} *</label>
                                            <input type="url"
                                                   wire:model.live="online_url"
                                                   class="form-control @error('online_url') is-invalid @enderror"
                                                   placeholder="https://zoom.us/..."
                                                   {{ $is_online ? 'required' : '' }}>
                                            @error('online_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-secondary">
                                                    <i class="ph ph-info me-1"></i>{{ __('events.online_url_help') }}
                                            </small>
                                            </div>
                                        </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('events.timezone') }} *</label>
                                            <select wire:model.live="timezone"
                                                    class="form-select @error('timezone') is-invalid @enderror"
                                                    {{ $is_online ? 'required' : '' }}>
                                                <option value="Europe/Rome">Europe/Rome (UTC+1)</option>
                                                <option value="Europe/London">Europe/London (UTC+0)</option>
                                                <option value="Europe/Paris">Europe/Paris (UTC+1)</option>
                                                <option value="Europe/Berlin">Europe/Berlin (UTC+1)</option>
                                                <option value="America/New_York">America/New York (UTC-5)</option>
                                                <option value="America/Los_Angeles">America/Los Angeles (UTC-8)</option>
                                                <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                                                <option value="Australia/Sydney">Australia/Sydney (UTC+10)</option>
                                            </select>
                                            @error('timezone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                    <!-- IN-PERSON EVENT FIELDS -->
                                <div class="row" style="display: {{ !$is_online ? 'flex' : 'none' }}">
                                    @if(!empty($recentVenues) && count($recentVenues) > 0)
                                        <!-- Recent Venues Dropdown -->
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="ph ph-trend-up me-2"></i>{{ __('events.recent_venues') }}
                                                </label>
                                                <select wire:model.live="selectedRecentVenue" 
                                                        class="form-select"
                                                        wire:change="loadRecentVenueFromSelect">
                                                    <option value="">{{ __('events.select_recent_venue') }}</option>
                                                    @foreach($recentVenues as $index => $venue)
                                                        <option value="{{ $index }}">
                                                            {{ $venue['venue_name'] }} - {{ $venue['venue_address'] }}, {{ $venue['city'] }}
                                                                ({{ $venue['total_usage'] }} {{ __('events.times') }}, {{ $venue['unique_users'] }} {{ __('events.users') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-secondary">
                                                    <i class="ph ph-info me-1"></i>{{ __('events.recent_venues_help') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                            <label class="form-label">{{ __('events.venue_name') }}</label>
                                            <input type="text"
                                                   wire:model.live="venue_name"
                                                   class="form-control @error('venue_name') is-invalid @enderror"
                                                   placeholder="{{ __('events.venue_name_placeholder') }}">
                                            @error('venue_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                            <label class="form-label">{{ __('events.city') }}</label>
                                            <input type="text"
                                                   id="city-input"
                                                   name="city"
                                                   value="{{ $city }}"
                                                   class="form-control @error('city') is-invalid @enderror"
                                                   placeholder="{{ __('events.city_placeholder') }}">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                            <label class="form-label">{{ __('events.venue_address') }}</label>
                                            <textarea wire:model="venue_address"
                                                      class="form-control @error('venue_address') is-invalid @enderror"
                                                      rows="2"
                                                      placeholder="{{ __('events.venue_address_placeholder') }}"></textarea>
                                            @error('venue_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                            <label class="form-label">{{ __('events.postcode') }}</label>
                                            <input type="text"
                                                   wire:model="postcode"
                                                   class="form-control"
                                                   placeholder="{{ __('events.postcode_placeholder') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="mb-3">
                                            <label class="form-label">{{ __('events.country') }}</label>
                                            <select wire:model="country" class="form-select">
                                                <option value="IT">Italia</option>
                                                <option value="FR">France</option>
                                                <option value="DE">Deutschland</option>
                                                <option value="ES">España</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="US">United States</option>
                                            </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button type="button" wire:click="geocodeAddress" class="btn btn-light-primary mb-3">
                                                <i class="ph ph-map-pin me-2"></i>{{ __('events.set_pin_on_map') }}
                                            </button>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('events.map_location') }}</label>
                                                <livewire:events.event-map :latitude="$latitude" :longitude="$longitude" />
                                                <small class="text-secondary d-block mt-2">{{ __('events.map_auto_positioning_help') }}</small>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <!-- RECURRENCE SECTION -->
                            <div>
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-arrow-clockwise me-2"></i>{{ __('events.recurrence') }}
                                </h6>

                                <div class="form-check mb-3">
                                    <input type="checkbox"
                                           wire:model.live="is_recurring"
                                           class="form-check-input"
                                           id="is_recurring">
                                    <label class="form-check-label f-w-600" for="is_recurring">
                                        {{ __('events.recurring_event') }}
                                    </label>
                                </div>

                                @if($is_recurring)
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                    <label class="form-label">{{ __('events.recurrence_type') }} *</label>
                                                    <select wire:model.live="recurrence_type"
                                                            class="form-select @error('recurrence_type') is-invalid @enderror"
                                                            required>
                                                        <option value="">{{ __('events.select') }}</option>
                                                        <option value="daily">{{ __('events.daily') }}</option>
                                                        <option value="weekly">{{ __('events.weekly') }}</option>
                                                        <option value="monthly">{{ __('events.monthly') }}</option>
                                                        <option value="yearly">{{ __('events.yearly') }}</option>
                                                    </select>
                                                    @error('recurrence_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                    <label class="form-label">{{ __('events.recurrence_interval') }}</label>
                                                    <input type="number"
                                                           wire:model.live="recurrence_interval"
                                                           class="form-control"
                                                           min="1"
                                                           value="1">
                                                        <small class="text-secondary">{{ __('events.recurrence_interval_help') }}</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                    <label class="form-label">{{ __('events.recurrence_count') }}</label>
                                                    <input type="number"
                                                           wire:model.live="recurrence_count"
                                                           class="form-control"
                                                           min="1"
                                                           max="100"
                                                           placeholder="10">
                                                        <small class="text-secondary">{{ __('events.recurrence_count_help') }}</small>
                                                    </div>
                                                </div>

                                                @if($recurrence_type == 'weekly')
                                                    <div class="col-12">
                                                        <label class="form-label">{{ __('events.recurrence_weekdays') }} *</label>
                                                        <div class="row g-2">
                                                            @foreach(['1' => __('events.weekday_1'), '2' => __('events.weekday_2'), '3' => __('events.weekday_3'), '4' => __('events.weekday_4'), '5' => __('events.weekday_5'), '6' => __('events.weekday_6'), '7' => __('events.weekday_7')] as $day => $label)
                                                                <div class="col-md-3 col-sm-4 col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                               type="checkbox"
                                                                               wire:model.live="recurrence_weekdays"
                                                                               value="{{ $day }}"
                                                                               id="weekday_{{ $day }}">
                                                                        <label class="form-check-label f-w-600" for="weekday_{{ $day }}">
                                                                            <i class="ph ph-calendar-check me-1 text-primary"></i>{{ $label }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @error('recurrence_weekdays')
                                                            <small class="text-danger d-block mt-1">
                                                                <i class="ph ph-warning me-1"></i>{{ $message }}
                                                            </small>
                                                        @enderror
                                                    </div>
                                                @endif

                                                @if($recurrence_type == 'monthly')
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                        <label class="form-label">{{ __('events.recurrence_monthday') }}</label>
                                                        <input type="number"
                                                               wire:model.live="recurrence_monthday"
                                                               class="form-control"
                                                               min="1"
                                                               max="31"
                                                               placeholder="1">
                                                            <small class="text-secondary">{{ __('events.recurrence_monthday_help') }}</small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================
                     STEP 3: DETAILS
                     ======================================== -->
                <div class="card" style="display: {{ $currentStep == 3 ? 'block' : 'none' }}">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-gear me-2"></i>{{ __('events.step_event_details') }}
                        </h5>
                        <p class="text-muted mb-0">{{ __('events.step_details_description') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- MEDIA SECTION -->
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-primary">
                                                <i class="ph ph-image me-2"></i>{{ __('events.media_and_content') }}
                                        </h6>
                        </div>
                                    <div class="card-body p-3">
                                        <!-- Event Image -->
                                        <div class="mb-3">
                                            <label class="form-label">
                                                {{ __('events.event_image') }} ({{ __('events.optional') }})
                                            </label>
                                            <input type="file"
                                                   wire:model="event_image"
                                                   class="form-control @error('event_image') is-invalid @enderror"
                                                   accept="image/*">
                                            @error('event_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if ($event_image)
                                                <div class="mt-2">
                                                    <img src="{{ $event_image->temporaryUrl() }}" 
                                                         class="img-thumbnail" 
                                                         style="max-height: 150px">
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Promotional Video -->
                                        <div class="mb-0">
                                            <label class="form-label">
                                                {{ __('events.promotional_video') }} ({{ __('events.optional') }})
                                            </label>
                                            <input type="url"
                                                   wire:model.live="promotional_video"
                                                   class="form-control @error('promotional_video') is-invalid @enderror"
                                                   placeholder="{{ __('events.promotional_video_placeholder') }}">
                                            @error('promotional_video')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PAYMENT SECTION -->
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-success">
                                            <i class="ph ph-currency-circle-dollar me-2"></i>{{ __('events.payment') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <!-- Paid Event Toggle -->
                                        <div class="form-check mb-3">
                                            <input type="checkbox"
                                                   wire:model.live="is_paid_event"
                                                   class="form-check-input"
                                                   id="is_paid_event">
                                            <label for="is_paid_event" class="form-check-label f-w-600">
                                                {{ __('events.is_paid_event') }}
                                            </label>
                                        </div>

                                        <!-- Payment Fields -->
                                        @if($is_paid_event)
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label">{{ __('events.ticket_price') }}</label>
                                                    <input type="number"
                                                           wire:model.live="ticket_price"
                                                           class="form-control @error('ticket_price') is-invalid @enderror"
                                                           min="0"
                                                           step="0.01"
                                                           placeholder="{{ __('events.ticket_price_placeholder') }}">
                                                    @error('ticket_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label">{{ __('events.currency') }}</label>
                                                    <select wire:model.live="ticket_currency" class="form-select">
                                                        <option value="EUR">EUR (€)</option>
                                                        <option value="USD">USD ($)</option>
                                                        <option value="GBP">GBP (£)</option>
                                                        <option value="CHF">CHF (CHF)</option>
                                                    </select>
                    </div>
                </div>
                @endif
                                    </div>
                                </div>
                            </div>

                            <!-- GROUPS SECTION -->
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-info">
                                            <i class="ph ph-users me-2"></i>{{ __('events.associations') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <!-- Link to Group Toggle -->
                                        <div class="form-check mb-3">
                                            <input type="checkbox"
                                                   wire:model.live="is_linked_to_group"
                                                   class="form-check-input"
                                                   id="is_linked_to_group">
                                            <label for="is_linked_to_group" class="form-check-label f-w-600">
                                                {{ __('events.is_linked_to_group') }}
                                            </label>
                                        </div>

                                        @if($is_linked_to_group)
                                            <div>
                                                <p class="text-secondary small mb-2">
                                                    {{ __('events.groups_help') }}
                                                </p>
                                                
                                                @php
                                                    $groups = \App\Models\Group::public()->get();
                                                @endphp
                                                
                                                @if($groups->count() > 0)
                                                    <div class="mb-2">
                                                        <label class="form-label">{{ __('events.select_groups') }}</label>
                                                    </div>
                                                    @foreach($groups as $group)
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox"
                                                                   wire:model.live="selected_groups"
                                                                   value="{{ $group->id }}"
                                                                   class="form-check-input"
                                                                   id="group_{{ $group->id }}">
                                                            <label for="group_{{ $group->id }}" class="form-check-label">
                                                                <strong>{{ $group->name }}</strong>
                                                                @if($group->description)
                                                                    <br><small class="text-muted">{{ Str::limit($group->description, 50) }}</small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @else
                                                        <p class="text-muted small">{{ __('events.no_groups_available') }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- FESTIVAL SECTION -->
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-warning">
                                            <i class="ph ph-trophy me-2"></i>{{ __('events.festival') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if($category === 'festival')
                                            <!-- Festival category: select events -->
                                            <div class="alert alert-info alert-sm mb-3">
                                                <small>
                                                    <i class="ph ph-info-circle me-1"></i>
                                                    {{ __('events.festival_events_selection_help') }}
                                                </small>
                                            </div>
                                            <p class="text-secondary small">
                                                {{ __('events.festival_events_management_later') }}
                                            </p>
                                        @else
                                            <!-- Other categories: link to existing festival -->
                                            <div class="form-check mb-3">
                                                <input type="checkbox"
                                                       wire:model.live="is_festival_event"
                                                       class="form-check-input"
                                                       id="is_festival_event">
                                                <label for="is_festival_event" class="form-check-label f-w-600">
                                                    {{ __('events.is_festival_event') }}
                                                </label>
                                            </div>

                                            @if($is_festival_event)
                                                @php
                                                    $festivals = \App\Models\Event::where('category', 'festival')
                                                        ->where('status', 'published')
                                                        ->orderBy('start_datetime', 'desc')
                                                        ->get();
                                                @endphp
                                                
                                                @if($festivals->count() > 0)
                                                    <div>
                                                        <label class="form-label">{{ __('events.select_festival') }}</label>
                                                        <select wire:model.live="festival_id" class="form-select">
                                                            <option value="">{{ __('events.select_festival') }}</option>
                                                            @foreach($festivals as $festival)
                                                                <option value="{{ $festival->id }}">
                                                                    {{ $festival->title }} 
                                                                    @if($festival->start_datetime)
                                                                        ({{ $festival->start_datetime->format('d/m/Y') }})
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <p class="text-muted small">{{ __('events.no_festivals_available') }}</p>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================
                     STEP 4: INVITATIONS & SETTINGS
                     ======================================== -->
                <div class="card" style="display: {{ $currentStep == 4 ? 'block' : 'none' }}">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-users me-2"></i>{{ __('events.invites_and_gig') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- MAX PARTICIPANTS & STATUS -->
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-primary">
                                            <i class="ph ph-users-three me-2"></i>{{ __('events.participants') }}
                                        </h6>
                        </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('events.max_participants') }}</label>
                                            <input type="number"
                                                   wire:model.live="max_participants"
                                                   class="form-control"
                                                   min="1"
                                                    placeholder="{{ __('events.max_participants_placeholder') }}">
                                            <small class="text-secondary">{{ __('events.max_participants_help') }}</small>
                    </div>

                                        <div class="form-check">
                                            <input type="checkbox"
                                                   wire:model.live="allow_requests"
                                                   class="form-check-input"
                                                   id="allow_requests">
                                            <label for="allow_requests" class="form-check-label f-w-600">
                                                {{ __('events.allow_requests') }}
                                            </label>
                                        </div>
                                        <small class="text-secondary d-block mt-1">
                                            {{ __('events.allow_requests_help') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- EVENT STATUS -->
                            <div class="col-md-6">
                                <div class="card border-secondary">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-secondary">
                                            <i class="ph ph-globe me-2"></i>{{ __('events.event_status') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="form-check p-3 border rounded mb-2">
                                            <input type="radio"
                                                   wire:model.live="status"
                                                   value="published"
                                                   class="form-check-input"
                                                   id="status_published">
                                            <label for="status_published" class="form-check-label">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="ph ph-globe me-2 text-success"></i>
                                                    <span class="f-w-600">{{ __('events.publish_immediately') }}</span>
                                                </div>
                                                <small class="text-secondary">{{ __('events.publish_immediately_help') }}</small>
                                            </label>
                                        </div>
                                        <div class="form-check p-3 border rounded">
                                            <input type="radio"
                                                   wire:model.live="status"
                                                   value="draft"
                                                   class="form-check-input"
                                                   id="status_draft">
                                            <label for="status_draft" class="form-check-label">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="ph ph-note-pencil me-2 text-warning"></i>
                                                    <span class="f-w-600">{{ __('events.save_as_draft') }}</span>
                                                </div>
                                                <small class="text-secondary">{{ __('events.save_as_draft_help') }}</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GIG POSITIONS -->
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-warning">
                                            <i class="ph ph-briefcase me-2"></i>{{ __('events.gig_positions') }}
                                            <span class="badge bg-warning ms-2">{{ count($gig_positions) }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <small class="text-secondary d-block mb-3">
                                            <i class="ph ph-info me-1"></i>{{ __('events.gig_positions_help') }}
                                        </small>

                                        <!-- Gig Positions List -->
                                        @if(count($gig_positions) > 0)
                                            <div class="mb-3">
                                                @foreach($gig_positions as $index => $position)
                                                    <div class="card mb-3">
                                                        <div class="card-header">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0">
                                                                    <i class="ph ph-briefcase me-2"></i>{{ __('events.gig_position') }} #{{ $index + 1 }}
                                                                </h6>
                                                                <button type="button"
                                                                        wire:click="removeGigPosition({{ $index }})"
                                                                        class="btn btn-sm btn-danger">
                                                                    <i class="ph ph-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <div class="row">
                                                                <!-- Tipologia -->
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">{{ __('events.position_type') }} *</label>
                                                                    <select wire:model.live="gig_positions.{{ $index }}.type"
                                                                            class="form-select">
                                                                        <option value="">{{ __('events.select_position_type') }}</option>
                                                                        <option value="poeta">{{ __('events.artist_poet') }}</option>
                                                                        <option value="mc">{{ __('events.mc_host') }}</option>
                                                                        <option value="tecnico">{{ __('events.technical_support') }}</option>
                                                                        <option value="volontario">{{ __('events.volunteer') }}</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Quantità -->
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">{{ __('events.quantity') }} *</label>
                                                                    <input type="number"
                                                                           wire:model.live="gig_positions.{{ $index }}.quantity"
                                                                           class="form-control"
                                                                           min="1"
                                                                           value="1">
                                                                </div>

                                                                <!-- Lingua -->
                                                                <div class="col-md-3 mb-3">
                                                                    <label class="form-label">{{ __('events.required_language') }}</label>
                                                                    <select wire:model.live="gig_positions.{{ $index }}.language"
                                                                            class="form-select">
                                                                        <option value="">{{ __('events.no_preference') }}</option>
                                                                        <option value="italiano">{{ __('events.italian') }}</option>
                                                                        <option value="inglese">{{ __('events.english') }}</option>
                                                                        <option value="francese">{{ __('events.french') }}</option>
                                                                        <option value="tedesco">{{ __('events.german') }}</option>
                                                                        <option value="spagnolo">{{ __('events.spanish') }}</option>
                                                                        <option value="portoghese">{{ __('events.portuguese') }}</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Cachet -->
                                                                <div class="col-12">
                                                                    <div class="form-check mb-2">
                                                                        <input type="checkbox"
                                                                               wire:model.live="gig_positions.{{ $index }}.has_cachet"
                                                                               class="form-check-input"
                                                                               id="cachet_{{ $index }}">
                                                                        <label for="cachet_{{ $index }}" class="form-check-label f-w-600">
                                                                            {{ __('events.cachet') }}
                                                                        </label>
                                                                    </div>
                                                                    
                                                                    @if($position['has_cachet'])
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">{{ __('events.amount') }}</label>
                                                                                <input type="number"
                                                                                       wire:model.live="gig_positions.{{ $index }}.cachet_amount"
                                                                                       class="form-control"
                                                                                       min="0"
                                                                                       step="0.01"
                                                                                       placeholder="0.00">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">{{ __('events.currency') }}</label>
                                                                                <select wire:model.live="gig_positions.{{ $index }}.cachet_currency"
                                                                                        class="form-select">
                                                                                    <option value="EUR">EUR (€)</option>
                                                                                    <option value="USD">USD ($)</option>
                                                                                    <option value="GBP">GBP (£)</option>
                                                                                    <option value="CHF">CHF</option>
                                                                                </select>
                    </div>
                </div>
                @endif
                                                                </div>

                                                                <!-- Spese di viaggio -->
                                                                <div class="col-12 mt-2">
                                                                    <div class="form-check mb-2">
                                                                        <input type="checkbox"
                                                                               wire:model.live="gig_positions.{{ $index }}.has_travel"
                                                                               class="form-check-input"
                                                                               id="travel_{{ $index }}">
                                                                        <label for="travel_{{ $index }}" class="form-check-label f-w-600">
                                                                            {{ __('events.travel_expenses') }}
                                                                        </label>
                                                                    </div>
                                                                    
                                                                    @if($position['has_travel'])
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">{{ __('events.max_coverage') }}</label>
                                                                                <input type="number"
                                                                                       wire:model.live="gig_positions.{{ $index }}.travel_max"
                                                                                       class="form-control"
                                                                                       min="0"
                                                                                       step="0.01"
                                                                                       placeholder="0.00">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label">{{ __('events.currency') }}</label>
                                                                                <select wire:model.live="gig_positions.{{ $index }}.travel_currency"
                                                                                        class="form-select">
                                                                                    <option value="EUR">EUR (€)</option>
                                                                                    <option value="USD">USD ($)</option>
                                                                                    <option value="GBP">GBP (£)</option>
                                                                                    <option value="CHF">CHF</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <!-- Vitto e alloggio -->
                                                                <div class="col-12 mt-2">
                                                                    <div class="form-check mb-2">
                                                                        <input type="checkbox"
                                                                               wire:model.live="gig_positions.{{ $index }}.has_accommodation"
                                                                               class="form-check-input"
                                                                               id="accommodation_{{ $index }}">
                                                                        <label for="accommodation_{{ $index }}" class="form-check-label f-w-600">
                                                                            {{ __('events.accommodation') }}
                                                                        </label>
                                                                    </div>
                                                                    
                                                                    @if($position['has_accommodation'])
                                                                        <div>
                                                                            <label class="form-label">{{ __('events.accommodation_details') }}</label>
                                                                            <textarea wire:model.live="gig_positions.{{ $index }}.accommodation_details"
                                                                                      class="form-control"
                                                                                      rows="2"
                                                                                      placeholder="{{ __('events.accommodation_details_placeholder') }}"></textarea>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Add Position Button -->
                                        <div class="text-center">
                                            <button type="button"
                                                    wire:click="addGigPosition"
                                                    class="btn btn-warning">
                                                <i class="ph ph-plus me-2"></i>{{ __('events.add_gig_position') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- INVITATIONS -->
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-success">
                                            <i class="ph ph-envelope me-2"></i>{{ __('events.invitations') }} 
                                            <span class="badge bg-success ms-2">{{ count($invitations) }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <small class="text-secondary d-block mb-3">
                                                <i class="ph ph-info me-1"></i>{{ __('events.invitations_optional_help') }}
                                        </small>

                                        <!-- User Search -->
                                        <div class="mb-3">
                                            <label class="form-label f-w-600">{{ __('events.search_users') }}</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       wire:model.live.debounce.300ms="userSearchQuery"
                                                       class="form-control"
                                                       placeholder="{{ __('events.search_users_placeholder') }}">
                                                <span class="input-group-text">
                                                    <i class="ph ph-magnifying-glass"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Search Results -->
                                        @if(strlen($userSearchQuery) >= 2 && count($searchResults) > 0)
                                            <div class="card mb-3">
                                                <div class="card-body p-2">
                                                    <small class="text-secondary f-w-600">{{ __('events.search_results') }}</small>
                                                    <div class="list-group list-group-flush">
                                                        @foreach($searchResults as $result)
                                                            <div class="list-group-item list-group-item-action p-2">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <strong>{{ $result['name'] }}</strong>
                                                                        @if($result['nickname'])
                                                                            <br><small class="text-secondary">@{{ $result['nickname'] }}</small>
                                                                        @endif
                                                                    </div>
                                                                    <button type="button"
                                                                            wire:click="addInvitation({{ $result['id'] }}, 'performer')"
                                                                            class="btn btn-sm btn-success">
                                                                        <i class="ph ph-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif(strlen($userSearchQuery) >= 2 && count($searchResults) === 0)
                                            <div class="alert alert-light mb-3">
                                                <small><i class="ph ph-warning me-1"></i>{{ __('events.no_users_found') }}</small>
                </div>
                @endif

                                        <!-- Invited Users List -->
                                        @if(count($invitations) > 0)
                                            <div class="mb-2">
                                                <label class="form-label f-w-600">{{ __('events.invited_users') }}</label>
                                            </div>
                                            <div class="row g-2">
                                                @foreach($invitations as $index => $invitation)
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body p-2">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5">
                                                                        <strong>{{ $invitation['name'] }}</strong>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <select wire:model.live="invitations.{{ $index }}.role"
                                                                                class="form-select form-select-sm">
                                                                            <option value="performer">{{ __('events.performer') }}</option>
                                                                            <option value="judge">{{ __('events.judge') }}</option>
                                                                            <option value="technician">{{ __('events.technician') }}</option>
                                                                            <option value="host">{{ __('events.host') }}</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 text-end">
                                                                        <button type="button"
                                                                                wire:click="removeInvitation({{ $index }})"
                                                                                class="btn btn-sm btn-danger">
                                                                            <i class="ph ph-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-light text-center">
                                                <i class="ph ph-user-plus f-s-32 text-secondary mb-2"></i>
                                                <p class="mb-0 text-secondary">{{ __('events.no_invitations_yet') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================
                     STEP 5: PREVIEW
                     ======================================== -->
                <div class="card" style="display: {{ $currentStep == 5 ? 'block' : 'none' }}">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-eye me-2"></i>{{ __('events.event_preview') }}
                        </h5>
                        <p class="text-muted mb-0 mt-2">{{ __('events.preview_description') }}</p>
                    </div>
                    <div class="card-body">
                        <!-- Success Alert -->
                        <div class="alert alert-success mb-4">
                            <div class="d-flex align-items-start">
                                <i class="ph ph-check-circle f-s-32 me-3"></i>
                                <div>
                                        <h5 class="mb-1">{{ __('events.ready_to_create') }}</h5>
                                    <p class="mb-0">{{ __('events.ready_to_create_description') }}</p>
                        </div>
                    </div>
                </div>

                        <div class="row g-3">
                            <!-- BASIC INFO -->
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-primary">
                                            <i class="ph ph-info me-2"></i>{{ __('events.basic_information') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="mb-2">{{ $title }}</h4>
                                                @if($has_subtitle && $subtitle)
                                                    <h6 class="text-muted mb-3">{{ $subtitle }}</h6>
                @endif

                                                <div class="mb-2">
                                                    <span class="badge bg-primary">
                                                        {{ \App\Models\Event::getCategories()[$category] ?? $category }}
                                                    </span>
                                                    <span class="badge bg-{{ $is_public ? 'success' : 'warning' }}">
                                                        {{ $is_public ? __('events.public') : __('events.private') }}
                                                    </span>
                                                    <span class="badge bg-{{ $status === 'published' ? 'success' : 'secondary' }}">
                                                        {{ $status === 'published' ? __('events.published') : __('events.draft') }}
                                                    </span>
                                                </div>

                                                @if($description)
                                                    <div class="mt-3">
                                                        <strong>{{ __('events.description') }}:</strong>
                                                        <p class="text-secondary mt-1">{{ Str::limit($description, 300) }}</p>
                                                    </div>
                                                @endif

                                                @if($requirements)
                                                    <div class="mt-2">
                                                        <strong>{{ __('events.requirements') }}:</strong>
                                                        <p class="text-secondary mt-1">{{ Str::limit($requirements, 200) }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if($event_image)
                                                <div class="col-md-4">
                                                    <img src="{{ $event_image->temporaryUrl() }}" 
                                                         class="img-fluid rounded border"
                                                         alt="Event preview">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DATE & LOCATION -->
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-info">
                                            <i class="ph ph-calendar me-2"></i>{{ __('events.date_and_time') }}
                                        </h6>
                                    </div>
                    <div class="card-body">
                                        @if($is_availability_based)
                                            <div class="alert alert-info alert-sm">
                                                <i class="ph ph-users-three me-1"></i>
                                                <strong>{{ __('events.availability_based_event') }}</strong>
                                            </div>
                                            @if(count($availability_options) > 0)
                                                <p class="mb-1"><strong>{{ __('events.available_dates') }}:</strong></p>
                                                @foreach($availability_options as $option)
                                                    @if(is_array($option) && isset($option['datetime']))
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="ph ph-calendar-check me-2 text-success"></i>
                                                            <span>{{ $option['datetime'] }}</span>
                                                            @if(!empty($option['description']))
                                                                <small class="text-secondary ms-2">- {{ $option['description'] }}</small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                            @else
                                            <p class="mb-1">
                                                <i class="ph ph-calendar-check me-2 text-success"></i>
                                                <strong>{{ __('events.start') }}:</strong> {{ $start_datetime ?: '-' }}
                                            </p>
                                            <p class="mb-0">
                                                <i class="ph ph-calendar-x me-2 text-danger"></i>
                                                <strong>{{ __('events.end') }}:</strong> {{ $end_datetime ?: '-' }}
                                            </p>
                            @endif

                                        @if($is_recurring)
                                            <div class="alert alert-warning alert-sm mt-2">
                                                <i class="ph ph-arrow-clockwise me-1"></i>
                                                <strong>{{ __('events.recurring_event') }}</strong>
                                                <br><small>{{ ucfirst($recurrence_type) }} × {{ $recurrence_count ?: '∞' }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- LOCATION -->
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0 text-warning">
                                                <i class="ph ph-map-pin me-2"></i>{{ __('events.location') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($is_online)
                                            <div class="alert alert-info alert-sm">
                                                <i class="ph ph-globe me-1"></i>
                                                <strong>{{ __('events.online_event') }}</strong>
                                            </div>
                                            @if($online_url)
                                                <p class="mb-1">
                                                    <i class="ph ph-link me-2"></i>
                                                    <a href="{{ $online_url }}" target="_blank" class="text-break">{{ $online_url }}</a>
                                                </p>
                                            @endif
                                            <p class="mb-0">
                                                <i class="ph ph-clock me-2"></i>
                                                <strong>{{ __('events.timezone') }}:</strong> {{ $timezone }}
                                            </p>
                            @else
                                            @if($venue_name)
                                                <p class="mb-1">
                                                    <i class="ph ph-building me-2 text-primary"></i>
                                                    <strong>{{ $venue_name }}</strong>
                                                </p>
                                            @endif
                                            @if($venue_address)
                                                <p class="mb-1">
                                                    <i class="ph ph-map-pin me-2"></i>
                                                    {{ $venue_address }}
                                                </p>
                                            @endif
                                            @if($city)
                                                <p class="mb-1">
                                                    <i class="ph ph-map-trifold me-2"></i>
                                                    {{ $postcode ? $postcode . ' - ' : '' }}{{ $city }}, {{ $country }}
                                                </p>
                                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                            <!-- PAYMENT & DETAILS -->
                            @if($is_paid_event || $promotional_video || $is_linked_to_group || $is_festival_event)
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0">
                                                <i class="ph ph-info me-2"></i>{{ __('events.additional_details') }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @if($is_paid_event)
                                                    <div class="col-md-6">
                                                        <p class="mb-2">
                                                            <i class="ph ph-currency-circle-dollar me-2 text-success"></i>
                                                            <strong>{{ __('events.ticket_price') }}:</strong>
                                                            {{ number_format($ticket_price, 2) }} {{ $ticket_currency }}
                                                        </p>
                                                    </div>
                                                @endif

                                                @if($promotional_video)
                                                    <div class="col-md-6">
                                                        <p class="mb-2">
                                                            <i class="ph ph-video me-2 text-danger"></i>
                                                            <strong>{{ __('events.promotional_video') }}:</strong>
                                                            <a href="{{ $promotional_video }}" target="_blank" class="text-break">{{ Str::limit($promotional_video, 40) }}</a>
                                                        </p>
                                                    </div>
                                                @endif

                                                @if($max_participants)
                                                    <div class="col-md-6">
                                                        <p class="mb-2">
                                                            <i class="ph ph-users-three me-2 text-primary"></i>
                                                            <strong>{{ __('events.max_participants') }}:</strong>
                                                            {{ $max_participants }} {{ __('events.people') }}
                                                        </p>
                                                    </div>
                                                @endif

                                                @if($allow_requests)
                                                    <div class="col-md-6">
                                                        <p class="mb-2">
                                                            <i class="ph ph-check-circle me-2 text-success"></i>
                                                            {{ __('events.allow_requests') }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- GIG POSITIONS -->
                            @if(count($gig_positions) > 0)
                                <div class="col-12">
                                    <div class="card border-warning">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0 text-warning">
                                                <i class="ph ph-briefcase me-2"></i>{{ __('events.gig_positions') }}
                                                <span class="badge bg-warning ms-2">{{ count($gig_positions) }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                                <th>{{ __('events.position_type') }}</th>
                                                            <th>{{ __('events.benefits') }}</th>
                                                            <th class="text-center">{{ __('events.quantity') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($gig_positions as $position)
                                                            <tr>
                                                                <td>
                                                                    <strong>
                                                                        @if($position['type'] === 'poeta'){{ __('events.artist_poet') }}
                                                                        @elseif($position['type'] === 'mc'){{ __('events.mc_host') }}
                                                                        @elseif($position['type'] === 'tecnico'){{ __('events.technical_support') }}
                                                                        @elseif($position['type'] === 'volontario'){{ __('events.volunteer') }}
                                                                        @else{{ $position['type'] }}
                                                                        @endif
                                                                    </strong>
                                                                    @if(!empty($position['language']))
                                                                        <br><small class="text-secondary">
                                                                            <i class="ph ph-globe me-1"></i>{{ ucfirst($position['language']) }}
                                                                        </small>
                                                                    @endif
                                                                </td>
                                                                <td class="text-secondary">
                                                                    @if($position['has_cachet'] && $position['cachet_amount'])
                                                                        <i class="ph ph-currency-circle-dollar me-1 text-success"></i>{{ $position['cachet_amount'] }} {{ $position['cachet_currency'] }}
                                                                    @endif
                                                                    @if($position['has_travel'] && $position['travel_max'])
                                                                        <br><i class="ph ph-airplane me-1 text-info"></i>{{ __('events.travel') }}: {{ $position['travel_max'] }} {{ $position['travel_currency'] }}
                                                                    @endif
                                                                    @if($position['has_accommodation'])
                                                                        <br><i class="ph ph-bed me-1 text-warning"></i>{{ __('events.accommodation_included') }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-warning">{{ $position['quantity'] ?? 1 }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- INVITATIONS -->
                            @if(count($invitations) > 0)
                                <div class="col-12">
                                    <div class="card border-success">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0 text-success">
                                                <i class="ph ph-envelope me-2"></i>{{ __('events.invitations') }}
                                                <span class="badge bg-success ms-2">{{ count($invitations) }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('events.name') }}</th>
                                                            <th>{{ __('events.role') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($invitations as $invitation)
                                                            <tr>
                                                                <td><strong>{{ $invitation['name'] }}</strong></td>
                                                                <td>
                                                                    <span class="badge bg-primary">
                                                                        {{ __('events.' . $invitation['role']) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- GROUPS & FESTIVAL -->
                            @if($is_linked_to_group && count($selected_groups) > 0)
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0 text-info">
                                                <i class="ph ph-users me-2"></i>{{ __('events.linked_groups') }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $linkedGroups = \App\Models\Group::whereIn('id', $selected_groups)->get();
                                            @endphp
                                            @foreach($linkedGroups as $group)
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ph ph-check-circle me-2 text-success"></i>
                                                    <strong>{{ $group->name }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($is_festival_event && $festival_id)
                                <div class="col-md-6">
                                    <div class="card border-warning">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0 text-warning">
                                                <i class="ph ph-trophy me-2"></i>{{ __('events.festival') }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $festival = \App\Models\Event::find($festival_id);
                                            @endphp
                                            @if($festival)
                                                <p class="mb-0">
                                                    <i class="ph ph-link me-2"></i>
                                                    <strong>{{ $festival->title }}</strong>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Final CTA -->
                        <div class="alert alert-light border mt-4 text-center">
                            <i class="ph ph-arrow-down f-s-24 mb-2 text-primary"></i>
                                <h6 class="mb-2">{{ __('events.ready_to_publish') }}</h6>
                            <p class="text-secondary mb-0">
                                {{ __('events.click_create_below') }}
                            </p>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card position-sticky top-0">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info-circle me-2"></i>{{ __('events.help') }}
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-secondary">
                        {{ __('events.help_text_step_' . $currentStep) }}
                    </p>

                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-secondary">{{ __('events.progress') }}</small>
                            <small class="text-secondary f-w-600">{{ round(($currentStep / $totalSteps) * 100) }}%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary"
                                 role="progressbar"
                                 style="width: {{ ($currentStep / $totalSteps) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    <small class="text-success d-block mb-3">
                        <i class="ph ph-check-circle me-1"></i>
                        {{ __('events.step_info', ['current' => $currentStep, 'total' => $totalSteps]) }}:
                    </small>

                    <!-- Step Status -->
                    <div class="mb-4">
                        <h6 class="mb-3">{{ __('events.steps') }}</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2 border-0 {{ $currentStep == 1 ? 'bg-light-primary' : '' }}" 
                                 style="cursor: pointer;" wire:click="goToStep(1)">
                                <i class="ph ph-{{ $currentStep > 1 ? 'check-circle text-success' : ($currentStep == 1 ? 'arrow-right text-primary' : 'circle text-secondary') }} me-2"></i>
                                <div class="d-inline-block">
                                    <div class="{{ $currentStep == 1 ? 'f-w-600 text-primary' : ($currentStep > 1 ? 'f-w-600' : 'text-secondary') }}">
                                        {{ __('events.step_basic_info') }}
                                </div>
                                    <small class="text-secondary d-block">{{ __('events.step_1_hint') }}</small>
                        </div>
                    </div>

                            <div class="list-group-item px-0 py-2 border-0 {{ $currentStep == 2 ? 'bg-light-primary' : '' }}"
                                 style="cursor: pointer;" wire:click="goToStep(2)">
                                <i class="ph ph-{{ $currentStep > 2 ? 'check-circle text-success' : ($currentStep == 2 ? 'arrow-right text-primary' : 'circle text-secondary') }} me-2"></i>
                                <div class="d-inline-block">
                                    <div class="{{ $currentStep == 2 ? 'f-w-600 text-primary' : ($currentStep > 2 ? 'f-w-600' : 'text-secondary') }}">
                                        {{ __('events.step_date_location') }}
                </div>
                                    <small class="text-secondary d-block">{{ __('events.step_2_hint') }}</small>
            </div>
        </div>

                            <div class="list-group-item px-0 py-2 border-0 {{ $currentStep == 3 ? 'bg-light-primary' : '' }}"
                                 style="cursor: pointer;" wire:click="goToStep(3)">
                                <i class="ph ph-{{ $currentStep > 3 ? 'check-circle text-success' : ($currentStep == 3 ? 'arrow-right text-primary' : 'circle text-secondary') }} me-2"></i>
                                <div class="d-inline-block">
                                    <div class="{{ $currentStep == 3 ? 'f-w-600 text-primary' : ($currentStep > 3 ? 'f-w-600' : 'text-secondary') }}">
                                        {{ __('events.step_event_details') }}
                                    </div>
                                    <small class="text-secondary d-block">{{ __('events.step_3_hint') }}</small>
                                </div>
                            </div>

                            <div class="list-group-item px-0 py-2 border-0 {{ $currentStep == 4 ? 'bg-light-primary' : '' }}"
                                 style="cursor: pointer;" wire:click="goToStep(4)">
                                <i class="ph ph-{{ $currentStep > 4 ? 'check-circle text-success' : ($currentStep == 4 ? 'arrow-right text-primary' : 'circle text-secondary') }} me-2"></i>
                                <div class="d-inline-block">
                                    <div class="{{ $currentStep == 4 ? 'f-w-600 text-primary' : ($currentStep > 4 ? 'f-w-600' : 'text-secondary') }}">
                                        {{ __('events.invites_and_gig') }}
                                    </div>
                                    <small class="text-secondary d-block">{{ __('events.step_4_hint') }}</small>
                                </div>
                            </div>

                            <div class="list-group-item px-0 py-2 border-0 {{ $currentStep == 5 ? 'bg-light-primary' : '' }}"
                                 style="cursor: pointer;" wire:click="goToStep(5)">
                                <i class="ph ph-{{ $currentStep > 5 ? 'check-circle text-success' : ($currentStep == 5 ? 'arrow-right text-primary' : 'circle text-secondary') }} me-2"></i>
                                <div class="d-inline-block">
                                    <div class="{{ $currentStep == 5 ? 'f-w-600 text-primary' : ($currentStep > 5 ? 'f-w-600' : 'text-secondary') }}">
                                            {{ __('events.event_preview') }}
                                    </div>
                                    <small class="text-secondary d-block">{{ __('events.step_5_hint') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-3">
                        <div class="d-grid gap-2">
                            @if($currentStep < $totalSteps)
                                <button type="button"
                                        wire:click="nextStep"
                                        class="btn btn-primary">
                                    {{ __('events.next') }}<i class="ph ph-arrow-right ms-2"></i>
                                </button>
                            @else
                                <button type="button"
                                        wire:click="save"
                                        class="btn btn-success btn-lg">
                                        <i class="ph ph-check-circle me-2"></i>{{ __('events.create_event') }}
                                </button>
                            @endif

                            @if($currentStep > 1)
                                <button type="button"
                                        wire:click="prevStep"
                                        class="btn btn-secondary">
                                    <i class="ph ph-arrow-left me-2"></i>{{ __('events.previous') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
@endassets

@script
<script>
// Auto-initialize when DOM is ready
document.addEventListener('livewire:navigated', () => {
    initFlatpickr();
});

// Also init on first load
setTimeout(() => {
    initFlatpickr();
}, 500);

function initFlatpickr() {
    if (typeof flatpickr === 'undefined') {
        return;
    }
    
    const startEl = document.getElementById('start_datetime');
    const endEl = document.getElementById('end_datetime');
    
    if (!startEl || !endEl) {
        return;
    }
    
    // Destroy existing instances if any
    if (startEl._flatpickr) startEl._flatpickr.destroy();
    if (endEl._flatpickr) endEl._flatpickr.destroy();
    
    flatpickr("#start_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: "it",
        minDate: "today",
        defaultDate: $wire.start_datetime || null,
        onChange: (selectedDates, dateStr) => {
            $wire.set('start_datetime', dateStr);
        }
    });

    flatpickr("#end_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: "it",
        minDate: "today",
        defaultDate: $wire.end_datetime || null,
        onChange: (selectedDates, dateStr) => {
            $wire.set('end_datetime', dateStr);
        }
    });
}
</script>
@endscript
