<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slam In - A Home for poetry</title>

    <!-- Solo Bootstrap CSS essenziale -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* CSS con colori del template */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(12, 78, 85) 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .left-section {
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(12, 78, 85) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 3rem 2rem;
        }

        .brand-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }

        .right-section {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 1.5rem;
        }

        .form-container {
            width: 100%;
            max-width: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .right-section {
                padding: 2rem 1rem;
                justify-content: center;
            }

            .form-container {
                max-width: 600px;
                margin: 0 auto;
            }
        }

        @media (min-width: 992px) {
            .right-section {
                padding: 2rem 3rem;
            }

            .form-container {
                max-width: 100%;
            }
        }

        .logo {
            max-width: 180px;
            margin-bottom: 2rem;
        }

        .feature-icon {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .feature-icon:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: rgb(15, 98, 106);
            border: 1px solid rgb(15, 98, 106);
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: rgb(12, 78, 85);
            border-color: rgb(12, 78, 85);
            transform: translateY(-1px);
        }

        .role-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .role-card:hover {
            border-color: rgb(15, 98, 106);
            background-color: rgba(15, 98, 106, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(15, 98, 106, 0.1);
        }

        .role-card input:checked + label {
            color: rgb(15, 98, 106);
            font-weight: 600;
        }

        .role-card:has(input:checked) {
            border-color: rgb(15, 98, 106);
            background-color: rgba(15, 98, 106, 0.08);
            box-shadow: 0 2px 8px rgba(15, 98, 106, 0.15);
        }

        .form-control:focus {
            border-color: rgb(15, 98, 106);
            box-shadow: 0 0 0 0.2rem rgba(15, 98, 106, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-label strong {
            color: rgb(15, 98, 106);
        }

        .btn-outline-secondary:hover,
        .btn-outline-info:hover {
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0" style="min-height: 100vh;">

            <!-- Colonna sinistra - Brand -->
            <div class="col-lg-7 d-none d-lg-block left-section">
                <div class="brand-container">
                    <img src="{{ asset('assets/images/logo.png') }}"
                         alt="Slam In - A Home for poetry"
                         class="img-fluid logo">

                    <h1 class="mb-4">🎭 Slam In</h1>
                    <p class="lead mb-5">A Home for poetry</p>

                    <div class="row text-center justify-content-center">
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>🎤</h3>
                                <small>Poeti</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>🎪</h3>
                                <small>Eventi</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>🏛️</h3>
                                <small>Venue</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>👥</h3>
                                <small>Community</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="small opacity-75">
                            La piattaforma italiana per<br>
                            poeti, organizzatori, venue e appassionati di slam
                        </p>
                    </div>
                </div>
            </div>

            <!-- Colonna destra - Form -->
            <div class="col-lg-5 col-12 right-section">
                <div class="form-container">

                    <div class="text-center mb-4">
                        <h2>🚀 Registrati</h2>
                        <p class="text-muted">Crea il tuo account e scegli i tuoi ruoli</p>
                        <p class="small text-muted">✏️ Potrai completare il tuo profilo con bio e località dopo la registrazione</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.process') }}">
                        @csrf

                        <!-- Dati Base - Layout migliorato -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome Completo *</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name') }}" required
                                       placeholder="Il tuo nome e cognome">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nickname</label>
                                <input type="text" name="nickname" class="form-control"
                                       value="{{ old('nickname') }}"
                                       placeholder="Come vuoi essere chiamato">
                                <small class="text-muted">Opzionale - Se non specificato, useremo il tuo nome</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email') }}" required
                                       placeholder="la.tua.email@esempio.it">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password *
                                    <small class="text-muted">(min. 8 caratteri)</small>
                                </label>
                                <input type="password" name="password" class="form-control"
                                       required placeholder="••••••••">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Conferma Password *</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                       required placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Selezione Multi-Ruolo -->
                        <div class="mb-4">
                            <label class="form-label">
                                <strong>🎭 Scegli il tuo ruolo in Slam In</strong>
                            </label>
                            <p class="text-muted small mb-3">
                                Puoi selezionare uno o più ruoli. Se non ne selezioni nessuno, verrai registrato come audience/fan.
                                <br><strong>💡 Quattro ruoli principali:</strong> Poeta, Event Manager, Proprietario Venue, Audience
                            </p>



                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-6 mb-2">
                                        <div class="role-card">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="roles[]"
                                                       value="{{ $role['name'] }}"
                                                       id="role_{{ $role['name'] }}"
                                                       {{ in_array($role['name'], old('roles', [])) ? 'checked' : '' }}>

                                                <label class="form-check-label w-100" for="role_{{ $role['name'] }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2 fs-5">{{ $role['icon'] }}</span>
                                                        <div class="flex-grow-1">
                                                            <strong>{{ $role['display_name'] }}</strong><br>
                                                            <small class="text-muted">{{ $role['description'] }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pulsante Registrazione -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                🚀 Join Slam In
                            </button>
                        </div>
                    </form>

                    <!-- Link Alternativi -->
                    <div class="text-center border-top pt-3 mt-4">
                        <p class="text-muted mb-3">Hai già un account?</p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-info btn-sm">
                                🧪 Test Login
                            </a>
                        </div>
                    </div>

                    <!-- Info Footer -->
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <h6>🌟 Perché unirti a Slam In?</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li>• <strong>Registrazione veloce:</strong> Solo i dati essenziali, profilo completabile dopo</li>
                                <li>• <strong>Ruoli flessibili:</strong> Poeta, event manager, proprietario venue o audience</li>
                                <li>• <strong>Ecosistema completo:</strong> Artisti, organizzatori, venue e pubblico insieme</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Solo JavaScript essenziale -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
