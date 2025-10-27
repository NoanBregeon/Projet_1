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

    // Routes pour les univers
    Route::resource('univers', UniversController::class)->except(['show']);
});

Route::get('/univers/{id}', [UniversController::class, 'show'])->name('univers.show');

require __DIR__.'/auth.php';
