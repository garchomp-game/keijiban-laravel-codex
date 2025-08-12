<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::get('/threads', [ThreadController::class, 'index']);
Route::post('/threads', [ThreadController::class, 'store'])->middleware('auth:sanctum');
Route::get('/threads/{thread}', [ThreadController::class, 'show']);
Route::patch('/threads/{thread}', [ThreadController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/threads/{thread}', [ThreadController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/threads/{thread}/posts', [PostController::class, 'index']);
Route::post('/threads/{thread}/posts', [PostController::class, 'store'])->middleware('auth:sanctum');
