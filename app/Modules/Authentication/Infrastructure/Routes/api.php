<?php

use App\Modules\Authentication\Infrastructure\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::prefix('authentication')->group(function () {

    Route::prefix('spa')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'spaLogin'])
            ->middleware('throttle:5,1');
        Route::post('/logout', [AuthenticationController::class, 'spaLogout'])
            ->middleware('throttle:5,1');
    });

    Route::prefix('mobile')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'mobileLogin'])
            ->middleware('throttle:5,1');
        Route::post('/logout', [AuthenticationController::class, 'mobileLogout'])
            ->middleware('throttle:5,1');
    });
});
