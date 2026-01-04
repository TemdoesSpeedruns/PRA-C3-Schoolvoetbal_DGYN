<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\TournamentController;
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

// My pool / group assignment
Route::get('/mijn-poule', [App\Http\Controllers\PublicPoolController::class, 'myPool'])->name('my.pool');

// Schedule / Wedstrijdschema (PUBLIC - geen login nodig)
Route::get('/schema', function() {
    $matches = \App\Models\GameMatch::with(['tournament', 'homeSchool', 'awaySchool', 'field', 'referee'])
        ->whereNotNull('scheduled_time')
        ->orderBy('scheduled_time', 'asc')
        ->get();
    $tournaments = \App\Models\Tournament::all();
    $fields = \App\Models\Field::where('is_active', true)->get();
    return view('schedule.public', compact('matches', 'tournaments', 'fields'));
})->name('schedule.public');

// Publieke score weergave
Route::get('/scores', [ScoreController::class, 'viewPublic'])->name('public.scores');

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
Route::get('/admin/schools/register', [SchoolRegistrationController::class, 'showForm'])->name('admin.schools.register');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Main Admin Panel (all in one)
    Route::get('/panel', [App\Http\Controllers\AdminPanelController::class, 'index'])->name('panel');
    
    // Pools / Groups
    Route::get('/poules', [App\Http\Controllers\PoolController::class, 'index'])->name('pools.index');
    
    // Tournaments
    Route::get('/toernooien', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/toernooien/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::patch('/toernooien/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');

    // School approval
    Route::get('/scholen', [SchoolApprovalController::class, 'index'])->name('schools.index');
    Route::get('/scholen/{school}/edit', [SchoolApprovalController::class, 'edit'])->name('schools.edit');
    Route::patch('/scholen/{school}', [SchoolApprovalController::class, 'update'])->name('schools.update');
    Route::post('/scholen/{id}/approve', [SchoolApprovalController::class, 'approve'])->name('schools.approve');
    Route::post('/scholen/{id}/reject', [SchoolApprovalController::class, 'reject'])->name('schools.reject');
    Route::delete('/scholen/{id}', [SchoolApprovalController::class, 'destroy'])->name('schools.destroy');
    
    // Scores invoeren
    Route::get('/scores', [ScoreController::class, 'index'])->name('scores.index');
    Route::get('/scores/create-match', [ScoreController::class, 'createMatch'])->name('scores.create');
    Route::post('/scores/matches', [ScoreController::class, 'storeMatch'])->name('scores.store');
    Route::get('/scores/{match}/edit', [ScoreController::class, 'edit'])->name('scores.edit');
    Route::patch('/scores/{match}', [ScoreController::class, 'update'])->name('scores.update');
    Route::delete('/scores/{match}', [ScoreController::class, 'destroy'])->name('scores.destroy');

    // Schedule / Wedstrijdschema
    Route::get('/schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule/{match}/schedule', [\App\Http\Controllers\ScheduleController::class, 'schedule'])->name('schedule.schedule');
    Route::post('/schedule/{match}/unschedule', [\App\Http\Controllers\ScheduleController::class, 'unschedule'])->name('schedule.unschedule');
    
    // Fields / Velden beheer
    Route::get('/schedule/fields', [\App\Http\Controllers\ScheduleController::class, 'fieldsIndex'])->name('schedule.fields.index');
    Route::post('/schedule/fields', [\App\Http\Controllers\ScheduleController::class, 'fieldStore'])->name('schedule.fields.store');
    
    // Referees / Scheidsrechters beheer
    Route::get('/schedule/referees', [\App\Http\Controllers\ScheduleController::class, 'refereesIndex'])->name('schedule.referees.index');
    Route::post('/schedule/referees', [\App\Http\Controllers\ScheduleController::class, 'refereeStore'])->name('schedule.referees.store');
});

// Include Breeze / auth routes
require __DIR__.'/auth.php';
