<?php

use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectsController::class, 'index']);
    Route::get('/{id}', [ProjectsController::class, 'show']);
    Route::post('/create', [ProjectsController::class, 'store']);
    Route::match(['put', 'patch'], '/{id}', [ProjectsController::class, 'update']);
    Route::delete('/{id}', [ProjectsController::class, 'destroy']);
})->middleware('auth:sanctum');

// Route::apiResource('tasks', TasksController::class);
Route::prefix('tasks')->group(function () {
    Route::get('/', [TasksController::class, 'index']);
    Route::get('/{id}', [TasksController::class, 'show']);
    Route::post('/create', [TasksController::class, 'store']);
    Route::match(['put', 'patch'], '/{id}', [TasksController::class, 'update']);
    Route::delete('/{id}', [TasksController::class, 'destroy']);
})->middleware('auth:sanctum');
