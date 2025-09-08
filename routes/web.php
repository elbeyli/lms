<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect to dashboard for authenticated users
Route::redirect('/', '/dashboard')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('subjects', SubjectController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('topics', TopicController::class);
    
    Route::post('/topics/{topic}/complete', [TopicController::class, 'complete'])->name('topics.complete');
    Route::patch('/topics/{topic}/progress', [TopicController::class, 'updateProgress'])->name('topics.updateProgress');
});

// Basic auth routes (for testing - normally would use Laravel Breeze/Jetstream)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');
