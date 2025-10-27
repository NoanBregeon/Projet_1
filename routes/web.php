<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UniversController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes resource pour les univers (sauf show qui est accessible à tous)
    Route::resource('univers', UniversController::class)->except(['index', 'show']);

    // Routes personnalisées pour supprimer individuellement image et logo
    Route::delete('/univers/{univers}/image', [UniversController::class, 'removeImage'])->name('univers.remove-image');
    Route::delete('/univers/{univers}/logo', [UniversController::class, 'removeLogo'])->name('univers.remove-logo');
});

// Route show accessible à tous (en dehors du middleware auth)
Route::get('/univers/{univers}', [UniversController::class, 'show'])->name('univers.show');

require __DIR__.'/auth.php';
