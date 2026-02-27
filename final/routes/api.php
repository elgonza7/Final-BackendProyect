<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes - Rutas para la API REST
|--------------------------------------------------------------------------
| NOTA: Todas estas rutas tienen prefijo /api automáticamente
| Ejemplo: Route::get('/posts') -> http://localhost/api/posts
| 
| Middleware 'api': incluye throttling (rate limiting) y accept JSON
| auth:sanctum: usa Laravel Sanctum para autenticación con tokens
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/comments', [CommentController::class, 'index']);
Route::get('/posts/{postId}/comments', [CommentController::class, 'getPostComments']);

Route::middleware(['auth:sanctum', 'log.activity'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
    
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);
    
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::get('/my-comments', [CommentController::class, 'myComments']);
    
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        
        Route::get('/users', [AdminController::class, 'getAllUsers']);
        Route::get('/users/{id}', [AdminController::class, 'getUser']);
        
        Route::get('/activities', [AdminController::class, 'getAllActivities']);
        Route::get('/users/{id}/activities', [AdminController::class, 'getUserActivities']);
        
        Route::get('/statistics', [AdminController::class, 'getStatistics']);
        
        Route::post('/users/{id}/roles', [AdminController::class, 'assignRole']);
        Route::delete('/users/{id}/roles', [AdminController::class, 'removeRole']);
        Route::post('/users/{id}/permissions', [AdminController::class, 'givePermission']);
        Route::delete('/users/{id}/permissions', [AdminController::class, 'revokePermission']);
    });
});

