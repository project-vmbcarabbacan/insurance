<?php

use App\Modules\Authentication\Infrastructure\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::prefix('authentication')->group(function () {

    Route::prefix('spa')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'spaLogin']);
        Route::post('/logout', [AuthenticationController::class, 'spaLogout']);
    });

    Route::prefix('mobile')->group(function () {
        Route::post('/login', [AuthenticationController::class, 'mobileLogin']);
        Route::post('/logout', [AuthenticationController::class, 'mobileLogout']);
    });
});
