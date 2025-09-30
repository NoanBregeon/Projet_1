<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversController;

// Page d'accueil avec la collection de cartes
Route::get('/', [UniversController::class, 'index'])->name('home');

// Route resource pour UniversController
Route::resource('univers', UniversController::class)->parameters(['univers' => 'id']);

// Routes pour supprimer individuellement image et logo
Route::delete('/univers/{id}/image', [UniversController::class, 'removeImage'])->name('univers.remove-image');
Route::delete('/univers/{id}/logo', [UniversController::class, 'removeLogo'])->name('univers.remove-logo');

// Route personnalisée pour ajouter une carte (formulaire de création)
Route::get('/univers/add', [UniversController::class, 'create'])->name('univers.add');

// Route personnalisée pour modifier une carte (formulaire d'édition)
Route::get('/univers/{id}/modify', [UniversController::class, 'edit'])->name('univers.modify');
