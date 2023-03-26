<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth APIs
    Route::post('/logout', [AuthController::class, 'logout']);

    // User APIs
    Route::get('/user', [AuthController::class, 'getLoggedInUserData']);
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/users/{userId}/blogs', [BlogController::class, 'getUserBlogs']);

    // Blog APIs
    Route::prefix('blogs')->group(function () {
        Route::post('/', [BlogController::class, 'createBlog']);
        Route::get('/', [BlogController::class, 'getBlogs']);
        Route::get('/{blogId}', [BlogController::class, 'getBlog']);
        Route::patch('/{blogId}', [BlogController::class, 'updateBlog']);
        Route::delete('/{blogId}', [BlogController::class, 'deleteBlog']);

        Route::post('/{blogId}/comments', [CommentController::class, 'createComment']);
        Route::delete('/{blogId}/comments/{commentId}', [CommentController::class, 'deleteComment']);

    });


});



