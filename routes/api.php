<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CheckController as AdminCheckController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\CheckController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\TransactionController;
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

// User authenticated routes
Route::middleware(['auth:sanctum', 'abilities:guard-user'])->group(function () {

    // User auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout']);
        Route::get('/user', [UserAuthController::class, 'user']);
    });

    // User check routes
    Route::prefix('checks')->group(function () {
        // create check
        Route::post('/', [CheckController::class, 'store']);
        // get all checks
        Route::get('/', [CheckController::class, 'index']);
        // get dates to filter
        Route::get('/dates', [CheckController::class, 'datesToFilter']);
    });

    // User expense routes
    Route::prefix('expenses')->group(function () {
        // create expense
        Route::post('/', [ExpenseController::class, 'store']);
        // get all expenses
        Route::get('/', [ExpenseController::class, 'index']);
        // get dates to filter
        Route::get('/dates', [ExpenseController::class, 'datesToFilter']);
    });

    // get user balance
    Route::get('/balance', [AccountController::class, 'balance']);

    // get user transactions history (checks and expenses)
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        // get dates to filter
        Route::get('/dates', [TransactionController::class, 'datesToFilter']);
    });

});

// User unauthenticated routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});

// Admin routes
Route::prefix('admin')->group(function () {

    // Admin authenticated routes
    Route::middleware(['auth:sanctum', 'abilities:guard-admin'])->group(function () {
        // Admin auth routes
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
            Route::get('/user', [AdminAuthController::class, 'user']);
        });

        // Admin checks routes
        Route::prefix('checks')->group(function () {
            // get all pending checks
            Route::get('/', [AdminCheckController::class, 'index']);
            // get pending check by id
            Route::get('/{checkId}', [AdminCheckController::class, 'show']);
            // accept or reject check
            Route::patch('/{checkId}', [AdminCheckController::class, 'acceptOrReject']);
        });

    });

    // Admin unauthenticated routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
});
