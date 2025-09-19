<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard for authenticated users, login for guests
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'attemptLogin'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'attemptRegister'])->name('register.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Application Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('subjects', SubjectController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('topics', TopicController::class);

    Route::post('/topics/{topic}/complete', [TopicController::class, 'complete'])->name('topics.complete');
    Route::patch('/topics/{topic}/progress', [TopicController::class, 'updateProgress'])->name('topics.updateProgress');

    // Study Session Routes
    Route::resource('sessions', StudySessionController::class);
    Route::post('/sessions/{session}/start', [StudySessionController::class, 'start'])->name('sessions.start');
    Route::post('/sessions/{session}/pause', [StudySessionController::class, 'pause'])->name('sessions.pause');
    Route::post('/sessions/{session}/resume', [StudySessionController::class, 'resume'])->name('sessions.resume');
    Route::post('/sessions/{session}/complete', [StudySessionController::class, 'complete'])->name('sessions.complete');
    Route::post('/sessions/{session}/abandon', [StudySessionController::class, 'abandon'])->name('sessions.abandon');
    Route::get('/sessions/{session}/active', [StudySessionController::class, 'active'])->name('sessions.active');
    Route::get('/sessions/analytics', [StudySessionController::class, 'analytics'])->name('sessions.analytics');
    Route::get('/sessions/current', [StudySessionController::class, 'current'])->name('sessions.current');

    // User Profile and Settings Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/settings', [UserController::class, 'settings'])->name('user.settings');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
});
