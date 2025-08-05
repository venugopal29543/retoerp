<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\PlotController;
use App\Http\Controllers\Api\PlotSyncController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// API route for dynamic plot data - using database
Route::get('/api/plots', [PlotController::class, 'index']);
Route::get('/api/plots/statistics', [PlotController::class, 'statistics']);
Route::get('/api/plots/{plotId}', [PlotController::class, 'show']);

// Plot sync API routes for real-time updates
Route::prefix('api/sync')->group(function () {
    Route::get('/plots', [PlotSyncController::class, 'getAllPlots']);
    Route::get('/statistics', [PlotSyncController::class, 'getStatistics']);
    Route::put('/plots/{plotId}', [PlotSyncController::class, 'updatePlot']);
});

// Plot management API routes for dashboard CRUD operations
Route::prefix('api/plots')->group(function () {
    Route::post('/update', [PlotSyncController::class, 'updatePlot']);
    Route::delete('/delete', [PlotSyncController::class, 'deletePlot']);
    Route::post('/create', [PlotSyncController::class, 'createPlot']);
});

// Booking routes
Route::post('/api/booking/submit', [BookingController::class, 'submitBooking']);
Route::delete('/api/booking/cancel/{plotId}', [BookingController::class, 'cancelBooking']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
