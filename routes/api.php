<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GameScenarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::get('/scenarios', [GameScenarioController::class, 'index']);
        Route::get('/scenarios/{gameScenario}', [GameScenarioController::class, 'show']);
        Route::post('/scenarios/{gameScenario}/update', [GameScenarioController::class, 'update']);
        Route::post('/scenarios/{gameScenario}/reset', [GameScenarioController::class, 'reset']);
    });
});
