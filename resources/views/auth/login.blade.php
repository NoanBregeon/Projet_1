@extends('layouts.app')

@section('title', 'Connexion')

@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="auth-container">
        <div class="text-center mb-3">
            <x-application-logo class="mx-auto mb-3" style="width:64px;height:64px;" />
            <h1 class="auth-title">Connexion</h1>
            <p class="auth-subtitle">Accédez à votre espace pour gérer vos cartes</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-frame">
                <div class="form-group">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <div class="form-field">
                        <label for="email" class="auth-form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-form-input @error('email') is-invalid @enderror" required autofocus placeholder="votre@example.com">
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

                <div class="form-inline">
                    <div>
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="text-muted ms-2">Se souvenir de moi</label>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('password.request') }}" class="auth-link">Mot de passe oublié ?</a>
                    </div>
                </div>
            </div>

            <button type="submit" class="auth-form-button">Se connecter</button>
        </form>

        <div class="mt-3 text-center">
            <span class="text-muted">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="auth-link ms-2">Créer un compte</a>
        </div>

        <a href="{{ url('/') }}" class="btn btn-outline-secondary auth-back">
            <i class="bi bi-house-door me-1"></i> Retour à l'accueil
        </a>
    </div>
</div>
@endsection
