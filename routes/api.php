<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::get('articles', [ArticleController::class, 'search']);
Route::get('categories', [ArticleController::class, 'categories']);