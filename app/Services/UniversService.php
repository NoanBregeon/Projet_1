<?php

namespace App\Services;

use App\Contracts\UniversRepositoryInterface;
use App\Models\Univers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UniversService
{
    /** @var UniversRepositoryInterface */
    protected $universRepository;

    public function __construct($universRepository = null)
    {
        // Accept either an implementation instance or resolve the interface via the container
        $this->universRepository = $universRepository ?? app(UniversRepositoryInterface::class);
    }

    /**
     * Prépare les univers pour l'affichage (cards, etc.).
     */
    public function processUniversForDisplay(Collection $listeUnivers): Collection
    {
        return $listeUnivers->map(function ($univers) {
            // Normaliser l'élément : Model, stdClass ou array -> objet
            if (is_array($univers)) {
                $univers = (object) $univers;
            }

            // si $univers est un stdClass ou Univers, on peut accéder aux propriétés
            $isAuth = auth()->check();
            $locale = app()->getLocale();

            $primary = $univers->primary_color ?? '#000000';
            $secondary = $univers->secondary_color ?? '#ffffff';
            $image = $univers->image ?? null;
            $logo = $univers->logo ?? null;

            return [
                'id' => $univers->id ?? null,
                'name' => $univers->name ?? null,
                'description' => $univers->description ?? null,
                'truncated_description' => $isAuth ? $this->truncateDescription((string) ($univers->description ?? ''), 100) : ($univers->description ?? ''),
                'primary_color' => $primary,
                'secondary_color' => $secondary,
                'image' => $image,
                'logo' => $logo,
                'image_url' => $image ? asset('storage/'.$image) : null,
                'logo_url' => $logo ? asset('storage/'.$logo) : null,
                'gradient_header' => $this->generateGradient((string) $primary, (string) $secondary, '135deg'),
                'gradient_background' => $this->generateGradient((string) $primary, (string) $secondary, '45deg'),
                'color_tooltips' => [
                    'primary' => $locale === 'en' ? "Primary color: {$primary}" : "Couleur primaire: {$primary}",
                    'secondary' => $locale === 'en' ? "Secondary color: {$secondary}" : "Couleur secondaire: {$secondary}",
                ],
                'edit_url' => isset($univers->id) ? route('univers.edit', $univers->id) : null,
            ];
        });
    }

    /**
     * Crée un univers à partir de données validées et d'une requête.
     */
    public function createUnivers(array $validatedData, Request $request): Univers
    {
        $univers = new Univers;

        $this->fillUniversData($univers, $validatedData, $request);

        $univers->save();

        return $univers;
    }

    /**
     * Met à jour un univers. Retourne true si OK.
     */
    public function updateUnivers(Univers $univers, array $validatedData, Request $request): bool
    {
        $this->fillUniversData($univers, $validatedData, $request);

        return $univers->save();
    }

    /**
     * Supprime un univers + fichiers associés. Retourne true si OK.
     */
    public function deleteUnivers(Univers $univers): bool
    {
        // suppression des fichiers si présents
        if ($univers->image && Storage::disk('public')->exists($univers->image)) {
            Storage::disk('public')->delete($univers->image);
        }

        if ($univers->logo && Storage::disk('public')->exists($univers->logo)) {
            Storage::disk('public')->delete($univers->logo);
        }

        return (bool) $univers->delete();
    }

    private function truncateDescription(string $description, int $limit = 80): string
    {
        if (strlen($description) > $limit) {
            return substr($description, 0, $limit).'...';
        }

        return $description;
    }

    private function generateGradient(string $primaryColor, string $secondaryColor, string $direction = 'to right'): string
    {
        return "linear-gradient({$direction}, {$primaryColor}, {$secondaryColor})";
    }

    /**
     * @param  array<string,mixed>  $validatedData
     */
    private function fillUniversData(Univers $univers, array $validatedData, Request $request): void
    {
        $univers->name = $validatedData['name'];
        $univers->description = $validatedData['description'] ?? null;
        $univers->primary_color = $validatedData['primary_color'];
        $univers->secondary_color = $validatedData['secondary_color'];

        // Image
        if ($request->hasFile('image')) {
            if ($univers->image && Storage::disk('public')->exists($univers->image)) {
                Storage::disk('public')->delete($univers->image);
            }

            $path = $request->file('image')->store('univers/images', 'public');
            $univers->image = $path;
        }

        // Logo
        if ($request->hasFile('logo')) {
            if ($univers->logo && Storage::disk('public')->exists($univers->logo)) {
                Storage::disk('public')->delete($univers->logo);
            }

            $path = $request->file('logo')->store('univers/logos', 'public');
            $univers->logo = $path;
        }

        // Si jamais la BDD a NOT NULL sur image/logo, on peut forcer une chaîne vide
        // pour éviter les erreurs SQL tant que la migration n'est pas corrigée :
        // $univers->image = $univers->image ?? '';
        // $univers->logo  = $univers->logo ?? '';
    }

    private function handleFileUpload(Univers $univers, Request $request, string $fieldName, string $storagePath): void
    {
        // ...existing code...
    }

    private function deleteFile(?string $filePath): void
    {
        // ...existing code...
    }

    private function deleteUniversFiles(Univers $univers): void
    {
        // ...existing code...
    }
}
