<?php

namespace App\Http\Controllers;

use App\Models\Univers;
use App\Services\UniversService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FavoriteController extends Controller
{
    protected UniversService $universService;

    // Accept a nullable service to allow the container to fallback gracefully
    public function __construct(?UniversService $universService = null)
    {
        $this->universService = $universService ?? app(UniversService::class);
    }

    /**
     * Toggle favorite pour l'utilisateur connecté.
     */
    public function toggle(Univers $univers): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // relation many-to-many attendue : favorites()
        if ($user->favorites()->where('univers_id', $univers->id)->exists()) {
            $user->favorites()->detach($univers->id);
            $message = app()->getLocale() === 'en' ? 'Removed from favorites' : 'Retiré des favoris';
        } else {
            $user->favorites()->attach($univers->id);
            $message = app()->getLocale() === 'en' ? 'Added to favorites' : 'Ajouté aux favoris';
        }

        return back()->with('message', $message);
    }

    /**
     * Page index des favoris de l'utilisateur.
     */
    public function index(): View
    {
        $user = auth()->user();

        if (! $user) {
            return view('favorites.index', ['processedUnivers' => collect()]);
        }

        $favorites = $user->favorites()->get();

        $processedUnivers = $this->universService->processUniversForDisplay($favorites);

        return view('favorites.index', [
            'processedUnivers' => $processedUnivers,
        ]);
    }
}
