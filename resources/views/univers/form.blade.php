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
                <div class="text-center py-4 rounded-3 mb-4 text-white" style="background: {{ $formData['gradients']['header'] }};">
                    <h1 class="h3 fw-bold">
                        {{ $isEdit ? 'Modifier ' . $formData['name'] : 'Créer une nouvelle carte' }}
                    </h1>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ $isEdit ? route('univers.update', $univers->id) : route('univers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit) @method('PUT') @endif

                            <!--  Nom DANS le formulaire -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">
                                    Nom de la carte <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ $formData['name'] }}" required
                                       placeholder="Ex : ¯\_(ツ)_/¯">
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!--  Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-medium">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4" required>{{ $formData['description'] }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-x-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!--  Image DANS le formulaire -->
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

                            <div class="mb-3">
                                <label for="image" class="form-label fw-medium">{{ $isEdit ? 'Changer l\'image (optionnel)' : 'Image de la carte' }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <!--  Logo DANS le formulaire -->
                            @if($isEdit && $formData['logo'])
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Logo/Symbole actuel</label>
                                    <div class="d-flex align-items-start gap-3">
                                        <img src="{{ asset('storage/' . $formData['logo']) }}" alt="Logo" class="img-thumbnail" style="width: 64px; height: 64px; object-fit: cover;">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="supprimerLogo()">
                                            <i class="bi bi-trash me-1"></i>Supprimer le logo
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="logo" class="form-label fw-medium">{{ $isEdit ? 'Changer le logo/symbole (optionnel)' : 'Logo/Symbole (optionnel)' }}</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <!--  Couleurs Primaire DANS le formulaire -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="primary_color" class="form-label fw-medium">Couleur principale <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror"
                                               id="primary_color" name="primary_color" value="{{ $formData['primary_color'] }}" required>
                                        <span class="input-group-text">#</span>
                                        <input type="text" class="form-control @error('primary_color_hex') is-invalid @enderror"
                                               id="primary_color_hex" name="primary_color_hex" value="{{ $formData['primary_color_hex'] }}" maxlength="6">
                                    </div>
                                    @error('primary_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @error('primary_color_hex')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <!--  Couleurs Secondaire DANS le formulaire -->
                                <div class="col-md-6">
                                    <label for="secondary_color" class="form-label fw-medium">Couleur secondaire <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror"
                                               id="secondary_color" name="secondary_color" value="{{ $formData['secondary_color'] }}" required>
                                        <span class="input-group-text">#</span>
                                        <input type="text" class="form-control @error('secondary_color_hex') is-invalid @enderror"
                                               id="secondary_color_hex" name="secondary_color_hex" value="{{ $formData['secondary_color_hex'] }}" maxlength="6">
                                    </div>
                                    @error('secondary_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @error('secondary_color_hex')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <!--  Boutons de suppression -->
                                @if($isEdit)
                                    <button type="button" class="btn btn-danger" onclick="confirmerSuppressionUnivers()">
                                        <i class="bi bi-trash"></i> Supprimer la carte
                                    </button>
                                @endif
                                <!--  Boutons d'annulation et d'application (création/modifications) -->
                                <div class="d-flex gap-2 {{ !$isEdit ? 'ms-auto' : '' }}">
                                    <a href="{{ url('/') }}" class="btn btn-secondary">Annuler</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-{{ $isEdit ? 'check-lg' : 'plus-lg' }}"></i>
                                        {{ $isEdit ? 'Modifier la carte' : 'Créer la carte' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--  Aperçu en temps réel -->
                    <div class="col-md-4">
                        <div class="sticky-top" style="top: 2rem;">
                            <h5 class="fw-bold mb-3">Aperçu en temps réel</h5>

                            <div id="preview" class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold fs-5 mb-3"
                                 style="height: 120px; background: {{ $formData['gradients']['preview_main'] }};">
                                {{ $formData['preview']['title'] }}
                            </div>

                            <div class="card shadow-sm position-relative">
                                <div class="card-header border-0 text-white text-center py-2" id="preview-header" style="background: {{ $formData['gradients']['preview_header'] }};">
                                    <h6 class="card-title mb-0 fw-bold" id="preview-title">{{ $formData['preview']['title'] }}</h6>
                                </div>

                                <div id="preview-image-container" style="height: 150px; position: relative; overflow: hidden;">
                                    @if($formData['preview']['has_image'])
                                        <img id="preview-image" src="{{ $formData['preview']['image_url'] }}" alt="Aperçu" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div id="preview-image" class="d-flex align-items-center justify-content-center text-white fs-1 fw-bold h-100" style="background: {{ $formData['gradients']['preview_image'] }};">
                                            <i class="bi bi-stars opacity-75"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body p-3">
                                    <p class="card-text text-muted small mb-2" id="preview-description">{{ $formData['preview']['description'] }}</p>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2">Couleurs:</small>
                                        <div class="d-flex gap-1">
                                            <div class="rounded-circle border border-white shadow-sm" id="preview-primary-color" style="width: 20px; height: 20px; background-color: {{ $formData['primary_color'] }};"></div>
                                            <div class="rounded-circle border border-white shadow-sm" id="preview-secondary-color" style="width: 20px; height: 20px; background-color: {{ $formData['secondary_color'] }};"></div>
                                        </div>
                                    </div>
                                </div>

                                @if($formData['preview']['has_logo'])
                                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;" id="preview-logo-container">
                                        <img id="preview-logo" src="{{ $formData['preview']['logo_url'] }}" alt="Logo" class="rounded-circle border border-2 border-white shadow" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;" id="preview-logo-container"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const jsConfig = @json($jsConfig);
@if($isEdit)
const deleteRoutes = @json($deleteRoutes);
@endif
</script>

@if($isEdit)
<script>
// Fonctions de suppression avec confirmation
function confirmerSuppressionUnivers() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette carte ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteRoutes.univers;
        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}));
        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_method', value: 'DELETE'}));
        document.body.appendChild(form);
        form.submit();
    }
}
// Fonctions de suppression d'image et de logo avec confirmation
function supprimerImage() {
    if (confirm('Êtes-vous sûr de vouloir supprimer l\'image ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteRoutes.image;
        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}));
        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_method', value: 'DELETE'}));
        document.body.appendChild(form);
        form.submit();
    }
}
function supprimerLogo() {
    if (confirm('Êtes-vous sûr de vouloir supprimer le logo ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteRoutes.logo;
        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}));
        form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: '_method', value: 'DELETE'}));
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const elements = Object.fromEntries(
        Object.entries(jsConfig.selectors).map(([key, selector]) => [key, document.querySelector(selector)])
    );

    function validateAndFormatHex(value) {
        return value.replace(/[^A-Fa-f0-9]/g, '').substring(0, jsConfig.validation.hex_max_length).toUpperCase();
    }

    function syncColorInputs(colorInput, hexInput) {
        colorInput.addEventListener('input', () => {
            hexInput.value = colorInput.value.substring(1).toUpperCase();
            updatePreview();
        });
        hexInput.addEventListener('input', () => {
            let hexValue = validateAndFormatHex(hexInput.value);
            hexInput.value = hexValue;
            if (hexValue.length === jsConfig.validation.hex_max_length) {
                colorInput.value = '#' + hexValue;
                updatePreview();
            }
        });
        hexInput.addEventListener('blur', () => {
            let hexValue = validateAndFormatHex(hexInput.value);
            if (hexValue.length < jsConfig.validation.hex_max_length) {
                hexValue = hexValue.padEnd(jsConfig.validation.hex_max_length, '0');
            }
            hexInput.value = hexValue;
            colorInput.value = '#' + hexValue;
            updatePreview();
        });
    }

    function updatePreview() {
        const name = elements.name_input?.value || jsConfig.preview.default_name;
        const description = elements.description_input?.value || jsConfig.preview.default_description;
        const primaryColor = elements.primary_color_input?.value;
        const secondaryColor = elements.secondary_color_input?.value;

        if (elements.preview) elements.preview.textContent = name;
        if (elements.preview) elements.preview.style.background = `linear-gradient(to right, ${primaryColor}, ${secondaryColor})`;
        if (elements.preview_title) elements.preview_title.textContent = name;
        if (elements.preview_description) {
            elements.preview_description.textContent = description.length > jsConfig.preview.description_limit ?
                description.substring(0, jsConfig.preview.description_limit) + '...' : description;
        }
        if (elements.preview_header) elements.preview_header.style.background = `linear-gradient(135deg, ${primaryColor} 0%, ${secondaryColor} 100%)`;

        if (elements.preview_primary_color) elements.preview_primary_color.style.backgroundColor = primaryColor;
        if (elements.preview_secondary_color) elements.preview_secondary_color.style.backgroundColor = secondaryColor;
        if (elements.preview_image && elements.preview_image.tagName === 'DIV') {
            elements.preview_image.style.background = `linear-gradient(45deg, ${primaryColor} 0%, ${secondaryColor} 100%)`;
        }
    }

    function previewFile(input, targetContainer, isLogo = false) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (isLogo) {
                    let logoImg = targetContainer.querySelector('#preview-logo');
                    if (!logoImg) {
                        logoImg = Object.assign(document.createElement('img'), {
                            id: 'preview-logo',
                            className: 'rounded-circle border border-2 border-white shadow',
                            alt: 'Logo'
                        });
                        logoImg.style.cssText = 'width: 40px; height: 40px; object-fit: cover;';
                        targetContainer.appendChild(logoImg);
                    }
                    logoImg.src = e.target.result;
                } else {
                    targetContainer.innerHTML = `<img src="${e.target.result}" alt="Aperçu" style="width: 100%; height: 100%; object-fit: cover;">`;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    if (elements.preview) {
        syncColorInputs(elements.primary_color_input, elements.primary_color_hex_input);
        syncColorInputs(elements.secondary_color_input, elements.secondary_color_hex_input);

        elements.name_input?.addEventListener('input', updatePreview);
        elements.description_input?.addEventListener('input', updatePreview);
        elements.image_input?.addEventListener('change', () => previewFile(elements.image_input, elements.preview_image_container));
        elements.logo_input?.addEventListener('change', () => previewFile(elements.logo_input, elements.preview_logo_container, true));

        updatePreview();
    }
});
</script>
@endsection
