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
        return view('vue', compact('listeUnivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Définir les couleurs par défaut et autres valeurs
        $defaultColors = $this->getRandomColors();
        $formData = [
            'name' => '',
            'description' => '',
            'primary_color' => $defaultColors['primary'],
            'secondary_color' => $defaultColors['secondary'],
            'image' => null,
            'logo' => null
        ];

        $univers = null; // Pas d'univers existant pour la création
        $isEdit = false;

        return view('univers.form', compact('formData', 'univers', 'isEdit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateUniversData($request);

        $univers = new Univers();
        $this->fillUniversData($univers, $validatedData, $request);
        $univers->save();

        return redirect('/')->with('message', 'Carte ajoutée avec succès !');
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

        // Préparer les données pour le formulaire
        $formData = [
            'name' => $univers->name,
            'description' => $univers->description,
            'primary_color' => $univers->primary_color,
            'secondary_color' => $univers->secondary_color,
            'image' => $univers->image,
            'logo' => $univers->logo
        ];

        $isEdit = true;

        return view('univers.form', compact('univers', 'formData', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $univers = Univers::findOrFail($id);
        $validatedData = $this->validateUniversData($request);

        $this->fillUniversData($univers, $validatedData, $request);
        $univers->save();

        return redirect('/')->with('message', 'Carte modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $univers = Univers::findOrFail($id);

        // Supprimer les fichiers associés
        if ($univers->image && Storage::disk('public')->exists($univers->image)) {
            Storage::disk('public')->delete($univers->image);
        }

        if ($univers->logo && Storage::disk('public')->exists($univers->logo)) {
            Storage::disk('public')->delete($univers->logo);
        }

        $univers->delete();

        return redirect('/')->with('message', 'Carte supprimée avec succès !');
    }

    /**
     * Supprimer uniquement l'image d'un univers
     */
    public function removeImage($id)
    {
        $univers = Univers::findOrFail($id);

        if ($univers->image && Storage::disk('public')->exists($univers->image)) {
            Storage::disk('public')->delete($univers->image);
            $univers->image = null;
            $univers->save();
        }

        return redirect()->back()->with('message', 'Image supprimée avec succès !');
    }

    /**
     * Supprimer uniquement le logo d'un univers
     */
    public function removeLogo($id)
    {
        $univers = Univers::findOrFail($id);

        if ($univers->logo && Storage::disk('public')->exists($univers->logo)) {
            Storage::disk('public')->delete($univers->logo);
            $univers->logo = null;
            $univers->save();
        }

        return redirect()->back()->with('message', 'Logo supprimé avec succès !');
    }

    /**
     * Valider les données d'un univers
     */
    private function validateUniversData(Request $request)
    {
        // Traitement des couleurs hexadécimales avant validation
        $this->processHexColors($request);

        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'primary_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ], [
            'name.required' => 'Le nom de la carte est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'primary_color.regex' => 'La couleur principale doit être au format hexadécimal.',
            'secondary_color.regex' => 'La couleur secondaire doit être au format hexadécimal.',
            'image.image' => 'Le fichier image doit être une image valide.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'logo.max' => 'Le logo ne doit pas dépasser 1MB.'
        ]);
    }

    /**
     * Traiter les couleurs hexadécimales depuis les champs texte
     */
    private function processHexColors(Request $request)
    {
        // Si des valeurs hex sont fournies, les convertir en couleurs
        if ($request->has('primary_color_hex') && $request->primary_color_hex) {
            $hex = $this->sanitizeHex($request->primary_color_hex);
            if ($hex) {
                $request->merge(['primary_color' => '#' . $hex]);
            }
        }

        if ($request->has('secondary_color_hex') && $request->secondary_color_hex) {
            $hex = $this->sanitizeHex($request->secondary_color_hex);
            if ($hex) {
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

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($univers->image && Storage::disk('public')->exists($univers->image)) {
                Storage::disk('public')->delete($univers->image);
            }
            $imagePath = $request->file('image')->store('univers/images', 'public');
            $univers->image = $imagePath;
        }

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($univers->logo && Storage::disk('public')->exists($univers->logo)) {
                Storage::disk('public')->delete($univers->logo);
            }
            $logoPath = $request->file('logo')->store('univers/logos', 'public');
            $univers->logo = $logoPath;
        }
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
        $univers = Univers::all();
        $colorUsage = [];

        foreach ($univers as $u) {
            $colorUsage[] = [
                'primary' => $u->primary_color,
                'secondary' => $u->secondary_color,
                'name' => $u->name
            ];
        }

        return $colorUsage;
    }
}
