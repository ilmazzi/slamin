@extends('layout.master')

@section('title', 'Modifica Profilo - Slamin')

@section('css')
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('profile.edit_profile') }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('profile.show') }}" class="f-s-14 f-w-500">{{ __('profile.breadcrumb_profile') }}</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('profile.edit_profile') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Form -->
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600 text-dark">
                        <i class="ph ph-user-edit me-2"></i>
                        Informazioni Personali
                    </h5>
                </div>
                <div class="card-body pa-30">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Info -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">Nome Completo *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">Nickname</label>
                                    <input type="text" class="form-control @error('nickname') is-invalid @enderror"
                                           name="nickname" value="{{ old('nickname', $user->nickname) }}"
                                           placeholder="Il tuo nome d'arte">
                                    @error('nickname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted f-s-12">Nome che apparirà nel tuo profilo pubblico</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">Telefono</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label f-w-600">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      name="bio" rows="4"
                                      placeholder="Racconta qualcosa di te, la tua passione per la poesia, i tuoi interessi...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted f-s-12">Massimo 1000 caratteri</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">Località</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                           name="location" value="{{ old('location', $user->location) }}"
                                           placeholder="Città, Regione">
                                    @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">Sito Web</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror"
                                           name="website" value="{{ old('website', $user->website) }}"
                                           placeholder="https://tuosito.com">
                                    @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="app-divider-v">
                            <span class="text-primary f-w-600">Social Media</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">
                                        <i class="ph ph-facebook-logo text-primary me-2"></i>Facebook
                                    </label>
                                    <input type="url" class="form-control @error('social_facebook') is-invalid @enderror"
                                           name="social_facebook" value="{{ old('social_facebook', $user->social_facebook) }}"
                                           placeholder="https://facebook.com/tuoprofilo">
                                    @error('social_facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">
                                        <i class="ph ph-instagram-logo text-danger me-2"></i>Instagram
                                    </label>
                                    <input type="url" class="form-control @error('social_instagram') is-invalid @enderror"
                                           name="social_instagram" value="{{ old('social_instagram', $user->social_instagram) }}"
                                           placeholder="https://instagram.com/tuoprofilo">
                                    @error('social_instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">
                                        <i class="ph ph-youtube-logo text-danger me-2"></i>YouTube
                                    </label>
                                    <input type="url" class="form-control @error('social_youtube') is-invalid @enderror"
                                           name="social_youtube" value="{{ old('social_youtube', $user->social_youtube) }}"
                                           placeholder="https://youtube.com/@tuocanale">
                                    @error('social_youtube')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label f-w-600">
                                        <i class="ph ph-twitter-logo text-info me-2"></i>Twitter/X
                                    </label>
                                    <input type="url" class="form-control @error('social_twitter') is-invalid @enderror"
                                           name="social_twitter" value="{{ old('social_twitter', $user->social_twitter) }}"
                                           placeholder="https://twitter.com/tuoprofilo">
                                    @error('social_twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary hover-effect">
                                <i class="ph ph-x me-2"></i>Annulla
                            </a>
                            <button type="submit" class="btn btn-primary hover-effect">
                                <i class="ph ph-check me-2"></i>Salva Modifiche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Photo Section -->
        <div class="col-lg-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600 text-dark">
                        <i class="ph ph-camera me-2"></i>
                        Foto Profilo
                    </h5>
                </div>
                <div class="card-body pa-30 text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="bg-light-primary h-150 w-150 d-flex-center b-r-50 position-relative overflow-hidden">
                            <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="img-fluid b-r-50">
                        </div>
                        <div class="position-absolute bottom-0 end-0">
                            <button class="btn btn-primary btn-sm rounded-circle" onclick="document.getElementById('profile-photo-input').click()">
                                <i class="ph ph-camera f-s-14"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600">Carica Nuova Foto</label>
                        <input type="file" id="profile-photo-input" name="profile_photo" class="form-control" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted f-s-12">Formati supportati: Tutti i formati immagine (JPG, PNG, GIF, WebP, ecc.). Max {{ \App\Models\SystemSetting::get('profile_photo_max_size', 5120) / 1024 }}MB</small>
                    </div>

                    <div class="text-start">
                        <h6 class="f-w-600 mb-2">Suggerimenti per una buona foto:</h6>
                        <ul class="text-muted f-s-12">
                            <li>Usa una foto chiara e ben illuminata</li>
                            <li>Mostra il tuo viso chiaramente</li>
                            <li>Evita foto troppo scure o sfocate</li>
                            <li>Formato quadrato funziona meglio</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card hover-effect mt-4">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600 text-dark">
                        <i class="ph ph-lightning me-2"></i>
                        Azioni Rapide
                    </h5>
                </div>
                <div class="card-body pa-20">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('profile.videos') }}" class="btn btn-success hover-effect">
                            <i class="ph ph-video-camera me-2"></i>Gestisci Video
                        </a>
                        <a href="{{ route('profile.activity') }}" class="btn btn-info hover-effect">
                            <i class="ph ph-activity me-2"></i>Le Mie Attività
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-primary hover-effect">
                            <i class="ph ph-eye me-2"></i>Vedi Profilo Pubblico
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = input.parentElement.parentElement.querySelector('img');
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-submit form when profile photo is selected
document.getElementById('profile-photo-input').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const file = this.files[0];

        console.log('File selezionato:', file);
        console.log('Dimensione file:', file.size, 'bytes');
        console.log('Tipo file:', file.type);

        // Verifica dimensione file (dinamica dalle impostazioni)
        const maxSize = {{ \App\Models\SystemSetting::get('profile_photo_max_size', 5120) }} * 1024; // Converti KB in bytes
        if (file.size > maxSize) {
            const maxSizeMB = {{ \App\Models\SystemSetting::get('profile_photo_max_size', 5120) }} / 1024;
            if (typeof Swal !== 'undefined') {
                Swal.fire('Errore', 'Il file è troppo grande. Dimensione massima: ' + maxSizeMB + 'MB', 'error');
            } else {
                alert('Il file è troppo grande. Dimensione massima: ' + maxSizeMB + 'MB');
            }
            this.value = ''; // Reset input
            return;
        }

        // Verifica tipo file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Errore', 'Tipo di file non supportato. Usa: JPEG, PNG, JPG, GIF', 'error');
            } else {
                alert('Tipo di file non supportato. Usa: JPEG, PNG, JPG, GIF');
            }
            this.value = ''; // Reset input
            return;
        }

        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_method', 'PUT');

        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Caricamento...',
                text: 'Sto aggiornando la tua foto profilo',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        } else {
            console.log('SweetAlert2 non disponibile, mostro alert normale');
            alert('Caricamento foto profilo...');
        }

        console.log('Invio richiesta a:', '{{ route("profile.update") }}');

        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If not JSON, get the text and log it
                return response.text().then(text => {
                    console.error('Non-JSON response received:', text);
                    throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Aggiorna l'immagine nella sidebar se esiste
                const sidebarAvatar = document.querySelector('.nav-profile img');
                if (sidebarAvatar && data.profile_photo_url) {
                    sidebarAvatar.src = data.profile_photo_url;
                }

                // Aggiorna l'immagine nella pagina di modifica
                const profileAvatar = document.querySelector('.profile-photo img');
                if (profileAvatar && data.profile_photo_url) {
                    profileAvatar.src = data.profile_photo_url;
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire('Successo!', 'Foto profilo aggiornata con successo', 'success');
                } else {
                    alert('Foto profilo aggiornata con successo!');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Errore', data.message || 'Errore durante il caricamento', 'error');
                } else {
                    alert('Errore: ' + (data.message || 'Errore durante il caricamento'));
                }
            }
        })
        .catch(error => {
            console.error('Errore durante il caricamento:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Errore', 'Errore durante il caricamento: ' + error.message, 'error');
            } else {
                alert('Errore durante il caricamento: ' + error.message);
            }
        });
    }
});

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
@endsection
