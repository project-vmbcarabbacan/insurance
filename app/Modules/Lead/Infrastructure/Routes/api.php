<?php

use App\Modules\Lead\Infrastructure\Http\Controllers\HealthLeadController;
use App\Modules\Lead\Infrastructure\Http\Controllers\VehicleLeadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('lead')->group(function () {
    Route::prefix('vehicle')->group(function () {
        Route::post('store', [VehicleLeadController::class, 'store']);
    });
    Route::prefix('health')->group(function () {
        Route::post('store', [HealthLeadController::class, 'store']);
    });
});
