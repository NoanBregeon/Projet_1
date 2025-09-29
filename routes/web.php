<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversController;

// Page d'accueil avec la collection de cartes
Route::get('/', [UniversController::class, 'index'])->name('home');

// Routes pour la gestion des univers/cartes
Route::get('/univers/add', [UniversController::class, 'create'])->name('univers.add');
Route::post('/univers', [UniversController::class, 'store'])->name('univers.store');
Route::get('/univers/{id}/modify', [UniversController::class, 'edit'])->name('univers.modify');
Route::put('/univers/{id}', [UniversController::class, 'update'])->name('univers.update');
Route::get('/univers/{id}', [UniversController::class, 'show'])->name('univers.show');
Route::delete('/univers/{id}', [UniversController::class, 'destroy'])->name('univers.destroy');

// Routes pour supprimer individuellement image et logo
Route::delete('/univers/{id}/image', [UniversController::class, 'removeImage'])->name('univers.remove-image');
Route::delete('/univers/{id}/logo', [UniversController::class, 'removeLogo'])->name('univers.remove-logo');
