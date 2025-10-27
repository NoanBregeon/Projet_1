@extends('layouts.app')

@section('title', 'Créer un compte')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="auth-container">
        <div class="text-center mb-3">
            <x-application-logo class="mx-auto mb-3" style="width:64px;height:64px;" />
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-subtitle">Inscrivez-vous pour commencer à créer vos cartes</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-frame">
                <div class="form-group">
                    <span class="input-icon"><i class="bi bi-person"></i></span>
                    <div class="form-field">
                        <label for="first_name" class="auth-form-label">Prénom</label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" class="auth-form-input @error('first_name') is-invalid @enderror" required autofocus placeholder="Votre prénom">
                        @error('first_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <span class="input-icon"><i class="bi bi-person-badge"></i></span>
                    <div class="form-field">
                        <label for="last_name" class="auth-form-label">Nom</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" class="auth-form-input @error('last_name') is-invalid @enderror" required placeholder="Votre nom">
                        @error('last_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <div class="form-field">
                        <label for="email" class="auth-form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-form-input @error('email') is-invalid @enderror" required placeholder="votre@example.com">
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <div class="form-field">
                        <label for="password" class="auth-form-label">Mot de passe</label>
                        <input id="password" type="password" name="password" class="auth-form-input @error('password') is-invalid @enderror" required placeholder="••••••••">
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                    <div class="form-field">
                        <label for="password_confirmation" class="auth-form-label">Confirmer le mot de passe</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="auth-form-input" required placeholder="••••••••">
                    </div>
                </div>
            </div>

            <button type="submit" class="auth-form-button">S'inscrire</button>
        </form>

        <div class="mt-3 text-center">
            <span class="text-muted">Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="auth-link ms-2">Se connecter</a>
        </div>

        <a href="{{ url('/') }}" class="btn btn-outline-light auth-back">
            <i class="bi bi-house-door me-1"></i> Retour à l'accueil
        </a>
    </div>
</div>
@endsection
