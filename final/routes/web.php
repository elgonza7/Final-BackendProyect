<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio');
});

Route::get('/post', [PostController::class, 'index']);

Route::get('/post/{id}', [PostController::class, 'show']);

Route::post('/post', [PostController::class, 'store']);