@extends('layouts.app')

@section('title', app()->getLocale() == 'en' ? 'Reset Password' : 'Réinitialiser le mot de passe')
@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="auth-container">
        <div class="text-center mb-3">
            <div class="mb-3">
                <i class="bi bi-shield-lock display-1 text-success"></i>
            </div>
            <h1 class="auth-title">
                {{ app()->getLocale() == 'en' ? 'Reset Password' : 'Réinitialiser le mot de passe' }}
            </h1>
            <p class="auth-subtitle">
                {{ app()->getLocale() == 'en' ?
                    'Enter your new password below to complete the reset process.' :
                    'Saisissez votre nouveau mot de passe ci-dessous pour terminer la réinitialisation.'
                }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email (en lecture seule) -->
            <div class="form-group">
                <div class="input-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="form-field">
                    <label class="auth-form-label" for="email">Email</label>
                    <input id="email" class="auth-form-input" type="email" name="email"
                           value="{{ old('email', $request->email) }}" required readonly
                           style="background-color: #f8f9fa;">
                    @error('email')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Nouveau mot de passe -->
            <div class="form-group">
                <div class="input-icon">
                    <i class="bi bi-lock"></i>
                </div>
                <div class="form-field">
                    <label class="auth-form-label" for="password">
                        {{ app()->getLocale() == 'en' ? 'New Password' : 'Nouveau mot de passe' }}
                    </label>
                    <input id="password" class="auth-form-input" type="password" name="password" required autofocus>
                    @error('password')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Confirmation -->
            <div class="form-group">
                <div class="input-icon">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <div class="form-field">
                    <label class="auth-form-label" for="password_confirmation">
                        {{ app()->getLocale() == 'en' ? 'Confirm Password' : 'Confirmer le mot de passe' }}
                    </label>
                    <input id="password_confirmation" class="auth-form-input" type="password"
                           name="password_confirmation" required>
                    @error('password_confirmation')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="auth-form-button">
                <i class="bi bi-check-circle me-2"></i>
                {{ app()->getLocale() == 'en' ? 'Reset Password' : 'Réinitialiser le mot de passe' }}
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="auth-link">
                <i class="bi bi-arrow-left me-1"></i>
                {{ app()->getLocale() == 'en' ? 'Back to login' : 'Retour à la connexion' }}
            </a>
        </div>
    </div>
</div>

@endsection
