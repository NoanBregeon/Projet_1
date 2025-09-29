@extends('layouts.app')

@section('title', $isEdit ? 'Modifier la carte' : 'Ajouter une carte')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="mb-4">
            <a href="{{ url('/') }}" class="btn btn-link text-decoration-none">
                <i class="bi bi-arrow-left"></i> Retour à ma collection
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body p-4">
                <!-- En-tête avec couleurs de la carte -->
                <div class="text-center py-4 rounded-3 mb-4 text-white" style="background: linear-gradient(to right, {{ $formData['primary_color'] }}, {{ $formData['secondary_color'] }});">
                    <h1 class="h3 fw-bold">
                        @if($isEdit)
                            Modifier {{ $formData['name'] }}
                        @else
                            Créer une nouvelle carte
                        @endif
                    </h1>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Formulaire à gauche -->
                    <div class="col-md-8">
                        <form action="{{ $isEdit ? route('univers.update', $univers->id) : route('univers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                @method('PUT')
                            @endif

                            <!-- Nom de la carte -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">
                                    Nom de la carte <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $formData['name']) }}" required
                                       placeholder="Ex: Dragon Légendaire, Forêt Enchantée...">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-medium">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4" required
                                          placeholder="Décrivez cette carte unique...">{{ old('description', $formData['description']) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image actuelle (seulement en mode édition) -->
                            @if($isEdit && $formData['image'])
                            <div class="mb-3">
                                <label class="form-label fw-medium">Image actuelle</label>
                                <div class="d-flex align-items-start gap-3">
                                    <img src="{{ asset('storage/' . $formData['image']) }}" alt="{{ $formData['name'] }}" class="img-thumbnail" style="width: 192px; height: 128px; object-fit: cover;">
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="supprimerImage()">
                                        <i class="bi bi-trash me-1"></i>Supprimer l'image
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-medium">
                                    @if($isEdit)
                                        Changer l'image (optionnel)
                                    @else
                                        Image de la carte
                                    @endif
                                </label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <div class="form-text">
                                    @if($isEdit)
                                        Laissez vide pour garder l'image actuelle. Format: JPG, PNG. Taille max: 2MB
                                    @else
                                        Format: JPG, PNG. Taille max: 2MB
                                    @endif
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Logo actuel (seulement en mode édition) -->
                            @if($isEdit && $formData['logo'])
                            <div class="mb-3">
                                <label class="form-label fw-medium">Logo/Symbole actuel</label>
                                <div class="d-flex align-items-start gap-3">
                                    <img src="{{ asset('storage/' . $formData['logo']) }}" alt="Logo {{ $formData['name'] }}" class="img-thumbnail" style="width: 64px; height: 64px; object-fit: cover;">
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="supprimerLogo()">
                                        <i class="bi bi-trash me-1"></i>Supprimer le logo
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- Logo -->
                            <div class="mb-3">
                                <label for="logo" class="form-label fw-medium">
                                    @if($isEdit)
                                        Changer le logo/symbole (optionnel)
                                    @else
                                        Logo/Symbole (optionnel)
                                    @endif
                                </label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                <div class="form-text">
                                    @if($isEdit)
                                        Laissez vide pour garder le logo actuel. Taille max: 1MB
                                    @else
                                        Petit symbole représentant cette carte. Taille max: 1MB
                                    @endif
                                </div>
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Couleurs thématiques avec champs hexadécimaux -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="primary_color" class="form-label fw-medium">
                                        Couleur principale <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror"
                                               id="primary_color" name="primary_color"
                                               value="{{ old('primary_color', $formData['primary_color']) }}" required>
                                        <span class="input-group-text">#</span>
                                        <input type="text" class="form-control @error('primary_color_hex') is-invalid @enderror"
                                               id="primary_color_hex" name="primary_color_hex"
                                               value="{{ old('primary_color_hex', str_replace('#', '', $formData['primary_color'])) }}"
                                               pattern="^[A-Fa-f0-9]{6}$" maxlength="6" placeholder="FF5733">
                                    </div>
                                    <div class="form-text">Format: 6 caractères hexadécimaux (ex: FF5733)</div>
                                    @error('primary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('primary_color_hex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="secondary_color" class="form-label fw-medium">
                                        Couleur secondaire <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror"
                                               id="secondary_color" name="secondary_color"
                                               value="{{ old('secondary_color', $formData['secondary_color']) }}" required>
                                        <span class="input-group-text">#</span>
                                        <input type="text" class="form-control @error('secondary_color_hex') is-invalid @enderror"
                                               id="secondary_color_hex" name="secondary_color_hex"
                                               value="{{ old('secondary_color_hex', str_replace('#', '', $formData['secondary_color'])) }}"
                                               pattern="^[A-Fa-f0-9]{6}$" maxlength="6" placeholder="33C3FF">
                                    </div>
                                    <div class="form-text">Format: 6 caractères hexadécimaux (ex: 33C3FF)</div>
                                    @error('secondary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('secondary_color_hex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between mt-4">
                                @if($isEdit)
                                    <button type="button" class="btn btn-danger" onclick="confirmerSuppressionUnivers()">
                                        <i class="bi bi-trash"></i> Supprimer la carte
                                    </button>
                                @else
                                    <div></div>
                                @endif
                                <div class="d-flex gap-2">
                                    <a href="{{ url('/') }}" class="btn btn-secondary">Annuler</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-{{ $isEdit ? 'check-lg' : 'plus-lg' }}"></i>
                                        {{ $isEdit ? 'Modifier la carte' : 'Créer la carte' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Aperçu à droite (sticky) -->
                    <div class="col-md-4">
                        <div class="sticky-top" style="top: 2rem;">
                            <h5 class="fw-bold mb-3">Aperçu en temps réel</h5>

                            <!-- Aperçu principal -->
                            <div id="preview" class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold fs-5 mb-3"
                                 style="height: 120px; background: linear-gradient(to right, {{ old('primary_color', $formData['primary_color']) }}, {{ old('secondary_color', $formData['secondary_color']) }});">
                                {{ old('name', $formData['name'] ?: 'Nom de la carte') }}
                            </div>

                            <!-- Aperçu carte miniature -->
                            <div class="card shadow-sm">
                                <div class="card-header border-0 text-white text-center py-2" id="preview-header" style="background: linear-gradient(135deg, {{ old('primary_color', $formData['primary_color']) }} 0%, {{ old('secondary_color', $formData['secondary_color']) }} 100%);">
                                    <h6 class="card-title mb-0 fw-bold" id="preview-title">{{ old('name', $formData['name'] ?: 'Nom de la carte') }}</h6>
                                </div>

                                <!-- Zone d'image avec prévisualisation -->
                                <div id="preview-image-container" style="height: 150px; position: relative; overflow: hidden;">
                                    @if($isEdit && $formData['image'])
                                        <img id="preview-image" src="{{ asset('storage/' . $formData['image']) }}" alt="Aperçu" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div id="preview-image" class="d-flex align-items-center justify-content-center text-white fs-1 fw-bold h-100" style="background: linear-gradient(45deg, {{ old('primary_color', $formData['primary_color']) }} 0%, {{ old('secondary_color', $formData['secondary_color']) }} 100%);">
                                            <i class="bi bi-stars opacity-75"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body p-3">
                                    <p class="card-text text-muted small mb-2" id="preview-description">{{ Str::limit(old('description', $formData['description'] ?: 'Description de la carte...'), 80) }}</p>

                                    <!-- Indicateurs de couleurs dans l'aperçu -->
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2">Couleurs:</small>
                                        <div class="d-flex gap-1">
                                            <div class="rounded-circle border border-white shadow-sm" id="preview-primary-color"
                                                 style="width: 20px; height: 20px; background-color: {{ old('primary_color', $formData['primary_color']) }};"></div>
                                            <div class="rounded-circle border border-white shadow-sm" id="preview-secondary-color"
                                                 style="width: 20px; height: 20px; background-color: {{ old('secondary_color', $formData['secondary_color']) }};"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo en superposition -->
                                <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;" id="preview-logo-container">
                                    @if($isEdit && $formData['logo'])
                                        <img id="preview-logo" src="{{ asset('storage/' . $formData['logo']) }}" alt="Logo"
                                             class="rounded-circle border border-2 border-white shadow"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($isEdit)
<script>
function confirmerSuppressionUnivers() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette carte ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("univers.destroy", $univers->id) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function supprimerImage() {
    if (confirm('Êtes-vous sûr de vouloir supprimer l\'image de cette carte ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("univers.remove-image", $univers->id) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function supprimerLogo() {
    if (confirm('Êtes-vous sûr de vouloir supprimer le logo de cette carte ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("univers.remove-logo", $univers->id) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endif

<script>
// Mise à jour de l'aperçu en temps réel avec synchronisation des couleurs hex
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const primaryColorInput = document.getElementById('primary_color');
    const secondaryColorInput = document.getElementById('secondary_color');
    const primaryColorHexInput = document.getElementById('primary_color_hex');
    const secondaryColorHexInput = document.getElementById('secondary_color_hex');
    const imageInput = document.getElementById('image');
    const logoInput = document.getElementById('logo');

    const preview = document.getElementById('preview');
    const previewHeader = document.getElementById('preview-header');
    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const previewImage = document.getElementById('preview-image');
    const previewLogoContainer = document.getElementById('preview-logo-container');
    const previewPrimaryColor = document.getElementById('preview-primary-color');
    const previewSecondaryColor = document.getElementById('preview-secondary-color');

    // Fonction pour valider et formater le hex
    function validateAndFormatHex(value) {
        value = value.replace(/[^A-Fa-f0-9]/g, '');
        value = value.substring(0, 6);
        return value.toUpperCase();
    }

    // Fonction pour synchroniser les champs couleur
    function syncColorInputs(colorInput, hexInput) {
        // Du sélecteur vers le hex
        colorInput.addEventListener('input', function() {
            const hexValue = this.value.substring(1);
            hexInput.value = hexValue.toUpperCase();
            updatePreview();
        });

        // Du hex vers le sélecteur
        hexInput.addEventListener('input', function() {
            let hexValue = validateAndFormatHex(this.value);
            this.value = hexValue;

            if (hexValue.length === 6) {
                colorInput.value = '#' + hexValue;
                updatePreview();
            }
        });

        // Validation lors de la perte de focus
        hexInput.addEventListener('blur', function() {
            let hexValue = validateAndFormatHex(this.value);
            if (hexValue.length < 6) {
                hexValue = hexValue.padEnd(6, '0');
            }
            this.value = hexValue;
            colorInput.value = '#' + hexValue;
            updatePreview();
        });
    }

    if (preview && nameInput && primaryColorInput && secondaryColorInput) {
        // Synchroniser les champs de couleurs
        syncColorInputs(primaryColorInput, primaryColorHexInput);
        syncColorInputs(secondaryColorInput, secondaryColorHexInput);

        function updatePreview() {
            const name = nameInput.value || 'Nom de la carte';
            const description = descriptionInput.value || 'Description de la carte...';
            const primaryColor = primaryColorInput.value;
            const secondaryColor = secondaryColorInput.value;

            // Mise à jour de l'aperçu principal
            preview.textContent = name;
            preview.style.background = `linear-gradient(to right, ${primaryColor}, ${secondaryColor})`;

            // Mise à jour de l'aperçu carte
            previewTitle.textContent = name;
            previewDescription.textContent = description.length > 80 ? description.substring(0, 80) + '...' : description;
            previewHeader.style.background = `linear-gradient(135deg, ${primaryColor} 0%, ${secondaryColor} 100%)`;
            if (previewPrimaryColor) previewPrimaryColor.style.backgroundColor = primaryColor;
            if (previewSecondaryColor) previewSecondaryColor.style.backgroundColor = secondaryColor;

            // Mise à jour du gradient de l'image si pas d'image uploadée
            if (previewImage && previewImage.tagName === 'DIV') {
                previewImage.style.background = `linear-gradient(45deg, ${primaryColor} 0%, ${secondaryColor} 100%)`;
            }
        }

        // Fonction pour prévisualiser l'image
        function previewImageFile(input, targetElement) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    targetElement.innerHTML = `<img src="${e.target.result}" alt="Aperçu" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Fonction pour prévisualiser le logo
        function previewLogoFile(input, targetContainer) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let logoImg = targetContainer.querySelector('#preview-logo');
                    if (!logoImg) {
                        logoImg = document.createElement('img');
                        logoImg.id = 'preview-logo';
                        logoImg.className = 'rounded-circle border border-2 border-white shadow';
                        logoImg.style.cssText = 'width: 40px; height: 40px; object-fit: cover;';
                        logoImg.alt = 'Logo';
                        targetContainer.appendChild(logoImg);
                    }
                    logoImg.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Event listeners
        nameInput.addEventListener('input', updatePreview);
        descriptionInput.addEventListener('input', updatePreview);

        // Event listeners pour les images
        imageInput.addEventListener('change', function() {
            previewImageFile(this, document.getElementById('preview-image-container'));
        });

        logoInput.addEventListener('change', function() {
            previewLogoFile(this, previewLogoContainer);
        });

        // Mise à jour initiale
        updatePreview();
    }
});
</script>
@endsection
