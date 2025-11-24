<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', [UniversController::class, 'index'])->name('home');

// Route pour changer de langue
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Route de test email pour vérifier la configuration
Route::get('/test-email', function () {
    try {
        Mail::raw('🎉 Test email depuis Laravel avec OVH Cloud!\n\nCeci est un email de test pour vérifier que la configuration SMTP fonctionne correctement.\n\nEnvoyé le : '.now()->format('d/m/Y à H:i:s'), function ($message) {
            $message->to('test.dev@noanbregeon.com')
                ->subject('✅ Test Email OVH - '.now()->format('H:i:s'));
        });

        return '<div style="padding:2rem;background:#d4edda;color:#155724;border-radius:12px;font-family:Arial;max-width:600px;margin:2rem auto;border:1px solid #c3e6cb;">
                    <h2 style="margin:0 0 1rem 0;">✅ Email envoyé avec succès !</h2>
                    <p style="margin:0;">L\'email de test a été envoyé vers <strong>test.dev@noanbregeon.com</strong></p>
                    <p style="margin:1rem 0 0 0;font-size:0.9em;opacity:0.8;">Heure d\'envoi : '.now()->format('d/m/Y à H:i:s').'</p>
                </div>';
    } catch (\Exception $e) {
        return '<div style="padding:2rem;background:#f8d7da;color:#721c24;border-radius:12px;font-family:Arial;max-width:600px;margin:2rem auto;border:1px solid #f5c6cb;">
                    <h2 style="margin:0 0 1rem 0;">❌ Erreur d\'envoi</h2>
                    <p style="margin:0;"><strong>Message d\'erreur :</strong></p>
                    <pre style="background:#fff;padding:1rem;border-radius:8px;margin:1rem 0;overflow:auto;">'.$e->getMessage().'</pre>
                    <p style="margin:0;font-size:0.9em;">Vérifiez la configuration SMTP dans le fichier .env</p>
                </div>';
    }
})->name('test.email');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes nécessitant une vérification d'email
    Route::middleware('verified')->group(function () {
        Route::resource('univers', UniversController::class)->except(['index', 'show']);
        Route::delete('/univers/{univers}/image', [UniversController::class, 'removeImage'])->name('univers.remove-image');
        Route::delete('/univers/{univers}/logo', [UniversController::class, 'removeLogo'])->name('univers.remove-logo');
    });

    // Routes des favoris
    Route::get('/favorites', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{univers}/toggle', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/count', function () {
        return response()->json(['count' => Auth::user()->favorites()->count()]);
    })->name('favorites.count');

    // Routes pour favoris
    Route::middleware('auth')->group(function () {
        Route::post('favorites/{univers}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
        Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');

        // Routes de suppression image/logo pour un univers
        Route::delete('univers/{univers}/image', [UniversController::class, 'removeImage'])->name('univers.remove-image');
        Route::delete('univers/{univers}/logo', [UniversController::class, 'removeLogo'])->name('univers.remove-logo');
    });

    // Resource univers si pas déjà défini
    Route::resource('univers', UniversController::class);
});

// Route show accessible à tous
Route::get('/univers/{univers}', [UniversController::class, 'show'])->name('univers.show');

require __DIR__.'/auth.php';
