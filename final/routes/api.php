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

// === RUTAS PÚBLICAS (sin autenticación) ===

// Autenticación
Route::post('/register', [AuthController::class, 'register']);  // registro de usuario
Route::post('/login', [AuthController::class, 'login']);         // login (devuelve token)

// Verificación de email
// {id} = user ID, {hash} = hash de verificación (seguridad)
// name() = darle nombre a la ruta para usarla en otros lados (ej: url() o route())
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

// Posts públicos (cualquiera puede ver)
Route::get('/posts', [PostController::class, 'index']);      // listar todos
Route::get('/posts/{id}', [PostController::class, 'show']); // ver uno específico

// Categorías públicas
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Comentarios públicos
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/posts/{postId}/comments', [CommentController::class, 'getPostComments']);

// === RUTAS PROTEGIDAS (requieren token de autenticación) ===
// middleware() = aplicar middlewares
// 'auth:sanctum' = verificar que venga un token válido en el header
// 'log.activity' = middleware custom para registrar actividad del usuario
Route::middleware(['auth:sanctum', 'log.activity'])->group(function () {
    
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);  // cerrar sesión (invalida token)
    Route::get('/me', [AuthController::class, 'me']);           // obtener usuario actual
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
    
    // CRUD Posts (usuarios autenticados)
    Route::post('/posts', [PostController::class, 'store']);              // crear mi post
    Route::put('/posts/{id}', [PostController::class, 'update']);         // editar mi post  
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);     // eliminar (mío o si soy admin)
    Route::get('/my-posts', [PostController::class, 'myPosts']);          // mis posts
    
    // CRUD Comentarios
    Route::post('/comments', [CommentController::class, 'store']);        
    Route::put('/comments/{id}', [CommentController::class, 'update']);   
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); 
    Route::get('/my-comments', [CommentController::class, 'myComments']);
    
    // === RUTAS DE ADMINISTRADOR ===
    // middleware 'role:admin' = solo usuarios con rol 'admin' (Spatie Permission)
    // prefix('admin') = todas las rutas llevan /admin/ (ej: /api/admin/users)
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        // CRUD Categorías (solo admin)
        Route::post('/categories', [CategoryController::class, 'store']);             
        Route::put('/categories/{category}', [CategoryController::class, 'update']);  
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']); 
        
        // Gestión de usuarios
        Route::get('/users', [AdminController::class, 'getAllUsers']);        // listar todos
        Route::get('/users/{id}', [AdminController::class, 'getUser']);       // ver uno
        
        // Log de actividades (para auditoría/seguimiento)
        Route::get('/activities', [AdminController::class, 'getAllActivities']);
        Route::get('/users/{id}/activities', [AdminController::class, 'getUserActivities']);
        
        // Dashboard: estadísticas del sistema
        Route::get('/statistics', [AdminController::class, 'getStatistics']);
        
        // Spatie: asignar/quitar roles y permisos
        Route::post('/users/{id}/roles', [AdminController::class, 'assignRole']);
        Route::delete('/users/{id}/roles', [AdminController::class, 'removeRole']);
        Route::post('/users/{id}/permissions', [AdminController::class, 'givePermission']);
        Route::delete('/users/{id}/permissions', [AdminController::class, 'revokePermission']);
        
        // Nota: Posts y comments ya se pueden eliminar desde las rutas de arriba
        // porque el controller verifica si el user es admin
    });
});

