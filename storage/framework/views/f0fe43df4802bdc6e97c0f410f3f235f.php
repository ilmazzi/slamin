<?php $__env->startSection('title', __('events.edit_event')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">
                                <i class="ph ph-house me-1"></i><?php echo e(__('common.dashboard')); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('events.index')); ?>" class="text-decoration-none">
                                <i class="ph ph-calendar me-1"></i><?php echo e(__('events.events')); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item active"><?php echo e(__('events.edit_event')); ?></li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ph ph-pencil-simple me-2"></i><?php echo e(__('events.edit_event')); ?>

                </h4>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph ph-calendar-plus me-2"></i><?php echo e(__('events.edit_event_details')); ?>

                        </h5>
                        <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-light">
                            <i class="ph ph-eye me-1"></i><?php echo e(__('common.view')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('events.update', $event)); ?>" method="POST" enctype="multipart/form-data" id="editEventForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Step 1: Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-info me-2"></i><?php echo e(__('events.basic_information')); ?>

                                </h6>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label"><?php echo e(__('events.title')); ?> *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="title" name="title" value="<?php echo e(old('title', $event->title)); ?>" required>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Toggle per Sottotitolo -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="subtitle-toggle" <?php echo e(old('subtitle', $event->subtitle) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="subtitle-toggle">
                                                <i class="ph ph-plus-circle me-2"></i><?php echo e(__('events.add_subtitle')); ?>

                                            </label>
                                        </div>
                                        <small class="text-muted">Opzionale</small>
                                    </div>
                                </div>

                                <!-- Campo Sottotitolo -->
                                <div class="mb-3" id="subtitle-field" style="display: <?php echo e(old('subtitle', $event->subtitle) ? 'block' : 'none'); ?>;">
                                    <label for="subtitle" class="form-label"><?php echo e(__('events.event_subtitle')); ?></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                        id="subtitle" name="subtitle" value="<?php echo e(old('subtitle', $event->subtitle)); ?>" placeholder="<?php echo e(__('events.subtitle_placeholder')); ?>">
                                    <small class="text-muted"><?php echo e(__('events.subtitle_help')); ?></small>
                                    <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label"><?php echo e(__('events.category')); ?> *</label>
                                    <select class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="category" name="category" required>
                                        <option value=""><?php echo e(__('events.select_category')); ?></option>
                                        <?php $__currentLoopData = App\Models\Event::getCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value); ?>" <?php echo e(old('category', $event->category) == $value ? 'selected' : ''); ?>>
                                                <?php echo e($label); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label"><?php echo e(__('events.description')); ?></label>
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="description" name="description" rows="4"><?php echo e(old('description', $event->description)); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Date and Location -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-map-pin me-2"></i><?php echo e(__('events.date_and_location')); ?>

                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_datetime" class="form-label"><?php echo e(__('events.start_datetime')); ?> *</label>
                                    <input type="datetime-local" class="form-control <?php $__errorArgs = ['start_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="start_datetime" name="start_datetime"
                                           value="<?php echo e(old('start_datetime', $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '')); ?>" required>
                                    <?php $__errorArgs = ['start_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_datetime" class="form-label"><?php echo e(__('events.end_datetime')); ?> *</label>
                                    <input type="datetime-local" class="form-control <?php $__errorArgs = ['end_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="end_datetime" name="end_datetime"
                                           value="<?php echo e(old('end_datetime', $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '')); ?>" required>
                                    <?php $__errorArgs = ['end_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="venue_name" class="form-label"><?php echo e(__('events.venue_name')); ?> *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['venue_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="venue_name" name="venue_name" value="<?php echo e(old('venue_name', $event->venue_name)); ?>" required>
                                    <?php $__errorArgs = ['venue_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label"><?php echo e(__('events.city')); ?> *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="city" name="city" value="<?php echo e(old('city', $event->city)); ?>" required>
                                    <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="venue_address" class="form-label"><?php echo e(__('events.venue_address')); ?> *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['venue_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="venue_address" name="venue_address" value="<?php echo e(old('venue_address', $event->venue_address)); ?>" required>
                                    <?php $__errorArgs = ['venue_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Hidden coordinates -->
                            <input type="hidden" id="latitude" name="latitude" value="<?php echo e(old('latitude', $event->latitude)); ?>">
                            <input type="hidden" id="longitude" name="longitude" value="<?php echo e(old('longitude', $event->longitude)); ?>">

                            <!-- Map -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo e(__('events.location_on_map')); ?></label>
                                    <div id="map" style="height: 300px; border-radius: 0.375rem;"></div>
                                    <small class="text-muted"><?php echo e(__('events.map_help')); ?></small>
                                </div>
                            </div>
                        </div>

                        <!-- Online Event Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-globe me-2"></i><?php echo e(__('events.online_event')); ?>

                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-light-info">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_online" id="is_online" class="form-check-input" value="1"
                                                   <?php echo e(old('is_online', $event->is_online) ? 'checked' : ''); ?>>
                                            <label for="is_online" class="form-check-label f-w-600">
                                                <i class="ph ph-globe me-2"></i><?php echo e(__('events.is_online')); ?>

                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body" id="online-event-settings" style="display: <?php echo e(old('is_online', $event->is_online) ? 'block' : 'none'); ?>;">
                                        <div class="row">
                                            <!-- Timezone -->
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <select name="timezone" id="timezone" class="form-select <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <option value=""><?php echo e(__('events.select_timezone')); ?></option>
                                                        <option value="Europe/Rome" <?php echo e(old('timezone', $event->timezone) == 'Europe/Rome' ? 'selected' : ''); ?>>Europe/Rome (UTC+1/+2)</option>
                                                        <option value="Europe/London" <?php echo e(old('timezone', $event->timezone) == 'Europe/London' ? 'selected' : ''); ?>>Europe/London (UTC+0/+1)</option>
                                                        <option value="Europe/Paris" <?php echo e(old('timezone', $event->timezone) == 'Europe/Paris' ? 'selected' : ''); ?>>Europe/Paris (UTC+1/+2)</option>
                                                        <option value="Europe/Berlin" <?php echo e(old('timezone', $event->timezone) == 'Europe/Berlin' ? 'selected' : ''); ?>>Europe/Berlin (UTC+1/+2)</option>
                                                        <option value="Europe/Madrid" <?php echo e(old('timezone', $event->timezone) == 'Europe/Madrid' ? 'selected' : ''); ?>>Europe/Madrid (UTC+1/+2)</option>
                                                        <option value="Europe/Amsterdam" <?php echo e(old('timezone', $event->timezone) == 'Europe/Amsterdam' ? 'selected' : ''); ?>>Europe/Amsterdam (UTC+1/+2)</option>
                                                        <option value="Europe/Brussels" <?php echo e(old('timezone', $event->timezone) == 'Europe/Brussels' ? 'selected' : ''); ?>>Europe/Brussels (UTC+1/+2)</option>
                                                        <option value="Europe/Vienna" <?php echo e(old('timezone', $event->timezone) == 'Europe/Vienna' ? 'selected' : ''); ?>>Europe/Vienna (UTC+1/+2)</option>
                                                        <option value="Europe/Zurich" <?php echo e(old('timezone', $event->timezone) == 'Europe/Zurich' ? 'selected' : ''); ?>>Europe/Zurich (UTC+1/+2)</option>
                                                        <option value="Europe/Stockholm" <?php echo e(old('timezone', $event->timezone) == 'Europe/Stockholm' ? 'selected' : ''); ?>>Europe/Stockholm (UTC+1/+2)</option>
                                                        <option value="Europe/Oslo" <?php echo e(old('timezone', $event->timezone) == 'Europe/Oslo' ? 'selected' : ''); ?>>Europe/Oslo (UTC+1/+2)</option>
                                                        <option value="Europe/Copenhagen" <?php echo e(old('timezone', $event->timezone) == 'Europe/Copenhagen' ? 'selected' : ''); ?>>Europe/Copenhagen (UTC+1/+2)</option>
                                                        <option value="Europe/Helsinki" <?php echo e(old('timezone', $event->timezone) == 'Europe/Helsinki' ? 'selected' : ''); ?>>Europe/Helsinki (UTC+2/+3)</option>
                                                        <option value="Europe/Warsaw" <?php echo e(old('timezone', $event->timezone) == 'Europe/Warsaw' ? 'selected' : ''); ?>>Europe/Warsaw (UTC+1/+2)</option>
                                                        <option value="Europe/Prague" <?php echo e(old('timezone', $event->timezone) == 'Europe/Prague' ? 'selected' : ''); ?>>Europe/Prague (UTC+1/+2)</option>
                                                        <option value="Europe/Budapest" <?php echo e(old('timezone', $event->timezone) == 'Europe/Budapest' ? 'selected' : ''); ?>>Europe/Budapest (UTC+1/+2)</option>
                                                        <option value="Europe/Bucharest" <?php echo e(old('timezone', $event->timezone) == 'Europe/Bucharest' ? 'selected' : ''); ?>>Europe/Bucharest (UTC+2/+3)</option>
                                                        <option value="Europe/Sofia" <?php echo e(old('timezone', $event->timezone) == 'Europe/Sofia' ? 'selected' : ''); ?>>Europe/Sofia (UTC+2/+3)</option>
                                                        <option value="Europe/Zagreb" <?php echo e(old('timezone', $event->timezone) == 'Europe/Zagreb' ? 'selected' : ''); ?>>Europe/Zagreb (UTC+1/+2)</option>
                                                        <option value="Europe/Ljubljana" <?php echo e(old('timezone', $event->timezone) == 'Europe/Ljubljana' ? 'selected' : ''); ?>>Europe/Ljubljana (UTC+1/+2)</option>
                                                        <option value="Europe/Athens" <?php echo e(old('timezone', $event->timezone) == 'Europe/Athens' ? 'selected' : ''); ?>>Europe/Athens (UTC+2/+3)</option>
                                                        <option value="Europe/Nicosia" <?php echo e(old('timezone', $event->timezone) == 'Europe/Nicosia' ? 'selected' : ''); ?>>Europe/Nicosia (UTC+2/+3)</option>
                                                        <option value="Europe/Valletta" <?php echo e(old('timezone', $event->timezone) == 'Europe/Valletta' ? 'selected' : ''); ?>>Europe/Valletta (UTC+1/+2)</option>
                                                        <option value="America/New_York" <?php echo e(old('timezone', $event->timezone) == 'America/New_York' ? 'selected' : ''); ?>>America/New_York (UTC-5/-4)</option>
                                                        <option value="America/Chicago" <?php echo e(old('timezone', $event->timezone) == 'America/Chicago' ? 'selected' : ''); ?>>America/Chicago (UTC-6/-5)</option>
                                                        <option value="America/Denver" <?php echo e(old('timezone', $event->timezone) == 'America/Denver' ? 'selected' : ''); ?>>America/Denver (UTC-7/-6)</option>
                                                        <option value="America/Los_Angeles" <?php echo e(old('timezone', $event->timezone) == 'America/Los_Angeles' ? 'selected' : ''); ?>>America/Los_Angeles (UTC-8/-7)</option>
                                                        <option value="America/Toronto" <?php echo e(old('timezone', $event->timezone) == 'America/Toronto' ? 'selected' : ''); ?>>America/Toronto (UTC-5/-4)</option>
                                                        <option value="America/Vancouver" <?php echo e(old('timezone', $event->timezone) == 'America/Vancouver' ? 'selected' : ''); ?>>America/Vancouver (UTC-8/-7)</option>
                                                        <option value="America/Mexico_City" <?php echo e(old('timezone', $event->timezone) == 'America/Mexico_City' ? 'selected' : ''); ?>>America/Mexico_City (UTC-6/-5)</option>
                                                        <option value="America/Sao_Paulo" <?php echo e(old('timezone', $event->timezone) == 'America/Sao_Paulo' ? 'selected' : ''); ?>>America/Sao_Paulo (UTC-3/-2)</option>
                                                        <option value="America/Buenos_Aires" <?php echo e(old('timezone', $event->timezone) == 'America/Buenos_Aires' ? 'selected' : ''); ?>>America/Buenos_Aires (UTC-3)</option>
                                                        <option value="America/Santiago" <?php echo e(old('timezone', $event->timezone) == 'America/Santiago' ? 'selected' : ''); ?>>America/Santiago (UTC-3/-4)</option>
                                                        <option value="Australia/Sydney" <?php echo e(old('timezone', $event->timezone) == 'Australia/Sydney' ? 'selected' : ''); ?>>Australia/Sydney (UTC+10/+11)</option>
                                                        <option value="Australia/Melbourne" <?php echo e(old('timezone', $event->timezone) == 'Australia/Melbourne' ? 'selected' : ''); ?>>Australia/Melbourne (UTC+10/+11)</option>
                                                        <option value="Australia/Perth" <?php echo e(old('timezone', $event->timezone) == 'Australia/Perth' ? 'selected' : ''); ?>>Australia/Perth (UTC+8)</option>
                                                        <option value="Pacific/Auckland" <?php echo e(old('timezone', $event->timezone) == 'Pacific/Auckland' ? 'selected' : ''); ?>>Pacific/Auckland (UTC+12/+13)</option>
                                                        <option value="Asia/Tokyo" <?php echo e(old('timezone', $event->timezone) == 'Asia/Tokyo' ? 'selected' : ''); ?>>Asia/Tokyo (UTC+9)</option>
                                                        <option value="Asia/Seoul" <?php echo e(old('timezone', $event->timezone) == 'Asia/Seoul' ? 'selected' : ''); ?>>Asia/Seoul (UTC+9)</option>
                                                        <option value="Asia/Shanghai" <?php echo e(old('timezone', $event->timezone) == 'Asia/Shanghai' ? 'selected' : ''); ?>>Asia/Shanghai (UTC+8)</option>
                                                        <option value="Asia/Singapore" <?php echo e(old('timezone', $event->timezone) == 'Asia/Singapore' ? 'selected' : ''); ?>>Asia/Singapore (UTC+8)</option>
                                                        <option value="Asia/Kuala_Lumpur" <?php echo e(old('timezone', $event->timezone) == 'Asia/Kuala_Lumpur' ? 'selected' : ''); ?>>Asia/Kuala_Lumpur (UTC+8)</option>
                                                        <option value="Asia/Jakarta" <?php echo e(old('timezone', $event->timezone) == 'Asia/Jakarta' ? 'selected' : ''); ?>>Asia/Jakarta (UTC+7)</option>
                                                        <option value="Asia/Manila" <?php echo e(old('timezone', $event->timezone) == 'Asia/Manila' ? 'selected' : ''); ?>>Asia/Manila (UTC+8)</option>
                                                        <option value="Asia/Bangkok" <?php echo e(old('timezone', $event->timezone) == 'Asia/Bangkok' ? 'selected' : ''); ?>>Asia/Bangkok (UTC+7)</option>
                                                        <option value="Asia/Ho_Chi_Minh" <?php echo e(old('timezone', $event->timezone) == 'Asia/Ho_Chi_Minh' ? 'selected' : ''); ?>>Asia/Ho_Chi_Minh (UTC+7)</option>
                                                        <option value="Asia/Dubai" <?php echo e(old('timezone', $event->timezone) == 'Asia/Dubai' ? 'selected' : ''); ?>>Asia/Dubai (UTC+4)</option>
                                                        <option value="Asia/Riyadh" <?php echo e(old('timezone', $event->timezone) == 'Asia/Riyadh' ? 'selected' : ''); ?>>Asia/Riyadh (UTC+3)</option>
                                                        <option value="Asia/Kolkata" <?php echo e(old('timezone', $event->timezone) == 'Asia/Kolkata' ? 'selected' : ''); ?>>Asia/Kolkata (UTC+5:30)</option>
                                                        <option value="Asia/Tehran" <?php echo e(old('timezone', $event->timezone) == 'Asia/Tehran' ? 'selected' : ''); ?>>Asia/Tehran (UTC+3:30/+4:30)</option>
                                                        <option value="Asia/Jerusalem" <?php echo e(old('timezone', $event->timezone) == 'Asia/Jerusalem' ? 'selected' : ''); ?>>Asia/Jerusalem (UTC+2/+3)</option>
                                                        <option value="Africa/Cairo" <?php echo e(old('timezone', $event->timezone) == 'Africa/Cairo' ? 'selected' : ''); ?>>Africa/Cairo (UTC+2/+3)</option>
                                                        <option value="Africa/Johannesburg" <?php echo e(old('timezone', $event->timezone) == 'Africa/Johannesburg' ? 'selected' : ''); ?>>Africa/Johannesburg (UTC+2)</option>
                                                        <option value="Africa/Lagos" <?php echo e(old('timezone', $event->timezone) == 'Africa/Lagos' ? 'selected' : ''); ?>>Africa/Lagos (UTC+1)</option>
                                                        <option value="Africa/Nairobi" <?php echo e(old('timezone', $event->timezone) == 'Africa/Nairobi' ? 'selected' : ''); ?>>Africa/Nairobi (UTC+3)</option>
                                                        <option value="Africa/Casablanca" <?php echo e(old('timezone', $event->timezone) == 'Africa/Casablanca' ? 'selected' : ''); ?>>Africa/Casablanca (UTC+0/+1)</option>
                                                        <option value="Africa/Tunis" <?php echo e(old('timezone', $event->timezone) == 'Africa/Tunis' ? 'selected' : ''); ?>>Africa/Tunis (UTC+1/+2)</option>
                                                        <option value="Africa/Algiers" <?php echo e(old('timezone', $event->timezone) == 'Africa/Algiers' ? 'selected' : ''); ?>>Africa/Algiers (UTC+1)</option>
                                                    </select>
                                                    <label for="timezone"><?php echo e(__('events.timezone')); ?> *</label>
                                                </div>
                                                <small class="text-muted"><?php echo e(__('events.timezone_help')); ?></small>
                                                <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>

                                            <!-- Online URL -->
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="url" name="online_url" id="online_url" class="form-control <?php $__errorArgs = ['online_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           placeholder="<?php echo e(__('events.online_url_placeholder')); ?>"
                                                           value="<?php echo e(old('online_url', $event->online_url)); ?>">
                                                    <label for="online_url"><?php echo e(__('events.online_url')); ?></label>
                                                </div>
                                                <small class="text-muted"><?php echo e(__('events.online_url_help')); ?></small>
                                                <?php $__errorArgs = ['online_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>

                                        <!-- Online Event Notice -->
                                        <div class="alert alert-info">
                                            <i class="ph ph-info me-2"></i>
                                            <strong><?php echo e(__('events.online_event_notice')); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-gear me-2"></i><?php echo e(__('events.settings')); ?>

                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_participants" class="form-label"><?php echo e(__('events.max_participants')); ?></label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="max_participants" name="max_participants"
                                           value="<?php echo e(old('max_participants', $event->max_participants)); ?>" min="1">
                                    <small class="text-muted"><?php echo e(__('events.max_participants_help')); ?></small>
                                    <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input <?php $__errorArgs = ['accepts_requests'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               type="checkbox" id="accepts_requests" name="accepts_requests" value="1"
                                               <?php echo e(old('accepts_requests', $event->accepts_requests) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="accepts_requests">
                                            <?php echo e(__('events.accepts_requests')); ?>

                                        </label>
                                        <?php $__errorArgs = ['accepts_requests'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_linked_to_group" id="is_linked_to_group" class="form-check-input" value="1" <?php echo e(old('is_linked_to_group', $event->group_id ? '1' : '0') ? 'checked' : ''); ?>>
                                        <label for="is_linked_to_group" class="form-check-label">
                                            <strong><?php echo e(__('events.is_linked_to_group')); ?></strong>
                                        </label>
                                    </div>
                                    <div id="groupFields" style="display: <?php echo e(old('is_linked_to_group', $event->group_id ? '1' : '0') ? 'block' : 'none'); ?>;">
                                        <select class="form-select <?php $__errorArgs = ['group_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="group_id" name="group_id">
                                            <option value=""><?php echo e(__('events.select_group')); ?></option>
                                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($group->id); ?>" <?php echo e(old('group_id', $event->group_id) == $group->id ? 'selected' : ''); ?>>
                                                    <?php echo e($group->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <small class="text-muted"><?php echo e(__('events.group_help')); ?></small>
                                        <?php $__errorArgs = ['group_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Image -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-image me-2"></i><?php echo e(__('events.event_image')); ?>

                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label"><?php echo e(__('events.new_image')); ?></label>
                                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="image" name="image" accept="image/*">
                                    <small class="text-muted"><?php echo e(__('events.image_help')); ?></small>
                                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <?php if($event->image_url): ?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo e(__('events.current_image')); ?></label>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo e($event->image_url); ?>" alt="<?php echo e($event->title); ?>"
                                             class="img-thumbnail me-3" style="max-width: 100px; max-height: 100px;">
                                        <div>
                                            <small class="text-muted d-block"><?php echo e(__('events.current_image_help')); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Festival Events Management -->
                        <?php if($event->isFestival()): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-trophy me-2"></i><?php echo e(__('events.festival_events')); ?>

                                </h6>
                                <div class="alert alert-border-primary" role="alert">
                                    <h6>
                                        <i class="ph ph-info-circle f-s-18 me-2 text-primary"></i>
                                        <?php echo e(__('events.festival_events_help')); ?>

                                    </h6>
                                    <p class="mb-0">
                                        Gestisci gli eventi che fanno parte di questo festival. Puoi aggiungere o rimuovere eventi esistenti.
                                    </p>
                                </div>

                                <!-- Current Festival Events -->
                                <div class="mb-4">
                                    <h6 class="mb-3"><?php echo e(__('events.current_festival_events')); ?></h6>
                                    <?php
                                        $currentFestivalEvents = $event->getFestivalEventModels();
                                    ?>
                                    <?php if($currentFestivalEvents->count() > 0): ?>
                                        <div class="row" id="currentFestivalEventsList">
                                            <?php $__currentLoopData = $currentFestivalEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $festivalEvent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-6 mb-3" data-event-id="<?php echo e($festivalEvent->id); ?>">
                                                    <div class="card card-light-primary border-0">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                                    <i class="ph ph-calendar f-s-18"></i>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1 fw-bold"><?php echo e($festivalEvent->title); ?></h6>
                                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                                        <span class="badge bg-primary"><?php echo e($festivalEvent->start_datetime->format('d/m/Y')); ?></span>
                                                                        <span class="badge bg-light-secondary"><?php echo e($festivalEvent->city); ?></span>
                                                                    </div>
                                                                    <small class="text-muted">
                                                                        <i class="ph ph-user me-1"></i><?php echo e($festivalEvent->organizer->getDisplayName()); ?>

                                                                    </small>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEventFromFestival(<?php echo e($festivalEvent->id); ?>)">
                                                                        <i class="ph ph-trash me-1"></i><?php echo e(__('events.remove')); ?>

                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-3">
                                            <i class="ph ph-calendar-x display-4 text-muted mb-3"></i>
                                            <p class="text-muted mb-0"><?php echo e(__('events.no_festival_events')); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Add New Events to Festival -->
                                <div class="mb-4">
                                    <h6 class="mb-3"><?php echo e(__('events.add_events_to_festival')); ?></h6>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="eventSearchInputEdit"
                                                       placeholder="<?php echo e(__('events.search_events_placeholder')); ?>">
                                                <button class="btn btn-outline-primary" type="button" onclick="searchEventsForFestivalEdit()">
                                                    <i class="ph ph-magnifying-glass me-1"></i><?php echo e(__('events.search')); ?>

                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Search Results -->
                                    <div class="mt-3" id="searchResultsEventsEdit" style="display: none;">
                                        <h6 class="mb-3"><?php echo e(__('events.search_results')); ?></h6>
                                        <div id="searchResultsListEventsEdit"></div>
                                    </div>
                                </div>

                                <!-- Hidden input for festival events data -->
                                <input type="hidden" name="selected_festival_events" id="selectedFestivalEventsDataEdit"
                                       value="<?php echo e(json_encode($event->getFestivalEventIds())); ?>">
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-light">
                                        <i class="ph ph-arrow-left me-1"></i><?php echo e(__('common.cancel')); ?>

                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph ph-check me-1"></i><?php echo e(__('common.update')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let map, marker;

// Online event functionality
document.addEventListener('DOMContentLoaded', function() {
    const isOnlineCheckbox = document.getElementById('is_online');
    const onlineEventSettings = document.getElementById('online-event-settings');
    const locationFields = ['venue_name', 'venue_address', 'city'];
    const mapContainer = document.getElementById('map');

    if (isOnlineCheckbox && onlineEventSettings) {
        isOnlineCheckbox.addEventListener('change', function() {
            if (this.checked) {
                onlineEventSettings.style.display = 'block';
                makeLocationFieldsOptional();
            } else {
                onlineEventSettings.style.display = 'none';
                makeLocationFieldsRequired();
            }
        });
    }

    // Funzione per rendere i campi del luogo opzionali
    function makeLocationFieldsOptional() {
        locationFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.required = false;
                // Nascondi il campo e il suo container
                const fieldContainer = field.closest('.col-12, .col-md-6, .col-md-3, .col-md-4');
                if (fieldContainer) {
                    fieldContainer.style.display = 'none';
                }
                // Rimuovi l'asterisco dal label
                const label = field.parentElement.querySelector('label');
                if (label) {
                    label.textContent = label.textContent.replace(' *', '');
                }
            }
        });

        // Nascondi la mappa per eventi online
        if (mapContainer) {
            mapContainer.style.display = 'none';
        }

        // Nascondi anche il container della mappa
        const mapSection = mapContainer?.closest('.col-12');
        if (mapSection) {
            mapSection.style.display = 'none';
        }
    }

    // Funzione per rendere i campi del luogo obbligatori
    function makeLocationFieldsRequired() {
        locationFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.required = true;
                // Mostra il campo e il suo container
                const fieldContainer = field.closest('.col-12, .col-md-6, .col-md-3, .col-md-4');
                if (fieldContainer) {
                    fieldContainer.style.display = 'block';
                }
                // Aggiungi l'asterisco al label
                const label = field.parentElement.querySelector('label');
                if (label && !label.textContent.includes('*')) {
                    label.textContent += ' *';
                }
            }
        });

        // Mostra la mappa per eventi fisici
        if (mapContainer) {
            mapContainer.style.display = 'block';
        }

        // Mostra anche il container della mappa
        const mapSection = mapContainer?.closest('.col-12');
        if (mapSection) {
            mapSection.style.display = 'block';
        }
    }

    // Gestione evento legato a gruppo
    const isLinkedToGroup = document.getElementById('is_linked_to_group');
    const groupFields = document.getElementById('groupFields');
    if (isLinkedToGroup && groupFields) {
        isLinkedToGroup.addEventListener('change', function() {
            groupFields.style.display = this.checked ? 'block' : 'none';
        });
    }

    // ========================================
    // GESTIONE TOGGLE SOTTOTITOLO
    // ========================================
    const subtitleToggle = document.getElementById('subtitle-toggle');
    const subtitleField = document.getElementById('subtitle-field');
    const subtitleInput = document.getElementById('subtitle');

    if (subtitleToggle && subtitleField) {
        subtitleToggle.addEventListener('change', function() {
            if (this.checked) {
                subtitleField.style.display = 'block';
                subtitleInput.focus();
            } else {
                subtitleField.style.display = 'none';
                subtitleInput.value = ''; // Pulisce il campo quando si disattiva
            }
        });
    }

    // Inizializza lo stato dei campi in base al valore corrente
    if (isOnlineCheckbox && isOnlineCheckbox.checked) {
        makeLocationFieldsOptional();
    }

    // Inizializza la mappa e la validazione del form
    initializeMap();
    setupFormValidation();
});

function initializeMap() {
    // Initialize map
    map = L.map('map').setView([<?php echo e($event->latitude ?? 41.9028); ?>, <?php echo e($event->longitude ?? 12.4964); ?>], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker if coordinates exist
    if (<?php echo e($event->latitude ?? 'null'); ?> && <?php echo e($event->longitude ?? 'null'); ?>) {
        marker = L.marker([<?php echo e($event->latitude); ?>, <?php echo e($event->longitude); ?>]).addTo(map);
    }

    // Handle map clicks
    map.on('click', function(e) {
        setMapLocation(e.latlng.lat, e.latlng.lng);
    });
}

function setMapLocation(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);
}

function setupFormValidation() {
    const form = document.getElementById('editEventForm');

    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Check if event is online
        const isOnline = document.getElementById('is_online').checked;

        // Validate required fields based on event type
        let requiredFields = ['title', 'category', 'start_datetime', 'end_datetime'];

        if (!isOnline) {
            // For physical events, require location fields
            requiredFields = requiredFields.concat(['venue_name', 'venue_address', 'city']);
        } else {
            // For online events, require timezone
            requiredFields.push('timezone');
        }

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });

        // Validate dates
        const startDate = document.getElementById('start_datetime').value;
        const endDate = document.getElementById('end_datetime').value;

        if (startDate && endDate && new Date(startDate) >= new Date(endDate)) {
            document.getElementById('end_datetime').classList.add('is-invalid');
            isValid = false;
        }

        // Validate online URL if provided
        const onlineUrl = document.getElementById('online_url');
        if (onlineUrl && onlineUrl.value.trim()) {
            try {
                new URL(onlineUrl.value);
            } catch (e) {
                onlineUrl.classList.add('is-invalid');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('<?php echo e(__("events.please_correct_errors")); ?>');
        }
    });
}

// Festival Events Management Functions
let selectedFestivalEventsEdit = [];

// Initialize selected events from current data
document.addEventListener('DOMContentLoaded', function() {
    const hiddenInput = document.getElementById('selectedFestivalEventsDataEdit');
    if (hiddenInput && hiddenInput.value) {
        try {
            selectedFestivalEventsEdit = JSON.parse(hiddenInput.value);
        } catch (e) {
            console.error('Error parsing festival events data:', e);
            selectedFestivalEventsEdit = [];
        }
    }
});

// Search events for festival (edit mode)
function searchEventsForFestivalEdit() {
    const searchInput = document.getElementById('eventSearchInputEdit');
    const searchTerm = searchInput.value.trim();

    if (!searchTerm) {
        alert('Inserisci un termine di ricerca');
        return;
    }

    :', searchTerm);

    // Mostra indicatore di caricamento
    const resultsSection = document.getElementById('searchResultsEventsEdit');
    const resultsContainer = document.getElementById('searchResultsListEventsEdit');
    if (resultsSection && resultsContainer) {
        resultsContainer.innerHTML = '<div class="text-center text-muted py-3"><i class="ph ph-spinner-gap me-2"></i>Ricerca in corso...</div>';
        resultsSection.style.display = 'block';
    }

    // Chiamata API per ricerca eventi
    fetch(`/api/events/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        :', data);
        displayEventSearchResultsEdit(data.events || []);
    })
    .catch(error => {
        console.error('Errore nella ricerca eventi (edit):', error);
        // Fallback con dati di esempio
        const sampleResults = [
            { id: 1, title: 'Slam Poetry Night', date: '15/03/2024', venue: 'Teatro Comunale' },
            { id: 2, title: 'Poetry Workshop', date: '16/03/2024', venue: 'Biblioteca Civica' },
            { id: 3, title: 'Open Mic Poetry', date: '17/03/2024', venue: 'Caffè Letterario' }
        ];
        displayEventSearchResultsEdit(sampleResults);
    });
}

// Display search results (edit mode)
function displayEventSearchResultsEdit(events) {
    const resultsContainer = document.getElementById('searchResultsListEventsEdit');
    if (!resultsContainer) return;

    if (events.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center text-muted py-3">Nessun evento trovato</div>';
        return;
    }

    let html = '';
    events.forEach(event => {
        const isAlreadyAdded = selectedFestivalEventsEdit.some(e => e.id === event.id);
        const isCurrentEvent = <?php echo e($event->id); ?> === event.id;

        if (!isAlreadyAdded && !isCurrentEvent) {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card card-light-secondary border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    <i class="ph ph-calendar f-s-18"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">${event.title}</h6>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-secondary">${event.date}</span>
                                        <span class="badge bg-light-secondary">${event.venue}</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="ph ph-user me-1"></i>${event.organizer || 'Organizzatore non specificato'}
                                    </small>
                                </div>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEventToFestivalEdit(${event.id}, '${event.title}', '${event.date}', '${event.venue}')">
                                        <i class="ph ph-plus me-1"></i><?php echo e(__('events.add')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    });

    if (html === '') {
        resultsContainer.innerHTML = '<div class="text-center text-muted py-3">Tutti gli eventi trovati sono già aggiunti al festival</div>';
    } else {
        resultsContainer.innerHTML = `<div class="row">${html}</div>`;
    }
}

// Add event to festival (edit mode)
function addEventToFestivalEdit(eventId, title, date, venue) {
    const eventData = { id: eventId, title, date, venue };

    // Check if already added
    if (selectedFestivalEventsEdit.some(e => e.id === eventId)) {
        alert('Questo evento è già aggiunto al festival');
        return;
    }

    // Add to selected events
    selectedFestivalEventsEdit.push(eventData);

    // Update hidden input
    updateSelectedFestivalEventsInputEdit();

    // Add to current events list
    addEventToCurrentListEdit(eventData);

    // Hide search results
    document.getElementById('searchResultsEventsEdit').style.display = 'none';

    :', eventData);
}

// Remove event from festival (edit mode)
function removeEventFromFestival(eventId) {
    // Remove from selected events
    selectedFestivalEventsEdit = selectedFestivalEventsEdit.filter(e => e.id !== eventId);

    // Update hidden input
    updateSelectedFestivalEventsInputEdit();

    // Remove from current events list
    const eventElement = document.querySelector(`[data-event-id="${eventId}"]`);
    if (eventElement) {
        eventElement.remove();
    }

    // Check if no events left
    const currentList = document.getElementById('currentFestivalEventsList');
    if (currentList && currentList.children.length === 0) {
        currentList.innerHTML = `
            <div class="col-12">
                <div class="text-center py-3">
                    <i class="ph ph-calendar-x display-4 text-muted mb-3"></i>
                    <p class="text-muted mb-0"><?php echo e(__('events.no_festival_events')); ?></p>
                </div>
            </div>
        `;
    }

    :', eventId);
}

// Add event to current list (edit mode)
function addEventToCurrentListEdit(eventData) {
    const currentList = document.getElementById('currentFestivalEventsList');
    if (!currentList) return;

    // Remove "no events" message if present
    const noEventsMessage = currentList.querySelector('.col-12 .text-center');
    if (noEventsMessage) {
        noEventsMessage.remove();
    }

    const eventHtml = `
        <div class="col-md-6 mb-3" data-event-id="${eventData.id}">
            <div class="card card-light-primary border-0">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="ph ph-calendar f-s-18"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">${eventData.title}</h6>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary">${eventData.date}</span>
                                <span class="badge bg-light-secondary">${eventData.venue}</span>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEventFromFestival(${eventData.id})">
                                <i class="ph ph-trash me-1"></i><?php echo e(__('events.remove')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    currentList.insertAdjacentHTML('beforeend', eventHtml);
}

// Update hidden input with selected events (edit mode)
function updateSelectedFestivalEventsInputEdit() {
    const hiddenInput = document.getElementById('selectedFestivalEventsDataEdit');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(selectedFestivalEventsEdit);
        :', selectedFestivalEventsEdit);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/events/edit.blade.php ENDPATH**/ ?>