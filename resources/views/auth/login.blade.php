<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slam In - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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

        .right-section {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .feature-icon {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .form-container {
            max-width: 400px;
            width: 100%;
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

        .logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo img {
            opacity: 0.95;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .logo img:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        .logo-brand {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .welcome-text {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .feature-list {
            text-align: left;
            max-width: 300px;
            margin: 0 auto;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255,255,255,0.15);
        }

        .feature-item i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .back-link {
            color: rgb(15, 98, 106);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: rgb(12, 78, 85);
            text-decoration: underline;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        @media (max-width: 992px) {
            .left-section {
                min-height: 40vh;
                padding: 2rem 1rem;
            }

            .right-section {
                min-height: 60vh;
                padding: 1rem;
            }

            .feature-list {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Left Section - Brand & Features -->
            <div class="col-lg-7 left-section">
                <div>
                    <div class="logo mb-3">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Slam In Logo" class="img-fluid" style="max-width: 250px;">
                    </div>
                    <div class="logo-brand">
                        🎭 Slam In
                    </div>
                    <div class="welcome-text">
                        Bentornato! Accedi alla tua community Slamin
                    </div>

                    <div class="feature-list">
                        <div class="feature-item">
                            <i class="bi bi-calendar-event"></i>
                            <span>Eventi e spettacoli</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-people"></i>
                            <span>Community di poeti</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-mic"></i>
                            <span>Condividi le tue performance</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-trophy"></i>
                            <span>Partecipa ai concorsi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section - Login Form -->
            <div class="col-lg-5 right-section">
                <div class="form-container">
                    <div class="text-center mb-4">
                        <div class="d-lg-none mb-3">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Slam In Logo" class="img-fluid" style="max-width: 180px;">
                        </div>
                        <h2>🔐 <strong>Accedi al tuo account</strong></h2>
                        <p class="text-muted">Entra in Slam In e scopri il mondo dello slam italiano</p>
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

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.process') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <strong>📧 Email</strong>
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="inserisci@tuaemail.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <strong>🔑 Password</strong>
                            </label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="la tua password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ricordami
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                🎭 Entra in Slam In
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <p class="mb-0">
                                Non hai ancora un account?
                                <a href="{{ route('register') }}" class="back-link">
                                    Registrati qui
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
