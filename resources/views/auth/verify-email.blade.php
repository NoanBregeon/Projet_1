@extends('layouts.app')

@section('title', app()->getLocale() == 'en' ? 'Verify Email' : 'Vérifier l\'email')
@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="auth-container">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="bi bi-envelope-check display-1 text-primary"></i>
            </div>
            <h1 class="auth-title">
                {{ app()->getLocale() == 'en' ? 'Verify Your Email' : 'Vérifiez votre email' }}
            </h1>
            <p class="auth-subtitle">
                {{ app()->getLocale() == 'en' ?
                    'We\'ve sent a verification email to your address. Please check your inbox and click the verification link to activate your account.' :
                    'Nous avons envoyé un email de vérification à votre adresse. Veuillez vérifier votre boîte de réception et cliquer sur le lien de vérification pour activer votre compte.'
                }}
            </p>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>{{ app()->getLocale() == 'en' ? 'Email sent to:' : 'Email envoyé à :' }}</strong>
                {{ auth()->user()->email }}
            </div>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ app()->getLocale() == 'en' ? 'A new verification link has been sent to your email address!' : 'Un nouveau lien de vérification a été envoyé à votre adresse email !' }}
            </div>
        @endif

        <div class="d-flex gap-3 flex-column">
            <!-- Renvoyer le lien -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="auth-form-button">
                    <i class="bi bi-envelope me-2"></i>
                    {{ app()->getLocale() == 'en' ? 'Resend Verification Email' : 'Renvoyer l\'email de vérification' }}
                </button>
            </form>

            <!-- Déconnexion -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    {{ app()->getLocale() == 'en' ? 'Log Out' : 'Se déconnecter' }}
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">
                {{ app()->getLocale() == 'en' ? 'Check your spam folder if you don\'t see the email in your inbox.' : 'Vérifiez votre dossier spam si vous ne voyez pas l\'email dans votre boîte de réception.' }}
            </small>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url('/') }}" class="auth-link">
                <i class="bi bi-house me-1"></i>
                {{ app()->getLocale() == 'en' ? 'Back to home' : 'Retour à l\'accueil' }}
            </a>
        </div>
    </div>
</div>

@endsection
