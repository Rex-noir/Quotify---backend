<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/users', UserController::class);


//Posts
Route::apiResource('/posts', PostController::class);
Route::get('/posts/{post}/comments', [PostController::class, 'comments']);

//Comments
Route::apiResource('/comments', CommentController::class);
Route::get('/comments/replies/{comment}', [CommentController::class, 'replies']);

Route::prefix('/auth')->group(function () {
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::post('/login', [AuthController::class, 'login']);
})->middleware('web');
