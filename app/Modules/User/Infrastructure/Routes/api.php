<?php

use App\Modules\User\Infrastructure\Http\Controllers\TeamController;
use App\Modules\User\Infrastructure\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:30,1'])->prefix('user')->group(function () {
    Route::get('/current-loggedin', [UserController::class, 'getCurrentuser'])
        ->middleware('throttle:60,1');

    Route::apiResource('teams', TeamController::class)
        ->only(['index', 'store', 'update'])
        ->middleware('throttle:60,1');

    Route::patch('teams/update/status', [TeamController::class, 'updateStatus'])
        ->middleware('throttle:60,1');

    Route::patch('teams/password/{team}', [TeamController::class, 'updatePassword'])
        ->middleware('throttle:60,1');
});
