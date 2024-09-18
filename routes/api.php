<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\RecoveryPasswordCodeController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Rota publica
 */
Route::prefix('login')->group(function() {
    Route::post('/', [AuthController::class, 'login']);
    Route::post('/create', [AuthController::class, 'create']);

    Route::post('/forgot-password-code', [RecoveryPasswordCodeController::class, 'forgotPasswordCode']);
    Route::post('/reset-password-validate-code', [RecoveryPasswordCodeController::class, 'resetPasswordValidateCode']);
    Route::post('/reset-password-code', [RecoveryPasswordCodeController::class, 'resetPasswordCode']);
});

/**
 * Rotas privadas
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::post('logout/{id}', [AuthController::class, 'logout']);

    Route::get('/users', [UserController::class, 'index']);

    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectsController::class, 'index']);
        Route::get('/{id}', [ProjectsController::class, 'show']);
        Route::post('/create', [ProjectsController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [ProjectsController::class, 'update']);
        Route::delete('/{id}', [ProjectsController::class, 'destroy']);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TasksController::class, 'index']);
        Route::get('/{id}', [TasksController::class, 'show']);
        Route::post('/create', [TasksController::class, 'store']);
        Route::match(['put', 'patch'], '/{id}', [TasksController::class, 'update']);
        Route::delete('/{id}', [TasksController::class, 'destroy']);

        Route::post('/report', [TasksController::class, 'reports']);
    });

    Route::prefix('tasks_relationship')->group(function () {
        Route::post('/', [TasksController::class, 'relationshipStore']);
        Route::delete('/', [TasksController::class, 'relationshipDelete']);
    });
});
