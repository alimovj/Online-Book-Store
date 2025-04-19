<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\BookController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\Admin\LanguageController;
use App\Http\Controllers\API\V1\TranslationController;

// ADMIN ROUTES
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {

    // Books CRUD
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    // Categories CRUD
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::put('/orders/{order}', [AdminOrderController::class, 'update']);

    // Users
    Route::apiResource('/users', UserController::class);

    // Languages CRUD
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::post('/languages', [LanguageController::class, 'store']);
    Route::put('/languages/{language}', [LanguageController::class, 'update']);
    Route::delete('/languages/{language}', [LanguageController::class, 'destroy']);

    // Translations CRUD
    Route::get('/translations', [TranslationController::class, 'index']);
    Route::post('/translations', [TranslationController::class, 'store']);
    Route::put('/translations/{translation}', [TranslationController::class, 'update']);
    Route::delete('/translations/{translation}', [TranslationController::class, 'destroy']);
});
