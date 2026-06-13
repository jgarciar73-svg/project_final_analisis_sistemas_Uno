<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;
use Illuminate\Support\Facades\Route;

// rutas publicas - solo necesitan el tenant
Route::middleware('tenant')->group(function (): void {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// rutas que necesitan autenticacion
Route::middleware(['tenant', 'jwt.auth'])->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // rutas de roles - solo para admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::post('/roles/asignar', [RoleController::class, 'asignarRol']);
    });

    // cualquier usuario autenticado puede ver sus roles
    Route::get('/usuarios/{id}/roles', [RoleController::class, 'rolesDeUsuario']);
});

// ruta para refrescar token
Route::middleware(['tenant', 'jwt.refresh'])->group(function (): void {
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
});
