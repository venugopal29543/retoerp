<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlotController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Plot API routes
Route::prefix('plots')->group(function () {
    Route::get('/', [PlotController::class, 'index']);
    Route::get('/statistics', [PlotController::class, 'statistics']);
    Route::get('/status/{status}', [PlotController::class, 'getByStatus']);
    Route::get('/block/{block}', [PlotController::class, 'getByBlock']);
    Route::get('/{plotId}', [PlotController::class, 'show']);
    Route::put('/{plotId}', [PlotController::class, 'update']);
});

// Booking API routes
Route::prefix('booking')->group(function () {
    Route::post('/submit', [BookingController::class, 'submitBooking']);
    Route::delete('/cancel/{plotId}', [BookingController::class, 'cancelBooking']);
});
