<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\ExpenseController;

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
    Route::prefix('auth')->group(function() {
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });

    // // User deposit routes
    // Route::prefix('deposits')->group(function() {
    //     // create deposit
    //     Route::post('/', 'User\DepositController@store');
    //     // get all deposits
    //     Route::get('/', 'User\DepositController@index');
    //     // get single deposit
    //     Route::get('/{deposit}', 'User\DepositController@show');
    // });

    // User expense routes
    Route::prefix('expenses')->group(function() {
        // create expense
        Route::post('/', [ExpenseController::class, 'store']);
        // get all expenses
        Route::get('/', [ExpenseController::class, 'index']);
    });

    // get user balance
    Route::get('/balance', [AccountController::class, 'balance']);

    // get user transactions history (deposits and expenses)
    Route::get('/transactions', [TransactionController::class, 'index']);
});

// User unauthenticated routes
Route::prefix('auth')->group(function() {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});

// Admin routes
Route::prefix('admin')->group(function() {

    // Admin authenticated routes
    Route::middleware(['auth:sanctum', 'abilities:guard-admin'])->group(function () {
        // Admin auth routes
        Route::prefix('auth')->group(function() {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
        });


    //     // Admin deposits routes
    //     Route::prefix('deposits')->group(function() {
    //         // get all deposits
    //         Route::get('/', 'Admin\DepositController@index');
    //         // get single deposit
    //         Route::get('/{deposit}', 'Admin\DepositController@show');
    //         // accept or reject deposit
    //         Route::patch('/{deposit}', 'Admin\DepositController@acceptOrReject');
    //     });


    });

    // Admin unauthenticated routes
    Route::prefix('auth')->group(function() {
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
});
