<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Auth;

// === RUTAS WEB (devuelven vistas HTML, no JSON) ===

// Ruta principal - Página de inicio
Route::get('/', function () {
    return view('inicio', ['currentUser' => auth()->user()]);
})->name('home');

// Ruta de prueba (para testear layouts/componentes)
Route::get('/test', function () {
    return view('test');
});

// === AUTENTICACIÓN WEB ===
// GET = mostrar formulario, POST = procesar formulario
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// === POSTS PÚBLICOS ===
// Cualquiera puede ver posts sin estar logueado
Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/post', [PostController::class, 'store']);
Route::post('/post/update/{id}', [PostController::class, 'update']);
Route::post('/post/delete/{id}', [PostController::class, 'destroy']);

// === RUTAS PROTEGIDAS (requieren login) ===
// middleware('auth') = Laravel verifica que el usuario esté autenticado
// Si no lo está, lo redirige a /login automáticamente
Route::middleware(['auth'])->group(function () {
    
    // === POSTS ===
    Route::get('/crear-post', [PostController::class, 'createForm'])->name('post.create');
    Route::post('/post/crear', [PostController::class, 'storeFromWeb'])->name('post.store');
    Route::post('/post/delete/{id}', [PostController::class, 'destroyFromWeb'])->name('post.delete');

    // === COMENTARIOS ===
    // Closure (función anónima) directa en la ruta
    // Útil para rutas simples sin lógica compleja
    Route::get('/comments/{id}', function($id) {
        $post = App\Models\Post::findOrFail($id);
        return view('comments', ['post' => $post, 'currentUser' => auth()->user()]);
    });
    Route::post('/comment/store/{id}', [CommentController::class, 'storeFromWeb'])->name('comment.store');
    Route::post('/comment/delete/{id}', [CommentController::class, 'destroyFromWeb'])->name('comment.delete');

    // === CUENTA DE USUARIO ===
    Route::get('/mi-cuenta', function () {
        // load() = Eager Loading de relaciones anidadas
        // posts.comments = posts del user Y sus comments
        $user = Auth::user()->load('posts.comments', 'comments');
        
        // Contar comentarios recibidos en TODOS mis posts
        // whereIn = WHERE post_id IN (1,2,3,...)
        // pluck('id') = extraer solo los IDs en un array [1,2,3]
        $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
        return view('cuenta', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
    })->name('account');
    
    Route::post('/mi-cuenta/update', [UserController::class, 'updateProfile'])->name('account.update');
    Route::post('/mi-cuenta/customize', [UserController::class, 'updateCustomization'])->name('account.customize');
    
    // Ver mis posts (usa la misma vista de cuenta)
    Route::get('/mis-posts', function () {
        $user = Auth::user()->load('posts.comments', 'comments');
        $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
        return view('cuenta', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
    })->name('my-posts');

    // === PERFIL PÚBLICO ===
    // Cualquier usuario logueado puede ver el perfil de otro
    Route::get('/usuario/{id}', function ($id) {
        // with() = Eager Loading complejo con relaciones anidadas
        // comments.post = los comments del usuario Y a qué post pertenecen
        $user = App\Models\User::with(['posts.comments', 'comments.post', 'roles'])->findOrFail($id);
        $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
        return view('perfil', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
    })->name('user.profile');

    // === PANEL ADMIN ===
    Route::get('/admin/usuarios', function () {
        // Verificar rol manualmente (también se podría usar middleware 'role:admin')
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }
        // withCount() = cuenta relaciones y las agrega como {relacion}_count
        // Ejemplo: $user->posts_count, $user->comments_count
        $users = App\Models\User::withCount(['posts', 'comments'])->with('roles')->orderBy('created_at', 'desc')->get();
        return view('usuarios', ['users' => $users]);
    })->name('admin.users');
});

