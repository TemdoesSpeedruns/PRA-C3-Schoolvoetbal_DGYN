<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;

// Welcome / Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Current tournament results
Route::get('/uitslagen', [ResultController::class, 'index'])->name('results');

// Past winners / historical tournaments
Route::get('/historie', [ResultController::class, 'history'])->name('historie');

// Dashboard (auth + verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard (auth + verified)
Route::get('/AdminDashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('AdminDashboard');

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Breeze / auth routes
require __DIR__.'/auth.php';
