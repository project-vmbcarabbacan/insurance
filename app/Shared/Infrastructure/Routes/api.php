<?php

namespace App\Shared\Infrastructure\Routes;

use App\Shared\Infrastructure\Http\Controllers\SettingController;
use App\Shared\Infrastructure\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('setting')->group(function () {
    Route::prefix('manage')->group(function () {
        Route::get('teams', [SettingController::class, 'manageTeams'])
            ->middleware('throttle:60,1');

        Route::get('customers', [SettingController::class, 'manageCustomers'])
            ->middleware('throttle:60,1');
    });

    Route::prefix('insurance')->group(function () {
        Route::get('product', [SettingController::class, 'assignProduct'])
            ->middleware('throttle:60,1');
    });

    Route::prefix('customer')->group(function () {
        Route::get('upsert', [SettingController::class, 'upsertCustomer'])
            ->middleware('throttle:60,1');
    });



    Route::prefix('vehicle')->group(function () {
        Route::get('prerequisites', [VehicleController::class, 'leadVehicle'])
            ->middleware('throttle:60,1');

        Route::get('make/{year}', [VehicleController::class, 'getMakes'])
            ->middleware('throttle:60,1');

        Route::get('model/{year}/{make_id}', [VehicleController::class, 'getModels'])
            ->middleware('throttle:60,1');

        Route::get('trim/{year}/{make_id}/{model_id}', [VehicleController::class, 'getTrims'])
            ->middleware('throttle:60,1');
    });
});
