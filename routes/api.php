<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('articles', [ArticleController::class, 'search']);
    Route::get('categories', [ArticleController::class, 'categories']);
    Route::get('sources', [ArticleController::class, 'sources']);
    Route::post('logout', [AuthController::class, 'logout']);
});