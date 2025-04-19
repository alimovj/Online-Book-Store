<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\API\V1\BookController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\LikeController;
use App\Http\Controllers\API\V1\TranslationController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\API\V1\Admin\LanguageController;

// Welcome
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Welcome to API v1'
    ]);
});

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify/{token}', [AuthController::class, 'verifyEmail']);

Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Books (Read)
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{slug}', [BookController::class, 'show']);

    // Categories (Read)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);

    // Translations
    Route::get('/translations/{locale}', [TranslationController::class, 'getByLocale']);
    Route::get('/languages/active', [LanguageController::class, 'activeLanguages']);

    // Likes
    Route::get('/whishlists', [LikeController::class, 'index']);
    Route::post('/books/{book}/like', [LikeController::class, 'toggle']);

    // Currency
    Route::prefix('currencies')->group(function () {
        Route::get('/', [CurrencyController::class, 'index']);
        Route::post('/', [CurrencyController::class, 'store']);
        Route::get('/{code}', [CurrencyController::class, 'show']);
        Route::put('/{code}', [CurrencyController::class, 'update']);
        Route::delete('/{code}', [CurrencyController::class, 'destroy']);
        Route::post('/convert', [CurrencyController::class, 'convert']);
        Route::post('/currencies/convert', [CurrencyController::class, 'convert']);
    });

    // Notifications
    Route::get('/admin/notifications', fn () => auth()->user()->notifications);
});
