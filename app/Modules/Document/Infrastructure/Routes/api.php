<?php

use App\Modules\Document\Infrastructure\Http\Controllers\DocumentController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('documents')->group(function () {
    Route::post('upload', [DocumentController::class, 'store'])
        ->middleware('throttle:60,1');

    Route::get('all/{lead}', [DocumentController::class, 'allDocuments'])
        ->middleware('throttle:60,1');

    Route::patch('update/type/{document}', [DocumentController::class, 'updateType'])
        ->middleware('throttle:60,1');

    Route::delete('delete/{document}', [DocumentController::class, 'deleteDocument'])
        ->middleware('throttle:60,1');
});
