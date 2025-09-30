<?php

namespace App\Http\Controllers;

use App\Models\Univers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listeUnivers = Univers::all();

        // Préparer les données pour la vue avec logique métier
        $processedUnivers = $listeUnivers->map(function ($univers) {
            return [
                'id' => $univers->id,
                'name' => $univers->name,
                'description' => $univers->description,
                'truncated_description' => $this->truncateDescription($univers->description, 100),
                'primary_color' => $univers->primary_color,
                'secondary_color' => $univers->secondary_color,
                'image' => $univers->image,
                'logo' => $univers->logo,
                'image_url' => $univers->image ? asset('storage/' . $univers->image) : null,
                'logo_url' => $univers->logo ? asset('storage/' . $univers->logo) : null,
                'gradient_header' => $this->generateGradient($univers->primary_color, $univers->secondary_color, '135deg'),
                'gradient_background' => $this->generateGradient($univers->primary_color, $univers->secondary_color, '45deg'),
                'color_tooltips' => [
                    'primary' => "Couleur primaire: {$univers->primary_color}",
                    'secondary' => "Couleur secondaire: {$univers->secondary_color}"
                ],
                'edit_url' => route('univers.modify', $univers->id)
            ];
        });

        $viewConfig = $this->getIndexViewConfig();

        return view('vue', [
            'listeUnivers' => $listeUnivers, // Pour la compatibilité
            'processedUnivers' => $processedUnivers,
            'viewConfig' => $viewConfig,
            'isEmpty' => $listeUnivers->isEmpty()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $defaultColors = $this->getRandomColors();
        $formData = $this->prepareFormData(null, $defaultColors);
        $jsConfig = $this->getJavaScriptConfig();

        return view('univers.form', [
            'formData' => $formData,
            'univers' => null,
            'isEdit' => false,
            'jsConfig' => $jsConfig,
            'viewHelpers' => $this->getViewHelpers()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateUniversData($request);

            $univers = new Univers();
            $this->fillUniversData($univers, $validatedData, $request);
            $univers->save();

            return redirect('/')->with('message', 'Carte ajoutée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Les erreurs de validation sont automatiquement gérées par Laravel
            // Les old() values sont automatiquement flashées en session
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $univers = Univers::findOrFail($id);
        return view('univers.show', compact('univers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $univers = Univers::findOrFail($id);
        $formData = $this->prepareFormData($univers);
        $jsConfig = $this->getJavaScriptConfig();
        $deleteRoutes = $this->getDeleteRoutes($univers->id);

        return view('univers.form', [
            'univers' => $univers,
            'formData' => $formData,
            'isEdit' => true,
            'jsConfig' => $jsConfig,
            'deleteRoutes' => $deleteRoutes,
            'viewHelpers' => $this->getViewHelpers()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $univers = Univers::findOrFail($id);
            $validatedData = $this->validateUniversData($request);

            $this->fillUniversData($univers, $validatedData, $request);
            $univers->save();

            return redirect('/')->with('message', 'Carte modifiée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rediriger vers le formulaire d'édition avec les erreurs
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $univers = Univers::findOrFail($id);
        $this->deleteUniversFiles($univers);
        $univers->delete();

        return redirect('/')->with('message', 'Carte supprimée avec succès !');
    }

    /**
     * Supprimer uniquement l'image d'un univers
     */
    public function removeImage($id)
    {
        $univers = Univers::findOrFail($id);
        $this->deleteFile($univers->image);

        // ✅ Utilisation directe au lieu de update()
        $univers->image = null;
        $univers->save();

        return redirect()->back()->with('message', 'Image supprimée avec succès !');
    }

    /**
     * Supprimer uniquement le logo d'un univers
     */
    public function removeLogo($id)
    {
        $univers = Univers::findOrFail($id);
        $this->deleteFile($univers->logo);

        // ✅ Utilisation directe au lieu de update()
        $univers->logo = null;
        $univers->save();

        return redirect()->back()->with('message', 'Logo supprimé avec succès !');
    }

    // ========== MÉTHODES PRIVÉES - LOGIQUE MÉTIER ==========

    /**
     * Préparer les données du formulaire avec gestion complète des old() values
     */
    private function prepareFormData($univers = null, $defaultColors = null)
    {
        // Récupérer les couleurs par défaut si nécessaire
        $defaultColors = $defaultColors ?: $this->getRandomColors();

        if ($univers) {
            // Mode édition - prioriser old() puis les données de l'univers
            $data = [
                'name' => old('name', $univers->name),
                'description' => old('description', $univers->description),
                'primary_color' => old('primary_color', $univers->primary_color),
                'secondary_color' => old('secondary_color', $univers->secondary_color),
                'primary_color_hex' => old('primary_color_hex', $this->colorToHex($univers->primary_color)),
                'secondary_color_hex' => old('secondary_color_hex', $this->colorToHex($univers->secondary_color)),
                'image' => $univers->image,
                'logo' => $univers->logo
            ];
        } else {
            // Mode création - prioriser old() puis les valeurs par défaut
            $data = [
                'name' => old('name', ''),
                'description' => old('description', ''),
                'primary_color' => old('primary_color', $defaultColors['primary']),
                'secondary_color' => old('secondary_color', $defaultColors['secondary']),
                'primary_color_hex' => old('primary_color_hex', $this->colorToHex($defaultColors['primary'])),
                'secondary_color_hex' => old('secondary_color_hex', $this->colorToHex($defaultColors['secondary'])),
                'image' => null,
                'logo' => null
            ];
        }

        // Ajouter les données calculées pour l'aperçu
        $data['preview'] = $this->generatePreviewData($data, $univers);
        $data['gradients'] = $this->generateFormGradients($data);

        return $data;
    }

    /**
     * Générer les données d'aperçu
     */
    private function generatePreviewData($formData, $univers = null)
    {
        return [
            'title' => $formData['name'] ?: 'Nom de la carte',
            'description' => $this->truncateDescription($formData['description'] ?: 'Description de la carte...', 80),
            'primary_color' => $formData['primary_color'],
            'secondary_color' => $formData['secondary_color'],
            'image_url' => $univers && $univers->image ? asset('storage/' . $univers->image) : null,
            'logo_url' => $univers && $univers->logo ? asset('storage/' . $univers->logo) : null,
            'has_image' => $univers && $univers->image,
            'has_logo' => $univers && $univers->logo
        ];
    }

    /**
     * Générer les gradients pour le formulaire
     */
    private function generateFormGradients($formData)
    {
        return [
            'header' => $this->generateGradient($formData['primary_color'], $formData['secondary_color']),
            'preview_main' => $this->generateGradient($formData['primary_color'], $formData['secondary_color']),
            'preview_header' => $this->generateGradient($formData['primary_color'], $formData['secondary_color'], '135deg'),
            'preview_image' => $this->generateGradient($formData['primary_color'], $formData['secondary_color'], '45deg')
        ];
    }

    /**
     * Configuration JavaScript complète
     */
    private function getJavaScriptConfig()
    {
        return [
            'validation' => [
                'hex_pattern' => '^[A-Fa-f0-9]{6}$',
                'hex_max_length' => 6,
                'name_required' => 'Le nom est requis',
                'description_required' => 'La description est requise'
            ],
            'preview' => [
                'default_name' => 'Nom de la carte',
                'default_description' => 'Description de la carte...',
                'description_limit' => 80
            ],
            'selectors' => [
                'name_input' => '#name',
                'description_input' => '#description',
                'primary_color_input' => '#primary_color',
                'secondary_color_input' => '#secondary_color',
                'primary_color_hex_input' => '#primary_color_hex',
                'secondary_color_hex_input' => '#secondary_color_hex',
                'image_input' => '#image',
                'logo_input' => '#logo',
                'preview' => '#preview',
                'preview_header' => '#preview-header',
                'preview_title' => '#preview-title',
                'preview_description' => '#preview-description',
                'preview_image' => '#preview-image',
                'preview_image_container' => '#preview-image-container',
                'preview_logo_container' => '#preview-logo-container',
                'preview_primary_color' => '#preview-primary-color',
                'preview_secondary_color' => '#preview-secondary-color'
            ],
            'functions' => [
                'validate_hex' => 'validateAndFormatHex',
                'sync_colors' => 'syncColorInputs',
                'update_preview' => 'updatePreview',
                'preview_image' => 'previewImageFile',
                'preview_logo' => 'previewLogoFile'
            ]
        ];
    }

    /**
     * Configuration pour la vue index
     */
    private function getIndexViewConfig()
    {
        return [
            'messages' => [
                'empty_title' => 'Aucune carte dans votre collection',
                'empty_subtitle' => 'Commencez par créer votre première carte !',
                'empty_button' => 'Créer ma première carte',
                'add_title' => 'Ajouter une carte',
                'add_subtitle' => 'Créer une nouvelle carte pour votre collection',
                'add_button' => 'Créer'
            ],
            'routes' => [
                'add' => route('univers.add'),
                'edit' => 'univers.modify'
            ],
            'styles' => [
                'card_image_height' => '200px',
                'logo_size' => '50px',
                'color_indicator_size' => '30px'
            ],
            'limits' => [
                'description_truncate' => 100
            ]
        ];
    }

    /**
     * Helpers pour les vues
     */
    private function getViewHelpers()
    {
        return [
            'asset_helper' => function($path) {
                return asset('storage/' . $path);
            },
            'truncate_helper' => function($text, $limit = 100) {
                return $this->truncateDescription($text, $limit);
            },
            'gradient_helper' => function($primary, $secondary, $direction = 'to right') {
                return $this->generateGradient($primary, $secondary, $direction);
            },
            'hex_helper' => function($color) {
                return $this->colorToHex($color);
            }
        ];
    }

    /**
     * Obtenir les routes de suppression
     */
    private function getDeleteRoutes($universId)
    {
        return [
            'univers' => route('univers.destroy', $universId),
            'image' => route('univers.remove-image', $universId),
            'logo' => route('univers.remove-logo', $universId)
        ];
    }

    /**
     * Générer un gradient CSS
     */
    private function generateGradient($primaryColor, $secondaryColor, $direction = 'to right')
    {
        return "linear-gradient({$direction}, {$primaryColor}, {$secondaryColor})";
    }

    /**
     * Tronquer la description
     */
    private function truncateDescription($description, $limit = 80)
    {
        if (strlen($description) > $limit) {
            return substr($description, 0, $limit) . '...';
        }
        return $description;
    }

    /**
     * Convertir une couleur en hexadécimal
     */
    private function colorToHex($color)
    {
        return $color ? str_replace('#', '', $color) : '000000';
    }

    /**
     * Validation améliorée avec gestion des couleurs hex
     */
    private function validateUniversData(Request $request)
    {
        // Traitement des couleurs hexadécimales avant validation
        $this->processHexColors($request);

        return $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'primary_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'primary_color_hex' => 'nullable|string|regex:/^[A-Fa-f0-9]{6}$/',
            'secondary_color_hex' => 'nullable|string|regex:/^[A-Fa-f0-9]{6}$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'Le nom de la carte est obligatoire.',
            'name.max' => 'Le nom ne doit pas dépasser 30 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.max' => 'La description ne doit pas dépasser 255 caractères.',
            'primary_color.required' => 'La couleur principale est obligatoire.',
            'primary_color.regex' => 'La couleur principale doit être au format hexadécimal (#RRGGBB).',
            'secondary_color.required' => 'La couleur secondaire est obligatoire.',
            'secondary_color.regex' => 'La couleur secondaire doit être au format hexadécimal (#RRGGBB).',
            'primary_color_hex.regex' => 'Le code hexadécimal principal est invalide (format attendu: RRGGBB).',
            'secondary_color_hex.regex' => 'Le code hexadécimal secondaire est invalide (format attendu: RRGGBB).',
            'image.image' => 'Le fichier image doit être une image valide.',
            'image.mimes' => 'L\'image doit être au format JPG, PNG ou GIF.',
            'image.max' => 'L\'image ne doit pas dépasser 3MB.',
            'logo.image' => 'Le fichier logo doit être une image valide.',
            'logo.mimes' => 'Le logo doit être au format JPG, PNG ou GIF.',
            'logo.max' => 'Le logo ne doit pas dépasser 2MB.'
        ]);
    }

    /**
     * Traiter les couleurs hexadécimales avec validation robuste
     */
    private function processHexColors(Request $request)
    {
        // Traitement de la couleur principale
        if ($request->has('primary_color_hex') && !empty($request->input('primary_color_hex'))) {
            $hex = $this->sanitizeHex($request->input('primary_color_hex'));
            if ($hex && strlen($hex) === 6) {
                $request->merge(['primary_color' => '#' . $hex]);
            }
        }

        // Traitement de la couleur secondaire
        if ($request->has('secondary_color_hex') && !empty($request->input('secondary_color_hex'))) {
            $hex = $this->sanitizeHex($request->input('secondary_color_hex'));
            if ($hex && strlen($hex) === 6) {
                $request->merge(['secondary_color' => '#' . $hex]);
            }
        }
    }

    /**
     * Nettoyer et valider le format hexadécimal
     */
    private function sanitizeHex($hex)
    {
        // Supprimer le # s'il existe et nettoyer
        $hex = str_replace('#', '', $hex);
        $hex = preg_replace('/[^A-Fa-f0-9]/', '', $hex);

        // Compléter si moins de 6 caractères
        if (strlen($hex) < 6) {
            $hex = str_pad($hex, 6, '0', STR_PAD_RIGHT);
        }

        // Limiter à 6 caractères
        $hex = substr($hex, 0, 6);

        return strlen($hex) === 6 ? strtoupper($hex) : null;
    }

    /**
     * Remplir les données d'un univers
     */
    private function fillUniversData(Univers $univers, array $validatedData, Request $request)
    {
        $univers->name = $validatedData['name'];
        $univers->description = $validatedData['description'];
        $univers->primary_color = $validatedData['primary_color'];
        $univers->secondary_color = $validatedData['secondary_color'];

        $this->handleFileUpload($univers, $request, 'image', 'univers/images');
        $this->handleFileUpload($univers, $request, 'logo', 'univers/logos');
    }

    /**
     * Gérer l'upload d'un fichier
     */
    private function handleFileUpload(Univers $univers, Request $request, $fieldName, $storagePath)
    {
        if ($request->hasFile($fieldName)) {
            $this->deleteFile($univers->$fieldName);
            $filePath = $request->file($fieldName)->store($storagePath, 'public');
            $univers->$fieldName = $filePath;
        }
    }

    /**
     * Supprimer un fichier
     */
    private function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    /**
     * Supprimer tous les fichiers d'un univers
     */
    private function deleteUniversFiles(Univers $univers)
    {
        $this->deleteFile($univers->image);
        $this->deleteFile($univers->logo);
    }

    /**
     * Générer des couleurs aléatoires harmonieuses
     */
    private function getRandomColors()
    {
        $colorPairs = [
            ['primary' => '#3B82F6', 'secondary' => '#10B981'], // Bleu/Vert
            ['primary' => '#8B5CF6', 'secondary' => '#F59E0B'], // Violet/Orange
            ['primary' => '#EF4444', 'secondary' => '#F97316'], // Rouge/Orange
            ['primary' => '#06B6D4', 'secondary' => '#8B5CF6'], // Cyan/Violet
            ['primary' => '#10B981', 'secondary' => '#F59E0B'], // Vert/Jaune
            ['primary' => '#F43F5E', 'secondary' => '#8B5CF6'], // Rose/Violet
        ];

        return $colorPairs[array_rand($colorPairs)];
    }

    /**
     * Obtenir les statistiques des couleurs utilisées
     */
    public function getColorStats()
    {
        return Univers::all()->map(function ($univers) {
            return [
                'primary' => $univers->primary_color,
                'secondary' => $univers->secondary_color,
                'name' => $univers->name
            ];
        })->toArray();
    }
}
