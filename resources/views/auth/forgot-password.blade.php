@extends('layouts.app')

@section('title', app()->getLocale() == 'en' ? 'Forgot Password' : 'Mot de passe oublié')
@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="auth-container">
        <div class="text-center mb-3">
            <div class="mb-3">
                <i class="bi bi-key display-1 text-warning"></i>
            </div>
            <h1 class="auth-title">
                {{ app()->getLocale() == 'en' ? 'Forgot Password?' : 'Mot de passe oublié ?' }}
            </h1>
            <p class="auth-subtitle">
                {{ app()->getLocale() == 'en' ?
                    'No problem. Just let us know your email address and we will email you a password reset link.' :
                    'Pas de problème. Indiquez-nous simplement votre adresse email et nous vous enverrons un lien de réinitialisation.'
                }}
            </p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <div class="input-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="form-field">
                    <label class="auth-form-label" for="email">Email</label>
                    <input id="email" class="auth-form-input" type="email" name="email"
                           value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="auth-form-button">
                <i class="bi bi-send me-2"></i>
                {{ app()->getLocale() == 'en' ? 'Email Password Reset Link' : 'Envoyer le lien de réinitialisation' }}
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="auth-link">
                {{ app()->getLocale() == 'en' ? 'Back to login' : 'Retour à la connexion' }}
            </a>
        </div>
    </div>
</div>

@endsection
