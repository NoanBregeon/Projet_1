@extends('layouts.app')

@section('title', $univers->name)
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header border-0 text-white text-center py-4" style="background: linear-gradient(135deg, {{ $univers->primary_color }} 0%, {{ $univers->secondary_color }} 100%);">
                <h2 class="card-title mb-0 fw-bold">{{ $univers->name }}</h2>
            </div>
            @if($univers->image)
                <img src="{{ asset('storage/' . $univers->image) }}" class="card-img-top" alt="{{ $univers->name }}" style="height: 260px; object-fit: cover;">
            @endif
            <div class="card-body">
                <p class="card-text fs-5 lh-base">{{ $univers->description }}</p>
                <div class="d-flex align-items-center mb-3">
                    <small class="text-muted me-2">Couleurs :</small>
                    <div class="d-flex gap-2">
                        <div class="rounded-circle border border-2 border-white shadow-sm"
                             style="width: 32px; height: 32px; background-color: {{ $univers->primary_color }};"
                             title="Couleur principale"></div>
                        <div class="rounded-circle border border-2 border-white shadow-sm"
                             style="width: 32px; height: 32px; background-color: {{ $univers->secondary_color }};"
                             title="Couleur secondaire"></div>
                    </div>
                </div>
                @if($univers->logo)
                    <div class="mt-4 text-center">
                        <img src="{{ asset('storage/' . $univers->logo) }}" alt="Logo {{ $univers->name }}"
                             class="rounded-circle border border-3 border-white shadow"
                             style="width: 64px; height: 64px; object-fit: cover;">
                    </div>
                @endif
            </div>
        </div>
        <div class="text-center">
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la collection
            </a>
        </div>
    </div>
</div>
@endsection
