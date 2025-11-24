<div class="card h-100 shadow-sm border-0 position-relative overflow-hidden" data-card-id="{{ $id }}">
    <div class="card-header border-0 text-white text-center py-3" style="background: {{ $gradientHeader }}">
        <h6 class="card-title mb-0 fw-bold">{{ $name }}</h6>
    </div>

    @if($imageUrl)
        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $name }}" style="height: {{ $cardImageHeight }}; object-fit: cover;">
    @else
        <div class="d-flex align-items-center justify-content-center text-white fs-1 fw-bold" style="height: {{ $cardImageHeight }}; background: {{ $gradientBackground }};">
            <i class="bi bi-stars opacity-75"></i>
        </div>
    @endif

    <div class="card-body d-flex flex-column">
        <p class="card-text text-muted flex-grow-1 {{ Auth::check() ? '' : 'fs-5 lh-base' }}">
            {{ $description }}
        </p>

        <div class="d-flex align-items-center mb-3">
            <small class="text-muted me-2">
                {{ app()->getLocale() == 'en' ? 'Colors:' : 'Couleurs:' }}
            </small>
            <div class="d-flex gap-2">
                <div class="rounded-circle border border-2 border-white shadow-sm"
                     style="width: {{ $colorIndicatorSize }}; height: {{ $colorIndicatorSize }}; background-color: {{ $primaryColor }};"
                     data-bs-toggle="tooltip"
                     data-bs-placement="top"
                     title="{{ $colorTooltipPrimary }}"></div>
                <div class="rounded-circle border border-2 border-white shadow-sm"
                     style="width: {{ $colorIndicatorSize }}; height: {{ $colorIndicatorSize }}; background-color: {{ $secondaryColor }};"
                     data-bs-toggle="tooltip"
                     data-bs-placement="top"
                     title="{{ $colorTooltipSecondary }}"></div>
            </div>
        </div>

        <div class="d-flex gap-2">
            @auth
                <!-- Bouton Voir (pour tous) -->
                <a href="{{ route('univers.show', $id) }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-eye me-1"></i>
                    {{ app()->getLocale() == 'en' ? 'View' : 'Voir' }}
                </a>

                <!-- Bouton Favoris -->
                <button class="btn btn-outline-warning btn-sm favorite-btn"
                        data-univers-id="{{ $id }}"
                        data-is-favorite="{{ Auth::user()->hasFavorite($id) ? 'true' : 'false' }}">
                    <i class="bi {{ Auth::user()->hasFavorite($id) ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                    <span class="favorite-text">
                        {{ Auth::user()->hasFavorite($id)
                            ? (app()->getLocale() == 'en' ? 'Favorited' : 'Favori')
                            : (app()->getLocale() == 'en' ? 'Favorite' : 'Favoris')
                        }}
                    </span>
                </button>

                <!-- Bouton Modifier (admin seulement) -->
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('univers.edit', $id) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>
                        {{ app()->getLocale() == 'en' ? 'Edit' : 'Modifier' }}
                    </a>
                @endif
            @else
                <!-- Visiteurs non connectés -->
                <a href="{{ route('univers.show', $id) }}" class="btn btn-outline-secondary btn-lg w-100">
                    <i class="bi bi-eye me-1"></i>
                    {{ app()->getLocale() == 'en' ? 'View card' : 'Voir l\'univers' }}
                </a>
            @endauth
        </div>
    </div>

    @if($logoUrl)
    <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
        <img src="{{ $logoUrl }}" alt="Logo {{ $name }}"
             class="rounded-circle border border-3 border-white shadow"
             style="width: {{ $logoSize }}; height: {{ $logoSize }}; object-fit: cover;">
    </div>
    @endif
</div>
