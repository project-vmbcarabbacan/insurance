<?php

use App\Modules\Customer\Infrastructure\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('customers')->group(function () {

    Route::get('search', [CustomerController::class, 'index'])
        ->middleware('throttle:60,1');

    Route::get('search/{customer}', [CustomerController::class, 'find'])
        ->middleware('throttle:60,1');

    Route::post('store', [CustomerController::class, 'store'])
        ->middleware('throttle:60,1');

    Route::put('update/{customer}', [CustomerController::class, 'update'])
        ->middleware('throttle:60,1');

    Route::get('details/{customer}', [CustomerController::class, 'details'])
        ->middleware('throttle:60,1');
});
