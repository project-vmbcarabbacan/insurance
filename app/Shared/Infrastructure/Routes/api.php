<?php

namespace App\Shared\Infrastructure\Routes;

use App\Shared\Infrastructure\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('setting')->group(function () {
    Route::get('manage-teams', [SettingController::class, 'manageTeams'])
        ->middleware('throttle:60,1');
    Route::get('insurance-product', [SettingController::class, 'assignProduct'])
        ->middleware('throttle:60,1');
});
