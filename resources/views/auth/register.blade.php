<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app()->getLocale() == 'en' ? 'Sign Up' : 'Créer un compte' }}</title>

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

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-container">
            <div class="text-center mb-3">
                <h1 class="auth-title">
                    {{ app()->getLocale() == 'en' ? 'Create an account' : 'Créer un compte' }}
                </h1>
                <p class="auth-subtitle">
                    {{ app()->getLocale() == 'en' ? 'Sign up to start creating your cards' : 'Inscrivez-vous pour commencer à créer vos cartes' }}
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Prénom -->
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="form-field">
                        <label class="auth-form-label" for="first_name">
                            {{ app()->getLocale() == 'en' ? 'First name' : 'Prénom' }}
                        </label>
                        <input id="first_name" class="auth-form-input" type="text" name="first_name"
                               value="{{ old('first_name') }}" required autofocus
                               placeholder="{{ app()->getLocale() == 'en' ? 'Your first name' : 'Votre prénom' }}">
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Nom -->
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="form-field">
                        <label class="auth-form-label" for="last_name">
                            {{ app()->getLocale() == 'en' ? 'Last name' : 'Nom' }}
                        </label>
                        <input id="last_name" class="auth-form-input" type="text" name="last_name"
                               value="{{ old('last_name') }}" required
                               placeholder="{{ app()->getLocale() == 'en' ? 'Your last name' : 'Votre nom' }}">
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="form-field">
                        <label class="auth-form-label" for="email">Email</label>
                        <input id="email" class="auth-form-input" type="email" name="email"
                               value="{{ old('email') }}" required
                               placeholder="votre@example.com">
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

                <!-- Confirmer le mot de passe -->
                <div class="form-group">
                    <div class="input-icon">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div class="form-field">
                        <label class="auth-form-label" for="password_confirmation">
                            {{ app()->getLocale() == 'en' ? 'Confirm password' : 'Confirmer le mot de passe' }}
                        </label>
                        <input id="password_confirmation" class="auth-form-input" type="password"
                               name="password_confirmation" required>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <button type="submit" class="auth-form-button">
                    {{ app()->getLocale() == 'en' ? 'Sign up' : 'S\'inscrire' }}
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="auth-link">
                        {{ app()->getLocale() == 'en' ? 'Already registered? Log in' : 'Déjà inscrit ? Se connecter' }}
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
