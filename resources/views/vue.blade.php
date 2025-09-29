@extends('layouts.app')

@section('title', 'Ma Collection de Cartes')

@section('content')
<!-- Messages de succès -->
@if(session('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- En-tête -->
<div class="text-center mb-5">
    <h1 class="display-4 fw-bold text-dark mb-2">Ma Collection de Cartes</h1>
    <p class="lead text-muted">Explorez et gérez votre collection personnelle</p>
</div>

<!-- Grille des cartes -->
<div class="row g-4">

    <!-- Cartes existantes -->
    @foreach($listeUnivers as $univers)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
            <!-- En-tête coloré avec gradient -->
            <div class="card-header border-0 text-white text-center py-3" style="background: linear-gradient(135deg, {{ $univers->primary_color }} 0%, {{ $univers->secondary_color }} 100%);">
                <h6 class="card-title mb-0 fw-bold">{{ $univers->name }}</h6>
            </div>

            @if($univers->image)
                <img src="{{ asset('storage/' . $univers->image) }}" class="card-img-top" alt="{{ $univers->name }}" style="height: 200px; object-fit: cover;">
            @else
                <!-- Si pas d'image, afficher un gradient coloré -->
                <div class="d-flex align-items-center justify-content-center text-white fs-1 fw-bold" style="height: 200px; background: linear-gradient(45deg, {{ $univers->primary_color }} 0%, {{ $univers->secondary_color }} 100%);">
                    <i class="bi bi-stars opacity-75"></i>
                </div>
            @endif

            <div class="card-body d-flex flex-column">
                <p class="card-text text-muted flex-grow-1">{{ Str::limit($univers->description, 100) }}</p>

                <!-- Indicateurs de couleurs -->
                <div class="d-flex align-items-center mb-3">
                    <small class="text-muted me-2">Couleurs:</small>
                    <div class="d-flex gap-2">
                        <div class="rounded-circle border border-2 border-white shadow-sm"
                             style="width: 30px; height: 30px; background-color: {{ $univers->primary_color }};"
                             title="Couleur primaire: {{ $univers->primary_color }}"></div>
                        <div class="rounded-circle border border-2 border-white shadow-sm"
                             style="width: 30px; height: 30px; background-color: {{ $univers->secondary_color }};"
                             title="Couleur secondaire: {{ $univers->secondary_color }}"></div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-2">
                    <a href="{{ route('univers.modify', $univers->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>
            </div>

            @if($univers->logo)
            <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                <img src="{{ asset('storage/' . $univers->logo) }}" alt="Logo {{ $univers->name }}"
                     class="rounded-circle border border-3 border-white shadow"
                     style="width: 50px; height: 50px; object-fit: cover;">
            </div>
            @endif
        </div>
    </div>
    @endforeach

    <!-- Bouton Ajouter une carte -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-2 border-dashed border-primary bg-light">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                <div class="mb-3">
                    <i class="bi bi-plus-circle display-1 text-primary"></i>
                </div>
                <h5 class="card-title text-primary fw-bold mb-3">Ajouter une carte</h5>
                <p class="card-text text-muted mb-4">Créer une nouvelle carte pour votre collection</p>
                <a href="{{ route('univers.add') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i>Créer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Message si aucune carte -->
@if($listeUnivers->isEmpty())
<div class="text-center mt-5">
    <div class="mb-4">
        <i class="bi bi-collection display-1 text-muted"></i>
    </div>
    <h3 class="text-muted mb-3">Aucune carte dans votre collection</h3>
    <p class="text-muted mb-4">Commencez par créer votre première carte !</p>
    <a href="{{ route('univers.add') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-lg me-2"></i>Créer ma première carte
    </a>
</div>
@endif
@endsection
