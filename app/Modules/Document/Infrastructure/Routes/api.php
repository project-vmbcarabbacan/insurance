<?php

use App\Modules\Document\Infrastructure\Http\Controllers\DocumentController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('documents')->group(function () {
    Route::post('upload', [DocumentController::class, 'store'])
        ->middleware('throttle:60,1');
});
