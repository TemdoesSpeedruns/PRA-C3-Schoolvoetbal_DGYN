<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Laatste uitslagen
Route::get('/uitslagen', [ResultController::class, 'index'])->name('results.index');

// Historie
Route::get('/historie', [ResultController::class, 'history'])->name('results.history');

// Formulier tonen (GET) – zonder auth
Route::get('/uitslagen/toevoegen', [ResultController::class, 'create'])->name('results.create');

// Opslaan (POST) – zonder auth
Route::post('/uitslagen/toevoegen', [ResultController::class, 'store'])->name('results.store');
