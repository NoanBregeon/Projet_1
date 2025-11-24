<?php

namespace App\Http\Controllers;

use App\Models\Univers;
use App\Repositories\UniversRepository;
use App\Services\UniversService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UniversController extends Controller
{
    public function __construct(
        private readonly UniversRepository $universRepository,
        private readonly UniversService $universService
    ) {
        $this->middleware(['auth', 'verified'])->except(['index']);
    }

    /**
     * Page d’accueil : liste des univers (guest + users).
     */
    public function index(): View
    {
        $listeUnivers = $this->universRepository->all();
        $controller = $this;

        $processedUnivers = $listeUnivers->map(function ($univers) {
            // ...existing mapping...
        });

        $viewConfig = $this->getIndexViewConfig();

        return view('univers.index', [
            'listeUnivers' => $listeUnivers,
            'processedUnivers' => $processedUnivers,
            'viewConfig' => $viewConfig,
            'isEmpty' => $listeUnivers->isEmpty(),
        ]);
    }

    /**
     * Dashboard admin (déjà dans tes routes, on le laisse tel quel).
     */
    public function dashboard(): View
    {
        $univers = $this->universRepository->all();
        $processedUnivers = $this->universService->processUniversForDisplay($univers);

        $viewConfig = [
            'display' => [
                'show_description' => true,
                'truncate_length' => null,
                'show_favorites' => true,
                'show_colors' => true,
                'show_images' => true,
                'show_logos' => true,
            ],
            'styles' => [
                'card_image_height' => '200px',
                'color_indicator_size' => '20px',
                'logo_size' => '60px',
            ],
        ];

        return view('univers.index', compact('processedUnivers', 'viewConfig'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        return view('univers.create');
    }

    /**
     * Enregistre un univers.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $this->validateUniversData($request);

            $univers = new Univers;
            $this->fillUniversData($univers, $validatedData, $request);
            $univers->save();

            return redirect()->route('univers.index')->with('message', 'Carte ajoutée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Les erreurs de validation sont automatiquement gérées par Laravel
            // Les old() values sont automatiquement flashées en session
            throw $e;
        }
    }

    /**
     * Formulaire d’édition.
     */
    public function edit(Univers $univers): View
    {
        return view('univers.edit', compact('univers'));
    }

    /**
     * Met à jour un univers.
     */
    public function update(Request $request, Univers $univers): RedirectResponse
    {
        try {
            $validatedData = $this->validateUniversData($request);

            $this->fillUniversData($univers, $validatedData, $request);
            $univers->save();

            return redirect()->route('univers.index')->with('message', 'Carte modifiée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rediriger vers le formulaire d'édition avec les erreurs
            throw $e;
        }
    }

    /**
     * Supprime un univers.
     */
    public function destroy(Univers $univers): RedirectResponse
    {
        $this->deleteUniversFiles($univers);
        $univers->delete();

        return redirect()->route('univers.index')->with('message', 'Carte supprimée avec succès !');
    }

    /**
     * Supprime uniquement l’image.
     */
    public function removeImage(Univers $univers): RedirectResponse
    {
        $this->deleteFile($univers->image);

        //  Utilisation directe au lieu de update()
        $univers->image = null;
        $univers->save();

        return redirect()->route('univers.edit', $univers)->with('message', 'Image supprimée avec succès !');
    }

    /**
     * Supprimer uniquement le logo d'un univers
     */
    public function removeLogo(Univers $univers): RedirectResponse
    {
        $this->deleteFile($univers->logo);

        //  Utilisation directe au lieu de update()
        $univers->logo = null;
        $univers->save();

        return redirect()->route('univers.edit', $univers)->with('message', 'Logo supprimé avec succès !');
    }
}
