<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app()->getLocale() == 'en' ? 'Login' : 'Connexion' }}</title>

    <!-- Script anti-flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.style.backgroundColor = '#343a40';
                document.documentElement.style.color = '#ffffff';
            }
        })();
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Bouton de bascule thème -->
    <button id="theme-toggle" class="theme-toggle">
        <i id="theme-icon" class="bi bi-moon-fill"></i>
    </button>

    <!-- Sélecteur de langue -->
    <div class="language-selector">
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-globe me-1"></i>
                {{ app()->getLocale() == 'fr' ? 'FR' : 'EN' }}
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() == 'fr' ? 'active' : '' }}"
                       href="{{ route('language.switch', 'fr') }}">
                        <i class="bi bi-flag me-2"></i>Français
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                       href="{{ route('language.switch', 'en') }}">
                        <i class="bi bi-flag me-2"></i>English
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
        <div class="auth-container">
            <div class="text-center mb-3">
                <h1 class="auth-title">
                    {{ app()->getLocale() == 'en' ? 'Login' : 'Connexion' }}
                </h1>
                <p class="auth-subtitle">
                    {{ app()->getLocale() == 'en' ? 'Access your space to manage your cards' : 'Accédez à votre espace pour gérer vos cartes' }}
                </p>
            </div>

            <!-- Affichage des erreurs de session -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="form-field">
                        <label class="auth-form-label" for="email">Email</label>
                        <input id="email" class="auth-form-input" type="email" name="email"
                               value="{{ old('email') }}" required autofocus
                               placeholder="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-lock"></i>
                    </div>
                    <div class="form-field">
                        <label class="auth-form-label" for="password">
                            {{ app()->getLocale() == 'en' ? 'Password' : 'Mot de passe' }}
                        </label>
                        <input id="password" class="auth-form-input" type="password" name="password" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <!-- Se souvenir de moi -->
                <div class="form-inline mb-3">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label ms-2">
                        {{ app()->getLocale() == 'en' ? 'Remember me' : 'Se souvenir de moi' }}
                    </label>
                </div>

                <button type="submit" class="auth-form-button">
                    {{ app()->getLocale() == 'en' ? 'Log in' : 'Se connecter' }}
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="auth-link">
                        {{ app()->getLocale() == 'en' ? 'Create an account' : 'Créer un compte' }}
                    </a>
                </div>

                <div class="auth-back">
                    <a href="{{ url('/') }}" class="btn btn-link">
                        <i class="bi bi-house me-1"></i>
                        {{ app()->getLocale() == 'en' ? 'Back to home' : 'Retour à l\'accueil' }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
