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

    // Routes pour tous les utilisateurs connectés (création seulement)
    Route::get('/univers/create', [UniversController::class, 'create'])->name('univers.create');
    Route::post('/univers', [UniversController::class, 'store'])->name('univers.store');
});

// Routes réservées aux admins seulement
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/univers/{univers}/edit', [UniversController::class, 'edit'])->name('univers.edit');
    Route::put('/univers/{univers}', [UniversController::class, 'update'])->name('univers.update');
    Route::delete('/univers/{univers}', [UniversController::class, 'destroy'])->name('univers.destroy');

    // Routes pour supprimer individuellement image et logo
    Route::delete('/univers/{univers}/image', [UniversController::class, 'removeImage'])->name('univers.remove-image');
    Route::delete('/univers/{univers}/logo', [UniversController::class, 'removeLogo'])->name('univers.remove-logo');
});

// Route show accessible à tous
Route::get('/univers/{univers}', [UniversController::class, 'show'])->name('univers.show');

require __DIR__.'/auth.php';
