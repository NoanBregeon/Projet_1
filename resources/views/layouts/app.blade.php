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
                document.documentElement.style.backgroundColor = '#1e293b';
                document.documentElement.style.color = '#f8fafc';
            }
        })();
    </script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Header fixe avec tous les contrôles -->
    <div class="header-controls">
        @auth
            <!-- Lien vers les favoris -->
            <a href="{{ route('favorites.index') }}" 
               class="btn {{ request()->routeIs('favorites.index') ? 'btn-warning' : 'btn-outline-warning' }} btn-sm me-2">
                <i class="bi bi-heart-fill me-1"></i>
                {{ app()->getLocale() == 'en' ? 'Favorites' : 'Favoris' }}
                @if(Auth::user()->favorites()->count() > 0)
                    <span class="badge {{ request()->routeIs('favorites.index') ? 'bg-dark' : 'bg-warning text-dark' }} ms-1">
                        {{ Auth::user()->favorites()->count() }}
                    </span>
                @endif
            </a>
            
            <!-- Lien vers l'accueil si on est sur les favoris -->
            @if(request()->routeIs('favorites.index'))
                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-house me-1"></i>
                    {{ app()->getLocale() == 'en' ? 'Home' : 'Accueil' }}
                </a>
            @endif
        @endauth

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
</body>
</html>
