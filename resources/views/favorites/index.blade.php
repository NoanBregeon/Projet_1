@extends('layouts.app')

@section('title', $viewConfig['messages']['title'])
@section('content')

<!-- Bouton de retour -->
<div class="mb-4">
    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>
        {{ app()->getLocale() == 'en' ? 'Back to collection' : 'Retour à la collection' }}
    </a>
</div>

<div class="text-center mb-5">
    <h1 class="display-4 fw-bold text-dark mb-2">
        <i class="bi bi-heart-fill text-danger me-3"></i>
        {{ $viewConfig['messages']['title'] }}
    </h1>
    <p class="lead text-muted">
        {{ $viewConfig['messages']['subtitle'] }}
    </p>
    
    @if($processedUnivers->isNotEmpty())
        <div class="mt-3">
            <span class="badge bg-danger fs-6 px-3 py-2">
                {{ $processedUnivers->count() }} {{ app()->getLocale() == 'en' ? 'favorite cards' : 'cartes favorites' }}
            </span>
        </div>
    @endif
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
                <i class="bi bi-heart display-1 text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">{{ $viewConfig['messages']['empty_title'] }}</h3>
            <p class="text-muted mb-4">{{ $viewConfig['messages']['empty_subtitle'] }}</p>
            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>
                {{ app()->getLocale() == 'en' ? 'Browse Cards' : 'Parcourir les cartes' }}
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Actions supplémentaires quand il y a des favoris -->
@if($processedUnivers->isNotEmpty())
    <div class="text-center mt-5 pt-4 border-top">
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('/') }}" class="btn btn-outline-primary">
                <i class="bi bi-collection me-2"></i>
                {{ app()->getLocale() == 'en' ? 'View all cards' : 'Voir toutes les cartes' }}
            </a>
            
            @if(Auth::user()->isA('admin') || true)
                <a href="{{ route('univers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    {{ app()->getLocale() == 'en' ? 'Create new card' : 'Créer une nouvelle carte' }}
                </a>
            @endif
        </div>
    </div>
@endif

@endsection
