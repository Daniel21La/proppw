<?php

use App\Http\Controllers\Api\v1\AuthApiController;
use App\Http\Controllers\Api\v1\BookingApiController;
use App\Http\Controllers\Api\v1\FleetApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware(['throttle:api'])->group(function () {

    // Auth API
    Route::post('/auth/login', [AuthApiController::class, 'login']);
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/me', [AuthApiController::class, 'me']);

    // Fleet / Car Catalog API
    Route::get('/fleet', [FleetApiController::class, 'index']);
    Route::get('/fleet/{id}', [FleetApiController::class, 'show']);
    Route::post('/fleet/{id}/check-availability', [FleetApiController::class, 'checkAvailability']);

    // Bookings API
    Route::get('/bookings', [BookingApiController::class, 'index']);
    Route::get('/bookings/{id}', [BookingApiController::class, 'show']);
    Route::post('/bookings', [BookingApiController::class, 'store']);

});
