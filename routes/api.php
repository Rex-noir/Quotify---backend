<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/users', UserController::class);

//Broadcasting route
Broadcast::routes(['middleware' => 'auth:sanctum']);

//Posts
Route::apiResource('/posts', PostController::class);
Route::prefix('/posts')->group(function () {
    //Gets
    Route::get('/{post}/comments', [PostController::class, 'comments']);

    //Posts
    Route::post('/{post}/like', [LikeController::class, 'likePost']);
    Route::post('/{post}/dislike', [LikeController::class, 'dislikePost']);
});

//Comments
Route::apiResource('/comments', CommentController::class);
Route::prefix('/comments')->group(function () {
    //Gets
    Route::get('/replies/{comment}', [CommentController::class, 'replies']);

    //Posts
    Route::post('/{post}/like', [CommentLikeController::class, 'likeComment']);
    Route::post('/{post}/dislike', [CommentLikeController::class, 'dislikeComment']);
});


Route::prefix('/auth')->group(function () {
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::post('/login', [AuthController::class, 'login']);
})->middleware('web');
