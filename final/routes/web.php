<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    $currentUser = auth()->user() ?? \App\Models\User::find(session('user_id', 1));
    return view('inicio', ['currentUser' => $currentUser]);
});

Route::get('/test', function () {
    return view('test');
});

// Posts
Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/post', [PostController::class, 'store']);
Route::post('/post/update/{id}', [PostController::class, 'update']);
Route::post('/post/delete/{id}', [PostController::class, 'destroy']);

// Posts Web Form
Route::get('/crear-post', [PostController::class, 'createForm']);
Route::post('/post/crear', [PostController::class, 'storeFromWeb']);
Route::post('/post/delete/{id}', [PostController::class, 'destroyFromWeb']);

// Comments
Route::get('/comments/{id}', function($id) {
    $post = App\Models\Post::findOrFail($id);
    $currentUser = auth()->user() ?? \App\Models\User::find(session('user_id', 1));
    return view('comments', ['post' => $post, 'currentUser' => $currentUser]);
});
Route::post('/comment/store/{id}', [CommentController::class, 'storeFromWeb']);
Route::post('/comment/store', [CommentController::class, 'store']);
Route::get('/comment', [CommentController::class, 'index']);
Route::get('/comment/create', [CommentController::class, 'create']);

// Comments Web Form
Route::post('/comment/delete/{id}', [CommentController::class, 'destroyFromWeb']);

// User Account
Route::get('/mi-cuenta', function () {
    $userId = session('user_id', 1);
    $user = App\Models\User::with('posts', 'comments')->findOrFail($userId);
    $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
    return view('cuenta', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
});

Route::get('/mis-posts', function () {
    $userId = session('user_id', 1);
    $user = App\Models\User::with('posts', 'comments')->findOrFail($userId);
    $totalComentsReceived = App\Models\Comment::whereIn('post_id', $user->posts->pluck('id'))->count();
    return view('cuenta', ['user' => $user, 'totalComentsReceived' => $totalComentsReceived]);
});

Route::get('/logout', function () {
    session()->forget('user_id');
    return redirect('/');
});

