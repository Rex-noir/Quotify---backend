<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/posts', PostController::class);

Route::prefix('/auth')->group(function () {
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::post('/login', [AuthController::class, 'login']);
})->middleware('web');
