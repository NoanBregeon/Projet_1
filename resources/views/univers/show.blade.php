@extends('layouts.app')

@section('title', $univers->name)
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Bouton retour -->
            <div class="mb-4">
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>
                    {{ app()->getLocale() == 'en' ? 'Back to collection' : 'Retour à la collection' }}
                </a>
            </div>

            <!-- Carte principale -->
            <div class="card shadow-lg border-0">
                <!-- En-tête avec gradient -->
                <div class="card-header text-white text-center py-4"
                     style="background: linear-gradient(135deg, {{ $univers->primary_color }}, {{ $univers->secondary_color }});">
                    <h1 class="h2 mb-0 fw-bold">{{ $univers->name }}</h1>
                </div>

                <!-- Image principale -->
                @if($univers->image)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $univers->image) }}"
                             class="card-img-top"
                             alt="{{ $univers->name }}"
                             style="height: 400px; object-fit: cover;">

                        <!-- Logo en overlay si présent -->
                        @if($univers->logo)
                            <div class="position-absolute top-0 end-0 m-3">
                                <img src="{{ asset('storage/' . $univers->logo) }}"
                                     alt="Logo {{ $univers->name }}"
                                     class="rounded-circle border border-3 border-white shadow"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Fallback si pas d'image -->
                    <div class="d-flex align-items-center justify-content-center text-white position-relative"
                         style="height: 400px; background: linear-gradient(45deg, {{ $univers->primary_color }}, {{ $univers->secondary_color }});">
                        <i class="bi bi-stars display-1 opacity-75"></i>

                        @if($univers->logo)
                            <div class="position-absolute top-0 end-0 m-3">
                                <img src="{{ asset('storage/' . $univers->logo) }}"
                                     alt="Logo {{ $univers->name }}"
                                     class="rounded-circle border border-3 border-white shadow"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Contenu de la carte -->
                <div class="card-body p-4">
                    <!-- Description -->
                    <div class="mb-4">
                        <h3 class="h4 mb-3">
                            {{ app()->getLocale() == 'en' ? 'Description' : 'Description' }}
                        </h3>
                        <p class="lead text-muted">{{ $univers->description }}</p>
                    </div>

                    <!-- Informations sur les couleurs -->
                    <div class="mb-4">
                        <h3 class="h5 mb-3">
                            {{ app()->getLocale() == 'en' ? 'Color palette' : 'Palette de couleurs' }}
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle me-3 border border-2 border-white shadow"
                                         style="width: 40px; height: 40px; background-color: {{ $univers->primary_color }};"></div>
                                    <div>
                                        <small class="text-muted d-block">
                                            {{ app()->getLocale() == 'en' ? 'Primary color' : 'Couleur principale' }}
                                        </small>
                                        <strong>{{ $univers->primary_color }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle me-3 border border-2 border-white shadow"
                                         style="width: 40px; height: 40px; background-color: {{ $univers->secondary_color }};"></div>
                                    <div>
                                        <small class="text-muted d-block">
                                            {{ app()->getLocale() == 'en' ? 'Secondary color' : 'Couleur secondaire' }}
                                        </small>
                                        <strong>{{ $univers->secondary_color }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du gradient -->
                    <div class="mb-4">
                        <h3 class="h5 mb-3">
                            {{ app()->getLocale() == 'en' ? 'Gradient preview' : 'Aperçu du dégradé' }}
                        </h3>
                        <div class="rounded p-4 text-white text-center fw-bold"
                             style="background: linear-gradient(135deg, {{ $univers->primary_color }}, {{ $univers->secondary_color }});">
                            {{ $univers->name }}
                        </div>
                    </div>

                    <!-- Actions (si utilisateur connecté et admin) -->
                    @auth
                        @if(Auth::user()->isA('admin'))
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('univers.edit', $univers->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>
                                    {{ app()->getLocale() == 'en' ? 'Edit card' : 'Modifier la carte' }}
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmerSuppressionUnivers()">
                                    <i class="bi bi-trash me-2"></i>
                                    {{ app()->getLocale() == 'en' ? 'Delete card' : 'Supprimer la carte' }}
                                </button>
                            </div>

                            <!-- Script pour la suppression -->
                            <script>
                                function confirmerSuppressionUnivers() {
                                    const message = {{ app()->getLocale() == 'en' ? "'Are you sure you want to delete this card?'" : "'Êtes-vous sûr de vouloir supprimer cette carte ?'" }};
                                    if (confirm(message)) {
                                        const form = document.createElement('form');
                                        form.method = 'POST';
                                        form.action = '{{ route('univers.destroy', $univers->id) }}';
                                        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}));
                                        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_method', value: 'DELETE'}));
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                }
                            </script>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
