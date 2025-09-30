@extends('layouts.app')

@section('title', 'Ma Collection de Cartes')
@section('content')
@if(session('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="text-center mb-5">
    <h1 class="display-4 fw-bold text-dark mb-2">Ma Collection de Cartes</h1>
    <p class="lead text-muted">Explorez et gérez votre collection personnelle</p>
</div>

<div class="row g-4">
    @foreach($processedUnivers as $univers)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
            <div class="card-header border-0 text-white text-center py-3" style="background: {{ $univers['gradient_header'] }};">
                <h6 class="card-title mb-0 fw-bold">{{ $univers['name'] }}</h6>
            </div>
            @if($univers['image_url'])
                <img src="{{ $univers['image_url'] }}" class="card-img-top" alt="{{ $univers['name'] }}" style="height: {{ $viewConfig['styles']['card_image_height'] }}; object-fit: cover;">
            @else
                <div class="d-flex align-items-center justify-content-center text-white fs-1 fw-bold" style="height: {{ $viewConfig['styles']['card_image_height'] }}; background: {{ $univers['gradient_background'] }};">
                    <i class="bi bi-stars opacity-75"></i>
                </div>
            @endif

            <div class="card-body d-flex flex-column">
                <p class="card-text text-muted flex-grow-1">{{ $univers['truncated_description'] }}</p>
                <div class="d-flex align-items-center mb-3">
                    <small class="text-muted me-2">Couleurs:</small>
                    <div class="d-flex gap-2">
                        <div class="rounded-circle border border-2 border-white shadow-sm"
                             style="width: {{ $viewConfig['styles']['color_indicator_size'] }}; height: {{ $viewConfig['styles']['color_indicator_size'] }}; background-color: {{ $univers['primary_color'] }};"
                             title="{{ $univers['color_tooltips']['primary'] }}"></div>
                        <div class="rounded-circle border border-2 border-white shadow-sm"
                             style="width: {{ $viewConfig['styles']['color_indicator_size'] }}; height: {{ $viewConfig['styles']['color_indicator_size'] }}; background-color: {{ $univers['secondary_color'] }};"
                             title="{{ $univers['color_tooltips']['secondary'] }}"></div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('univers.modify', $univers['id']) }}" class="btn btn-outline-primary btn-sm flex-fill">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                </div>
            </div>
            @if($univers['logo_url'])
            <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                <img src="{{ $univers['logo_url'] }}" alt="Logo {{ $univers['name'] }}"
                     class="rounded-circle border border-3 border-white shadow"
                     style="width: {{ $viewConfig['styles']['logo_size'] }}; height: {{ $viewConfig['styles']['logo_size'] }}; object-fit: cover;">
            </div>
            @endif
        </div>
    </div>
    @endforeach
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-2 border-dashed border-primary bg-light">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                <div class="mb-3">
                    <i class="bi bi-plus-circle display-1 text-primary"></i>
                </div>
                <h5 class="card-title text-primary fw-bold mb-3">{{ $viewConfig['messages']['add_title'] }}</h5>
                <p class="card-text text-muted mb-4">{{ $viewConfig['messages']['add_subtitle'] }}</p>
                <a href="{{ route('univers.add') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i>{{ $viewConfig['messages']['add_button'] }}
                </a>
            </div>
        </div>
    </div>
</div>

@if($isEmpty)
<div class="text-center mt-5">
    <div class="mb-4">
        <i class="bi bi-collection display-1 text-muted"></i>
    </div>
    <h3 class="text-muted mb-3">{{ $viewConfig['messages']['empty_title'] }}</h3>
    <p class="text-muted mb-4">{{ $viewConfig['messages']['empty_subtitle'] }}</p>
    <a href="{{ route('univers.add') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-lg me-2"></i>{{ $viewConfig['messages']['empty_button'] }}
    </a>
</div>
@endif
@endsection
