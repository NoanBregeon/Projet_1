<?php

namespace App\Http\Controllers;

use App\Models\Univers;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function toggle(Univers $univers): JsonResponse
    {
        $user = auth()->user();
        $isFavorite = $user->hasFavorite($univers->id);

        if ($isFavorite) {
            $user->favorites()->detach($univers->id);
            $message = app()->getLocale() == 'en' ? 'Removed from favorites' : 'Retiré des favoris';
            $action = 'removed';
        } else {
            $user->favorites()->attach($univers->id);
            $message = app()->getLocale() == 'en' ? 'Added to favorites' : 'Ajouté aux favoris';
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => $action,
            'is_favorite' => ! $isFavorite,
            'favorites_count' => $univers->favoritesCount(),
        ]);
    }

    public function index()
    {
        $favorites = auth()->user()->favorites()->get();

        $viewConfig = [
            'messages' => [
                'title' => app()->getLocale() == 'en' ? 'My Favorites' : 'Mes Favoris',
                'subtitle' => app()->getLocale() == 'en' ? 'Your favorite cards collection' : 'Votre collection de cartes favorites',
                'empty_title' => app()->getLocale() == 'en' ? 'No favorite cards yet' : 'Aucune carte favorite pour le moment',
                'empty_subtitle' => app()->getLocale() == 'en' ? 'Add cards to your favorites to see them here!' : 'Ajoutez des cartes à vos favoris pour les voir ici !',
            ],
            'styles' => [
                'card_image_height' => '200px',
                'color_indicator_size' => '20px',
                'logo_size' => '60px',
            ],
        ];

        $universService = app(\App\Services\UniversService::class);
        $processedUnivers = $universService->processUniversForDisplay($favorites);

        return view('favorites.index', compact('processedUnivers', 'viewConfig'));
    }
}
