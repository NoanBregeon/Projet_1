<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\TestController;
use App\Models\Test;


// Route::get('test', [AccueilController::class, 'test']);
Route::get('about/{message?}', [AccueilController::class, 'about'])->name('about');
Route::resource('test', TestController::class);
