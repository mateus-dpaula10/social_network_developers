<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/currentUser', [AuthController::class, 'currentUser']);
Route::post('/comments', [CommentController::class, 'store']);
Route::get('/comments/{postId}', [CommentController::class, 'getComments']);