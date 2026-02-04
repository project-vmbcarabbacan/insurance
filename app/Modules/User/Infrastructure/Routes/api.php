<?php

use App\Modules\User\Infrastructure\Http\Controllers\TeamController;
use App\Modules\User\Infrastructure\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::get('/current/loggedin', [UserController::class, 'getCurrentuser'])
        ->middleware('throttle:60,1');

    Route::apiResource('teams', TeamController::class)
        ->only(['index', 'store', 'update'])
        ->middleware('throttle:60,1');

    Route::prefix('teams')->group(function () {
        Route::get('assign/product/{team}', [TeamController::class, 'getAssignedProduct'])
            ->middleware('throttle:60,1');

        Route::patch('upsert/product/{team}', [TeamController::class, 'upsertAssignProduct'])
            ->middleware('throttle:10,1');

        Route::patch('update/status', [TeamController::class, 'updateStatus'])
            ->middleware('throttle:10,1');

        Route::patch('password/{team}', [TeamController::class, 'updatePassword'])
            ->middleware('throttle:10,1');
    });
});
