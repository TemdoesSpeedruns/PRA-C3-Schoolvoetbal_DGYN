<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolApprovalController;
use App\Http\Controllers\SchoolRegistrationController;

// Welcome / Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Current tournament results
Route::get('/uitslagen', [ResultController::class, 'index'])->name('results');

// Past winners / historical tournaments
Route::get('/historie', [ResultController::class, 'history'])->name('historie');

// Dashboard (auth + verified)
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Dashboard (auth + admin)
Route::get('/AdminDashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('AdminDashboard');

// Manage Users (admin only)
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/manage-users', [AdminUserController::class, 'index'])->name('manage.users');
    Route::post('/manage-users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('manage.users.toggleAdmin');
});

// Authenticated user profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Publieke registratie
Route::get('/scholen/registreren', [SchoolRegistrationController::class, 'showForm'])->name('schools.register.form');
Route::post('/scholen/registreren', [SchoolRegistrationController::class, 'register'])->name('schools.register');

// Admin routes (zorg dat middleware is geregistreerd)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/scholen', [SchoolApprovalController::class, 'index'])->name('schools.index');
    Route::post('/scholen/{id}/approve', [SchoolApprovalController::class, 'approve'])->name('schools.approve');
    Route::post('/scholen/{id}/reject', [SchoolApprovalController::class, 'reject'])->name('schools.reject');
});
// Include Breeze / auth routes
require __DIR__.'/auth.php';
