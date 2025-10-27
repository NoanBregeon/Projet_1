<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Header fixe avec tous les contrôles -->
    <div class="header-controls">
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

        <!-- Bouton de bascule thème -->
        <button id="theme-toggle" class="theme-toggle">
            <i id="theme-icon" class="bi bi-moon-fill"></i>
        </button>
    </div>

    <!-- Contenu principal avec espace pour le header -->
    <main class="main-content container px-4 py-6">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
