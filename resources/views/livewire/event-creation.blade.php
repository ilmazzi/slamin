<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="mb-2">
                            <i class="ph ph-calendar-plus me-2"></i>{{ __('events.create_event') }}
                        </h2>
                        <p class="text-muted mb-0">{{ __('events.create_event_help') }}</p>
                    </div>

                    <!-- Wizard Steps Progress -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-none d-lg-flex align-items-center justify-content-center">
                                @for($i = 1; $i <= $totalSteps; $i++)
                                    <div class="text-center cursor-pointer" wire:click="goToStep({{ $i }})" data-step="{{ $i }}">
                                        <i class="ph ph-{{ $i == 1 ? 'info' : ($i == 2 ? 'calendar-check' : ($i == 3 ? 'gear' : ($i == 4 ? 'users' : 'eye'))) }} fs-1 {{ $i <= $currentStep ? 'text-light-primary' : 'text-muted' }} mb-2"></i>
                                        <div class="small fw-bold {{ $i <= $currentStep ? 'text-light-primary' : 'text-muted' }}">
                                            {{ __('events.step') }} {{ $i }}
                                        </div>
                                    </div>
                                    @if($i < $totalSteps)
                                        <i class="ph ph-arrow-right text-muted mx-3"></i>
                                    @endif
                                @endfor
                            </div>

                            <!-- Mobile Progress -->
                            <div class="d-lg-none text-center">
                                <h6 class="mb-2">{{ __('events.step') }} {{ $currentStep }} {{ __('events.step_of') }} {{ $totalSteps }}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-light-primary" role="progressbar"
                                         style="width: {{ ($currentStep / $totalSteps) * 100 }}%"
                                         aria-valuenow="{{ $currentStep }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $totalSteps }}">
                                    </div>
                                </div>
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
                @if($currentStep == 1)
                <div class="card" id="step-1">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-info me-2"></i>{{ __('events.step_basic_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-12 mb-3">
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
                                    <div class="form-text">{{ strlen($title) }}/255 {{ __('events.characters') }}</div>
                                @endif
                            </div>

                            <!-- Subtitle Toggle -->
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox"
                                           wire:click="toggleSubtitle"
                                           class="form-check-input"
                                           id="subtitle-toggle"
                                           {{ $has_subtitle ? 'checked' : '' }}>
                                    <label class="form-check-label" for="subtitle-toggle">
                                        <i class="ph ph-{{ $has_subtitle ? 'check-circle' : 'plus-circle' }} me-2"></i>
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
                                            <div class="form-text">{{ strlen($subtitle) }}/255 {{ __('events.characters') }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('events.description') }}</label>
                                <textarea wire:model.live="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="{{ __('events.description_placeholder') }}"
                                          style="height: 120px"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Requirements -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('events.requirements') }}</label>
                                <textarea wire:model.live="requirements"
                                          class="form-control @error('requirements') is-invalid @enderror"
                                          placeholder="{{ __('events.requirements_placeholder') }}"
                                          style="height: 80px"></textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('events.requirements_help') }}</div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
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

                            <!-- Public/Private -->
                            <div class="col-md-6 mb-3">
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
                                <div class="form-text">
                                    @if($is_public)
                                        <i class="ph ph-info text-info me-1"></i>{{ __('events.public_event_help') }}
                                    @else
                                        <i class="ph ph-info text-warning me-1"></i>{{ __('events.private_event_help') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ========================================
                     STEP 2: DATE & LOCATION
                     ======================================== -->
                @if($currentStep == 2)
                <div class="card" id="step-2">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-calendar-check me-2"></i>{{ __('events.step_date_location') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- ========================================
                                 DATE & TIME SECTION
                                 ======================================== -->
                            <div class="col-12 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="ph ph-calendar me-2 text-light-primary"></i>{{ __('events.date_and_time') }}
                                </h6>

                                <div class="row">
                                    <!-- Start DateTime -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text"
                                                   wire:model.live="start_datetime"
                                                   id="start_datetime"
                                                   class="form-control flatpickr-input @error('start_datetime') is-invalid @enderror"
                                                   placeholder="{{ __('events.start_datetime_placeholder') }}"
                                                   {{ !$is_availability_based ? 'required' : '' }}
                                                   readonly>
                                            <label for="start_datetime">{{ __('events.start_datetime') }} *</label>
                                        </div>
                                        @error('start_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- End DateTime -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text"
                                                   wire:model.live="end_datetime"
                                                   id="end_datetime"
                                                   class="form-control flatpickr-input @error('end_datetime') is-invalid @enderror"
                                                   placeholder="{{ __('events.end_datetime_placeholder') }}"
                                                   {{ !$is_availability_based ? 'required' : '' }}
                                                   readonly>
                                            <label for="end_datetime">{{ __('events.end_datetime') }} *</label>
                                        </div>
                                        @error('end_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Registration Deadline -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text"
                                                   wire:model.live="registration_deadline"
                                                   id="registration_deadline"
                                                   class="form-control flatpickr-input @error('registration_deadline') is-invalid @enderror"
                                                   placeholder="{{ __('events.registration_deadline_placeholder') }}"
                                                   readonly>
                                            <label for="registration_deadline">{{ __('events.registration_deadline') }}</label>
                                        </div>
                                        @error('registration_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="ph ph-info me-1"></i>{{ __('events.registration_deadline_help') }}
                                        </div>
                                    </div>

                                    <!-- Invitation Deadline -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text"
                                                   wire:model.live="invitation_deadline"
                                                   id="invitation_deadline"
                                                   class="form-control flatpickr-input @error('invitation_deadline') is-invalid @enderror"
                                                   placeholder="{{ __('events.invitation_deadline_placeholder') }}"
                                                   readonly>
                                            <label for="invitation_deadline">{{ __('events.invitation_deadline') }}</label>
                                        </div>
                                        @error('invitation_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="ph ph-info me-1"></i>{{ __('events.invitation_deadline_help') }}
                                        </div>
                                    </div>

                                    <!-- Availability Based Event -->
                                    <div class="col-12 mb-3">
                                        <div class="card card-light-info">
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox"
                                                           wire:model.live="is_availability_based"
                                                           class="form-check-input"
                                                           id="is_availability_based">
                                                    <label class="form-check-label fw-bold" for="is_availability_based">
                                                        <i class="ph ph-users-three me-1"></i>{{ __('events.availability_based_event') }}
                                                    </label>
                                                </div>
                                                <small class="text-muted">
                                                    {{ __('events.availability_based_help') }}
                                                </small>

                                                @if($is_availability_based)
                                                    <div class="row mt-3">
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-floating">
                                                                <input type="text"
                                                                       wire:model.live="availability_deadline"
                                                                       id="availability_deadline"
                                                                       class="form-control flatpickr-input"
                                                                       placeholder="{{ __('events.availability_deadline_placeholder') }}"
                                                                       readonly>
                                                                <label for="availability_deadline">{{ __('events.availability_deadline') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label small">{{ __('events.availability_instructions') }}</label>
                                                            <textarea wire:model.live="availability_instructions"
                                                                      class="form-control form-control-sm"
                                                                      rows="2"
                                                                      placeholder="{{ __('events.availability_instructions_placeholder') }}"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Sezione Date Multiple per Disponibilità -->
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0">
                                                                        <i class="ph ph-calendar-plus me-2"></i>{{ __('events.availability_multiple_dates') }}
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p class="text-muted mb-3">
                                                                        {{ __('events.availability_multiple_dates_help') }}
                                                                    </p>

                                                                    <!-- Lista opzioni di date -->
                                                                    <div id="availability-options-list">
                                                                        <!-- Le opzioni verranno aggiunte qui dinamicamente -->
                                                                    </div>

                                                                    <!-- Pulsante per aggiungere nuova data -->
                                                                    <div class="text-center mt-3">
                                                                        <button type="button" class="btn btn-primary"
                                                                                id="add-availability-option">
                                                                            <i class="ph ph-plus me-2"></i>{{ __('events.add_availability_option') }}
                                                                        </button>
                                                                    </div>

                                                                    <div class="alert alert-info mt-3">
                                                                        <i class="ph ph-info me-2"></i>
                                                                        <strong>{{ __('events.availability_multiple_dates_notice') }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ========================================
                                 LOCATION SECTION
                                 ======================================== -->
                            <div class="col-12 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="ph ph-map-pin me-2 text-light-primary"></i>{{ __('events.location') }}
                                </h6>

                                <!-- Online/In Person Toggle -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio"
                                                   class="btn-check"
                                                   wire:model.live="is_online"
                                                   value="0"
                                                   id="in_person"
                                                   checked>
                                            <label class="btn btn-light-primary" for="in_person">
                                                <i class="ph ph-map-pin me-2"></i>{{ __('events.in_person') }}
                                            </label>

                                            <input type="radio"
                                                   class="btn-check"
                                                   wire:model.live="is_online"
                                                   value="1"
                                                   id="online">
                                            <label class="btn btn-light-primary" for="online">
                                                <i class="ph ph-globe me-2"></i>{{ __('events.online') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- ONLINE EVENT FIELDS -->
                                @if($is_online)
                                    <div class="row">
                                        <!-- Online URL -->
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">{{ __('events.online_url') }} *</label>
                                            <input type="url"
                                                   wire:model.live="online_url"
                                                   class="form-control @error('online_url') is-invalid @enderror"
                                                   placeholder="https://zoom.us/..."
                                                   required>
                                            @error('online_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="ph ph-info me-1"></i>{{ __('events.online_url_help') }}
                                            </div>
                                        </div>

                                        <!-- Timezone -->
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('events.timezone') }} *</label>
                                            <select wire:model.live="timezone"
                                                    class="form-select @error('timezone') is-invalid @enderror"
                                                    required>
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
                                @else
                                    <!-- IN-PERSON EVENT FIELDS -->
                                    <div class="row">
                                        <!-- Venue Name -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ __('events.venue_name') }}</label>
                                            <input type="text"
                                                   wire:model.live="venue_name"
                                                   class="form-control @error('venue_name') is-invalid @enderror"
                                                   placeholder="{{ __('events.venue_name_placeholder') }}">
                                            @error('venue_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- City -->
                                        <div class="col-md-6 mb-3">
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

                                        <!-- Address -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">{{ __('events.venue_address') }}</label>
                                            <textarea id="venue-address-input"
                                                      name="venue_address"
                                                      class="form-control @error('venue_address') is-invalid @enderror"
                                                      rows="2"
                                                      placeholder="{{ __('events.venue_address_placeholder') }}">{{ $venue_address }}</textarea>
                                            @error('venue_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Postcode & Country -->
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('events.postcode') }}</label>
                                            <input type="text"
                                                   id="postcode-input"
                                                   name="postcode"
                                                   value="{{ $postcode }}"
                                                   class="form-control"
                                                   placeholder="{{ __('events.postcode_placeholder') }}">
                                        </div>

                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">{{ __('events.country') }}</label>
                                            <select id="country-select" name="country" class="form-select">
                                                <option value="IT" {{ $country == 'IT' ? 'selected' : '' }}>Italia</option>
                                                <option value="FR" {{ $country == 'FR' ? 'selected' : '' }}>France</option>
                                                <option value="DE" {{ $country == 'DE' ? 'selected' : '' }}>Deutschland</option>
                                                <option value="ES" {{ $country == 'ES' ? 'selected' : '' }}>España</option>
                                                <option value="GB" {{ $country == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                                <option value="US" {{ $country == 'US' ? 'selected' : '' }}>United States</option>
                                            </select>
                                        </div>

                                        <!-- Map -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">{{ __('events.map_location') }}</label>
                                            <div id="locationMap" class="border rounded" style="height: 300px;" wire:ignore></div>
                                            <small class="text-muted">{{ __('events.map_auto_positioning_help') }}</small>
                                            <div id="geocoding-status" class="small text-info mt-1" style="display: none;">
                                                <i class="ph ph-spinner-gap me-1"></i>
                                                {{ __('events.auto_positioning_status') }}
                                            </div>
                                        </div>

                                        <!-- Hidden coordinates -->
                                        <input type="hidden" wire:model="latitude" id="latitude">
                                        <input type="hidden" wire:model="longitude" id="longitude">
                                    </div>
                                @endif
                            </div>

                            <!-- ========================================
                                 RECURRENCE SECTION
                                 ======================================== -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="ph ph-arrow-clockwise me-2 text-light-primary"></i>{{ __('events.recurrence') }}
                                </h6>

                                <!-- Recurring Event Checkbox -->
                                <div class="form-check mb-3">
                                    <input type="checkbox"
                                           wire:model.live="is_recurring"
                                           class="form-check-input"
                                           id="is_recurring">
                                    <label class="form-check-label" for="is_recurring">
                                        <strong>{{ __('events.recurring_event') }}</strong>
                                    </label>
                                </div>

                                @if($is_recurring)
                                    <div class="card card-light-primary">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Recurrence Type -->
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('events.recurrence_type') }} *</label>
                                                    <select wire:model.live="recurrence_type"
                                                            class="form-select @error('recurrence_type') is-invalid @enderror"
                                                            required>
                                                        <option value="">{{ __('common.select') }}</option>
                                                        <option value="daily">{{ __('events.daily') }}</option>
                                                        <option value="weekly">{{ __('events.weekly') }}</option>
                                                        <option value="monthly">{{ __('events.monthly') }}</option>
                                                        <option value="yearly">{{ __('events.yearly') }}</option>
                                                    </select>
                                                    @error('recurrence_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Interval -->
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('events.recurrence_interval') }}</label>
                                                    <input type="number"
                                                           wire:model.live="recurrence_interval"
                                                           class="form-control"
                                                           min="1"
                                                           value="1">
                                                    <div class="form-text">{{ __('events.recurrence_interval_help') }}</div>
                                                </div>

                                                <!-- Count -->
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ __('events.recurrence_count') }}</label>
                                                    <input type="number"
                                                           wire:model.live="recurrence_count"
                                                           class="form-control"
                                                           min="1"
                                                           max="100"
                                                           placeholder="10">
                                                    <div class="form-text">{{ __('events.recurrence_count_help') }}</div>
                                                </div>

                                                <!-- Weekly: Weekdays -->
                                                @if($recurrence_type == 'weekly')
                                                    <div class="col-12">
                                                        <label class="form-label">{{ __('events.recurrence_weekdays') }} *</label>
                                                        <div class="row g-2">
                                                            @foreach(['1' => __('events.weekday_1'), '2' => __('events.weekday_2'), '3' => __('events.weekday_3'), '4' => __('events.weekday_4'), '5' => __('events.weekday_5'), '6' => __('events.weekday_6'), '7' => __('events.weekday_7')] as $day => $label)
                                                                <div class="col-md-3 col-sm-4 col-6">
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <input class="form-check-input"
                                                                               type="checkbox"
                                                                               wire:model.live="recurrence_weekdays"
                                                                               value="{{ $day }}"
                                                                               id="weekday_{{ $day }}">
                                                                        <label class="form-check-label fw-semibold" for="weekday_{{ $day }}">
                                                                            <i class="ph ph-calendar-check me-1 text-light-primary"></i>{{ $label }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @error('recurrence_weekdays')
                                                            <div class="text-danger small mt-1">
                                                                <i class="ph ph-warning me-1"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @endif

                                                <!-- Monthly: Day of month -->
                                                @if($recurrence_type == 'monthly')
                                                    <div class="col-12">
                                                        <label class="form-label">{{ __('events.recurrence_monthday') }}</label>
                                                        <input type="number"
                                                               wire:model.live="recurrence_monthday"
                                                               class="form-control"
                                                               min="1"
                                                               max="31"
                                                               placeholder="1">
                                                        <div class="form-text">{{ __('events.recurrence_monthday_help') }}</div>
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
                @endif

                <!-- ========================================
                     STEP 3: DETAILS
                     ======================================== -->
                @if($currentStep == 3)
                <div class="card" id="step-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-gear me-2"></i>{{ __('events.step_details') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="ph ph-info me-2"></i>{{ __('events.step') }} 3 {{ __('events.in_implementation') }}...
                        </div>
                    </div>
                </div>
                @endif

                <!-- ========================================
                     STEP 4: INVITATIONS & SETTINGS
                     ======================================== -->
                @if($currentStep == 4)
                <div class="card" id="step-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-users me-2"></i>{{ __('events.step_invitations') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="ph ph-info me-2"></i>{{ __('events.step') }} 4 {{ __('events.in_implementation') }}...
                        </div>
                    </div>
                </div>
                @endif

                <!-- ========================================
                     STEP 5: PREVIEW
                     ======================================== -->
                @if($currentStep == 5)
                <div class="card" id="step-5">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-eye me-2"></i>{{ __('events.event_preview') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h4>{{ __('events.preview_title') }}</h4>
                            <p><strong>{{ __('events.title') }}:</strong> {{ $title ?: 'Titolo evento' }}</p>
                            <p><strong>{{ __('events.description') }}:</strong> {{ $description ?: 'Descrizione evento...' }}</p>
                            <p><strong>{{ __('events.category') }}:</strong> {{ $category ? $categories[$category] : 'Non specificata' }}</p>
                            <p><strong>{{ __('events.event_type') }}:</strong> {{ $is_public ? __('events.public') : __('events.private') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ========================================
                     NAVIGATION BUTTONS
                     ======================================== -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            @if($currentStep > 1)
                                <button type="button"
                                        wire:click="prevStep"
                                        class="btn btn-outline-secondary">
                                    <i class="ph ph-arrow-left me-2"></i>{{ __('common.previous') }}
                                </button>
                            @else
                                <div></div>
                            @endif

                            @if($currentStep < $totalSteps)
                                <button type="button"
                                        wire:click="nextStep"
                                        class="btn btn-light-primary">
                                    {{ __('common.next') }}<i class="ph ph-arrow-right ms-2"></i>
                                </button>
                            @else
                                <button type="submit"
                                        class="btn btn-success">
                                    <i class="ph ph-check-circle me-2"></i>{{ __('events.create_event_button') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info-circle me-2"></i>{{ __('common.help') }}
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        {{ __('events.help_text_step_' . $currentStep) }}
                    </p>

                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">{{ __('common.progress') }}</small>
                            <small class="text-muted">{{ round(($currentStep / $totalSteps) * 100) }}%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-light-primary"
                                 role="progressbar"
                                 style="width: {{ ($currentStep / $totalSteps) * 100 }}%"
                                 aria-valuenow="{{ $currentStep }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $totalSteps }}">
                            </div>
                        </div>
                    </div>

                    <small class="text-muted d-block">
                        <i class="ph ph-check-circle text-success me-1"></i>
                        {{ __('events.step_info', ['current' => $currentStep, 'total' => $totalSteps]) }}
                    </small>

                    <!-- Step Status -->
                    <div class="mt-3">
                        <h6 class="small mb-2">{{ __('events.steps') }}</h6>
                        <div class="list-group list-group-flush">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <i class="ph ph-{{ $i < $currentStep ? 'check-circle text-light-success' : ($i == $currentStep ? 'arrow-right text-light-primary' : 'circle text-muted') }} me-2"></i>
                                    <small class="{{ $i <= $currentStep ? 'fw-bold' : 'text-muted' }}">
                                        {{ __('events.step') }} {{ $i }}
                                    </small>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
