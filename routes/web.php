<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\PlotController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// API route for dynamic plot data - using database
Route::get('/api/plots', [PlotController::class, 'index']);
Route::get('/api/plots/statistics', [PlotController::class, 'statistics']);
Route::get('/api/plots/{plotId}', [PlotController::class, 'show']);

// Booking routes
Route::post('/api/booking/submit', [BookingController::class, 'submitBooking']);
Route::delete('/api/booking/cancel/{plotId}', [BookingController::class, 'cancelBooking']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
