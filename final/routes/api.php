<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí registramos las rutas de la API de la aplicación.
| Estas rutas son cargadas por el RouteServiceProvider y todas serán
| asignadas al grupo de middleware "api".
|
*/

// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Verificación de email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

// Rutas públicas de posts (ver posts sin autenticación)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

// Rutas públicas de comentarios (ver comentarios sin autenticación)
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/posts/{postId}/comments', [CommentController::class, 'getPostComments']);

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth:sanctum', 'log.activity'])->group(function () {
    
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
    
    // Posts (usuarios autenticados)
    Route::post('/posts', [PostController::class, 'store']);              // Crear post
    Route::put('/posts/{id}', [PostController::class, 'update']);         // Editar post (propio)
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);     // Eliminar post (propio o admin)
    Route::get('/my-posts', [PostController::class, 'myPosts']);          // Ver mis posts
    
    // Comentarios (usuarios autenticados)
    Route::post('/comments', [CommentController::class, 'store']);        // Crear comentario
    Route::put('/comments/{id}', [CommentController::class, 'update']);   // Editar comentario (propio)
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // Eliminar comentario (propio o admin)
    Route::get('/my-comments', [CommentController::class, 'myComments']); // Ver mis comentarios
    
    // Rutas de administrador (requieren rol de admin)
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        // Gestión de usuarios
        Route::get('/users', [AdminController::class, 'getAllUsers']);
        Route::get('/users/{id}', [AdminController::class, 'getUser']);
        
        // Gestión de actividades
        Route::get('/activities', [AdminController::class, 'getAllActivities']);
        Route::get('/users/{id}/activities', [AdminController::class, 'getUserActivities']);
        
        // Estadísticas
        Route::get('/statistics', [AdminController::class, 'getStatistics']);
        
        // Gestión de roles y permisos
        Route::post('/users/{id}/roles', [AdminController::class, 'assignRole']);
        Route::delete('/users/{id}/roles', [AdminController::class, 'removeRole']);
        Route::post('/users/{id}/permissions', [AdminController::class, 'givePermission']);
        Route::delete('/users/{id}/permissions', [AdminController::class, 'revokePermission']);
        
        // Gestión de posts (admin puede eliminar cualquier post)
        // Ya cubierto por la ruta /posts/{id} con la verificación de rol admin
        
        // Gestión de comentarios (admin puede eliminar cualquier comentario)
        // Ya cubierto por la ruta /comments/{id} con la verificación de rol admin
    });
});

