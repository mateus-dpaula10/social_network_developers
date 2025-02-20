<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{post}', [PostController::class, 'update']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/currentUser', [AuthController::class, 'currentUser']);
Route::post('/comments', [CommentController::class, 'store']);
Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);
Route::get('/comments/{postId}', [CommentController::class, 'getComments']);
Route::post('/posts/{post}/like', [LikeController::class, 'like']);
Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlike']);
Route::get('/posts/{post}/likes', [LikeController::class, 'getLikesCount']);