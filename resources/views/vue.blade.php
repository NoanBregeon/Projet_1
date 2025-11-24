@extends('layouts.app')

@section('title', 'Ma Collection de Cartes')
@section('content')

@if(session('message'))
    <x-alerte :message="session('message')" type="success" icon="bi-check-circle" />
@endif

@auth
<div class="mb-4 text-end d-flex justify-content-end gap-2 align-items-center">
    <!-- Indicateur de rôle -->
    @if(Auth::check() && Auth::user()->role === 'admin')
        <span class="badge bg-danger me-2">
            <i class="bi bi-shield-check me-1"></i>
            {{ app()->getLocale() == 'en' ? 'Administrator' : 'Administrateur' }}
        </span>
    @else
        <span class="badge bg-info me-2">
            <i class="bi bi-person me-1"></i>
            {{ app()->getLocale() == 'en' ? 'User' : 'Utilisateur' }}
        </span>
    @endif

    <!-- Bouton pour créer une carte -->
    <a href="{{ route('univers.create') }}" class="btn btn-primary me-2">
        <i class="bi bi-plus-lg me-1"></i>
        {{ app()->getLocale() == 'en' ? 'Create Card' : 'Créer une carte' }}
    </a>

    <!-- Formulaire de déconnexion -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right me-1"></i>
            {{ app()->getLocale() == 'en' ? 'Logout' : 'Déconnexion' }}
        </button>
    </form>
</div>
@endauth

@guest
<div class="mb-4 text-end">
    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
        <i class="bi bi-person-circle me-1"></i>
        {{ app()->getLocale() == 'en' ? 'Login' : 'Connexion' }}
    </a>
    <a href="{{ route('register') }}" class="btn btn-outline-success">
        <i class="bi bi-person-plus me-1"></i>
        {{ app()->getLocale() == 'en' ? 'Sign Up' : 'Créer un compte' }}
    </a>
</div>
@endguest

<div class="text-center mb-5">
    <h1 class="display-4 fw-bold text-dark mb-2">
        {{ app()->getLocale() == 'en' ? 'My Card Collection' : 'Ma Collection de Cartes' }}
    </h1>
    <p class="lead text-muted">
        {{ app()->getLocale() == 'en' ? 'Explore and manage your personal collection' : 'Explorez et gérez votre collection personnelle' }}
    </p>
</div>

<div class="row g-4">
    @forelse($processedUnivers as $univers)
    <div class="col-md-6 col-lg-4">
        <x-carte
            :id="$univers['id']"
            :name="$univers['name']"
            :gradientHeader="$univers['gradient_header']"
            :imageUrl="$univers['image_url']"
            :cardImageHeight="$viewConfig['styles']['card_image_height']"
            :gradientBackground="$univers['gradient_background']"
            :description="$univers['truncated_description']"
            :primaryColor="$univers['primary_color']"
            :secondaryColor="$univers['secondary_color']"
            :colorIndicatorSize="$viewConfig['styles']['color_indicator_size']"
            :colorTooltipPrimary="$univers['color_tooltips']['primary']"
            :colorTooltipSecondary="$univers['color_tooltips']['secondary']"
            :logoUrl="$univers['logo_url']"
            :logoSize="$viewConfig['styles']['logo_size']"
        />
    </div>
    @empty
    <div class="col-12">
        <div class="text-center mt-5">
            <div class="mb-4">
                <i class="bi bi-collection display-1 text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">{{ $viewConfig['messages']['empty_title'] }}</h3>
            <p class="text-muted mb-4">{{ $viewConfig['messages']['empty_subtitle'] }}</p>
            @auth
            <a href="{{ route('univers.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-lg me-2"></i>{{ $viewConfig['messages']['empty_button'] }}
            </a>
            @endauth
        </div>
    </div>
    @endforelse

    @if($processedUnivers->isNotEmpty() && Auth::check())
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-2 border-dashed border-primary bg-light">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                <div class="mb-3">
                    <i class="bi bi-plus-circle display-1 text-primary"></i>
                </div>
                <h5 class="card-title text-primary fw-bold mb-3">{{ $viewConfig['messages']['add_title'] }}</h5>
                <p class="card-text text-muted mb-4">{{ $viewConfig['messages']['add_subtitle'] }}</p>
                <a href="{{ route('univers.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i>{{ $viewConfig['messages']['add_button'] }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
