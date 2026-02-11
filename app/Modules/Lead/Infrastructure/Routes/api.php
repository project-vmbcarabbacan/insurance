<?php

use App\Modules\Lead\Infrastructure\Http\Controllers\HealthLeadController;
use App\Modules\Lead\Infrastructure\Http\Controllers\LeadController;
use App\Modules\Lead\Infrastructure\Http\Controllers\VehicleLeadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('lead')->group(function () {
    Route::prefix('vehicle')->group(function () {
        Route::post('store', [VehicleLeadController::class, 'store'])
            ->middleware('throttle:60,1');

        Route::get('view/{lead}', [VehicleLeadController::class, 'view'])
            ->middleware('throttle:60,1');

        Route::get('find/{lead}', [VehicleLeadController::class, 'find'])
            ->middleware('throttle:60,1');
    });
    Route::prefix('health')->group(function () {
        Route::post('store', [HealthLeadController::class, 'store'])
            ->middleware('throttle:60,1');

        Route::get('view/{lead}', [HealthLeadController::class, 'view'])
            ->middleware('throttle:60,1');

        Route::get('find/{lead}', [HealthLeadController::class, 'find'])
            ->middleware('throttle:60,1');
    });

    Route::prefix('activity')->group(function () {
        Route::post('add', [LeadController::class, 'addLeadActivity'])
            ->middleware('throttle:60,1');

        Route::get('{lead}', [LeadController::class, 'getLeadActivity'])
            ->middleware('throttle:60,1');
    });

    Route::get('leads/{customer}', [LeadController::class, 'leads'])
        ->middleware('throttle:60,1');
});
