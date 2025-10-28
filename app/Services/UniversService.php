<?php

namespace App\Services;

use App\Contracts\UniversRepositoryInterface;
use App\Models\Univers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversService
{
    protected $universRepository;

    public function __construct(UniversRepositoryInterface $universRepository)
    {
        $this->universRepository = $universRepository;
    }

    public function processUniversForDisplay($listeUnivers)
    {
        return $listeUnivers->map(function ($univers) {
            $isAuth = auth()->check();
            $locale = app()->getLocale();

            return [
                'id' => $univers->id,
                'name' => $univers->name,
                'description' => $univers->description,
                'truncated_description' => $isAuth
                    ? $this->truncateDescription($univers->description, 100)
                    : $univers->description,
                'primary_color' => $univers->primary_color,
                'secondary_color' => $univers->secondary_color,
                'image' => $univers->image,
                'logo' => $univers->logo,
                'image_url' => $univers->image ? asset('storage/'.$univers->image) : null,
                'logo_url' => $univers->logo ? asset('storage/'.$univers->logo) : null,
                'gradient_header' => $this->generateGradient($univers->primary_color, $univers->secondary_color, '135deg'),
                'gradient_background' => $this->generateGradient($univers->primary_color, $univers->secondary_color, '45deg'),
                'color_tooltips' => [
                    'primary' => $locale === 'en' ? "Primary color: {$univers->primary_color}" : "Couleur primaire: {$univers->primary_color}",
                    'secondary' => $locale === 'en' ? "Secondary color: {$univers->secondary_color}" : "Couleur secondaire: {$univers->secondary_color}",
                ],
                'edit_url' => route('univers.edit', $univers->id),
            ];
        });
    }

    public function createUnivers(array $validatedData, Request $request): Univers
    {
        $univers = new Univers;
        $this->fillUniversData($univers, $validatedData, $request);
        $univers->save();

        return $univers;
    }

    public function updateUnivers(Univers $univers, array $validatedData, Request $request): bool
    {
        $this->fillUniversData($univers, $validatedData, $request);

        return $univers->save();
    }

    public function deleteUnivers(Univers $univers): bool
    {
        $this->deleteUniversFiles($univers);

        return $univers->delete();
    }

    // Méthodes privées pour la logique métier
    private function truncateDescription($description, $limit = 80)
    {
        if (strlen($description) > $limit) {
            return substr($description, 0, $limit).'...';
        }

        return $description;
    }

    private function generateGradient($primaryColor, $secondaryColor, $direction = 'to right')
    {
        return "linear-gradient({$direction}, {$primaryColor}, {$secondaryColor})";
    }

    private function fillUniversData(Univers $univers, array $validatedData, Request $request)
    {
        $univers->name = $validatedData['name'];
        $univers->description = $validatedData['description'];
        $univers->primary_color = $validatedData['primary_color'];
        $univers->secondary_color = $validatedData['secondary_color'];

        $this->handleFileUpload($univers, $request, 'image', 'univers/images');
        $this->handleFileUpload($univers, $request, 'logo', 'univers/logos');
    }

    private function handleFileUpload(Univers $univers, Request $request, $fieldName, $storagePath)
    {
        if ($request->hasFile($fieldName)) {
            $this->deleteFile($univers->$fieldName);
            $filePath = $request->file($fieldName)->store($storagePath, 'public');
            $univers->$fieldName = $filePath;
        }
    }

    private function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function deleteUniversFiles(Univers $univers)
    {
        $this->deleteFile($univers->image);
        $this->deleteFile($univers->logo);
    }
}
