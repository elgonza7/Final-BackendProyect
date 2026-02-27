<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - Rutas para la interfaz web  
|--------------------------------------------------------------------------
| NOTA: Estas rutas son para la interfaz web tradicional (no API)
| Ejemplo: Route::get('/post/{id}') -> http://localhost/post/1
| Middleware 'web': incluye sesiones, CSRF protection, cookies, etc.
| Middleware 'auth': protege rutas para usuarios autenticados
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('inicio', ['currentUser' => auth()->user()]);
})->name('home');

Route::get('/test', function () {
    return view('test');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/post', [PostController::class, 'store']);
Route::post('/post/update/{id}', [PostController::class, 'update']);
Route::post('/post/delete/{id}', [PostController::class, 'destroy']);

Route::middleware(['auth'])->group(function () {
    
    Route::get('/crear-post', [PostController::class, 'createForm'])->name('post.create');
    Route::post('/post/crear', [PostController::class, 'storeFromWeb'])->name('post.store');
    Route::post('/post/delete/{id}', [PostController::class, 'destroyFromWeb'])->name('post.delete');

    Route::get('/comments/{id}', function($id) {
        $post = App\Models\Post::findOrFail($id);
        return view('comments', ['post' => $post, 'currentUser' => auth()->user()]);
    });
    Route::post('/comment/store/{id}', [CommentController::class, 'storeFromWeb'])->name('comment.store');
    Route::post('/comment/delete/{id}', [CommentController::class, 'destroyFromWeb'])->name('comment.delete');

    Route::get('/mi-cuenta', function () {
        $user = Auth::user()->load('posts.comments', 'comments');
        $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
        return view('cuenta', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
    })->name('account');
    
    Route::post('/mi-cuenta/update', [UserController::class, 'updateProfile'])->name('account.update');
    Route::post('/mi-cuenta/customize', [UserController::class, 'updateCustomization'])->name('account.customize');
    
    Route::get('/mis-posts', function () {
        $user = Auth::user()->load('posts.comments', 'comments');
        $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
        return view('cuenta', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
    })->name('my-posts');

    Route::get('/usuario/{id}', function ($id) {
        $user = App\Models\User::with(['posts.comments', 'comments.post', 'roles'])->findOrFail($id);
        $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
        return view('perfil', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
    })->name('user.profile');

    Route::get('/admin/usuarios', function () {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }
        $users = App\Models\User::withCount(['posts', 'comments'])->with('roles')->orderBy('created_at', 'desc')->get();
        return view('usuarios', ['users' => $users]);
    })->name('admin.users');
});

