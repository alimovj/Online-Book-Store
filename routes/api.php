<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\BookController;
use App\Http\Controllers\API\V1\LikeController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\TranslationController;
use App\Http\Controllers\API\V1\Admin\LanguageController;
use App\Http\Controllers\API\V1\Admin\OrderController as AdminOrderController;


Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Welcome to API v1'
    ]);
});

Route::prefix('v1')->group(function () {

    // ==== AUTH ====
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/verify/{token}', [AuthController::class, 'verifyEmail']);
    Route::get('email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed'])
    ->name('verification.verify');
    Route::middleware('auth:sanctum')->group(function () {

        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);

        // ==== BOOKS ====
        Route::get('/books', [BookController::class, 'index']);
        Route::get('/books/{slug}', [BookController::class, 'show']);

        // Admin CRUD
        Route::middleware('role:admin')->group(function () {
            Route::post('/books', [BookController::class, 'store']);
            Route::put('/books/{book}', [BookController::class, 'update']);
            Route::delete('/books/{book}', [BookController::class, 'destroy']);
        });

        // ==== CATEGORIES ====
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{slug}', [CategoryController::class, 'show']);

        // Admin CRUD
        Route::middleware('role:admin')->group(function () {
            Route::post('/categories', [CategoryController::class, 'store']);
            Route::put('/categories/{category}', [CategoryController::class, 'update']);
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        });

        // ==== ORDERS (User) ====
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);

        // ==== ORDERS (Admin) ====
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/orders', [AdminOrderController::class, 'index']);
            Route::put('/admin/orders/{order}', [AdminOrderController::class, 'update']);
        });

        // ==== USERS (Admin only) ====
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('/users', UserController::class);
        });
        

        // ==== LANGUAGES ====
        Route::get('/langs', [LanguageController::class, 'index']); // everyone
        Route::middleware('role:admin')->group(function () {
            Route::post('/languages', [LanguageController::class, 'store']);
            Route::put('/languages/{language}', [LanguageController::class, 'update']);
            Route::delete('/languages/{language}', [LanguageController::class, 'destroy']);
        });

        // ==== TRANSLATIONS ====
        Route::get('/translations', [TranslationController::class, 'index']); // everyone
        Route::middleware('role:admin')->group(function () {
            Route::post('/translations', [TranslationController::class, 'store']);
            Route::put('/translations/{translation}', [TranslationController::class, 'update']);
            Route::delete('/translations/{translation}', [TranslationController::class, 'destroy']);
        });

        Route::post('/books/{book}/like', [LikeController::class, 'toggle'])->middleware('auth:sanctum');

        Route::get('/whishlists', [LikeController::class, 'index']);
        Route::post('/books/{book}/like', [LikeController::class, 'toggle']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'userOrders']);
    Route::get('/admin/orders', [OrderController::class, 'allOrders']);
});
Route::get('/admin/notifications', function () {
    return auth()->user()->notifications;
});


Route::prefix('currencies')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CurrencyController::class, 'index']);
    Route::post('/', [CurrencyController::class, 'store']);
    Route::get('/{code}', [CurrencyController::class, 'show']);
    Route::put('/{code}', [CurrencyController::class, 'update']);
    Route::delete('/{code}', [CurrencyController::class, 'destroy']);
    Route::post('/convert', [CurrencyController::class, 'convert']);
});
