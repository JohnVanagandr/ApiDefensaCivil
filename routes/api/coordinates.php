<?php

use App\Http\Controllers\API\Coordinates\CoordinatesController;
use Illuminate\Support\Facades\Route;

Route::prefix('coordinates')->group(function () {
    Route::get('/', [CoordinatesController::class, 'index'])
        ->middleware('permission:coordinates.show');

    Route::get('/familyPlan/{familyPlanId}', [CoordinatesController::class, 'getByFamilyPlan'])
    ->middleware('permission:coordinates.by-family-plan');

    Route::get('/{id}', [CoordinatesController::class, 'show'])
        ->middleware('permission:coordinates.show');

    Route::post('/', [CoordinatesController::class, 'store'])
        ->middleware('permission:coordinates.store');

    Route::put('/{id}', [CoordinatesController::class, 'update'])
        ->middleware('permission:coordinates.update');

    Route::delete('/{id}', [CoordinatesController::class, 'destroy'])
        ->middleware('permission:coordinates.destroy');
});