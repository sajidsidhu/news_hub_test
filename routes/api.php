<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::post('articles', [ArticleController::class, 'search']);
Route::get('categories', [ArticleController::class, 'categories']);
Route::get('sources', [ArticleController::class, 'sources']);