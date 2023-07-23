<?php

use Illuminate\Http\Request;
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
Route::middleware('auth:api')->group(function () {

    // User auth routes
    Route::prefix('auth')->group(function() {
        Route::post('/logout', 'Users\AuthController@logout');
        Route::post('/refresh', 'Users\AuthController@refresh');
        Route::post('/me', 'Users\AuthController@me');
    });

    // User deposit routes
    Route::prefix('deposits')->group(function() {
        // create deposit
        Route::post('/', 'Users\DepositController@store');
        // get all deposits
        Route::get('/', 'Users\DepositController@index');
        // get single deposit
        Route::get('/{deposit}', 'Users\DepositController@show');
    });

    // User expense routes
    Route::prefix('expenses')->group(function() {
        // create expense
        Route::post('/', 'Users\ExpenseController@store');
        // get all expenses
        Route::get('/', 'Users\ExpenseController@index');
    });

    // get user balance
    Route::get('/balance', 'Users\AccountController@index');

    // get user transactions history (deposits and expenses)
    Route::get('/transactions', 'Users\TransactionController@index');
});

// User unauthenticated routes
Route::prefix('auth')->group(function() {
    Route::post('/login', 'Users\AuthController@login');
    Route::post('/register', 'Users\AuthController@register');
});

// Admin routes
Route::prefix('admin')->group(function() {

    // Admin authenticated routes
    Route::middleware('auth:admin')->group(function () {
        // Admin auth routes
        Route::prefix('auth')->group(function() {
            Route::post('/logout', 'Admin\AuthController@logout');
            Route::post('/refresh', 'Admin\AuthController@refresh');
            Route::post('/me', 'Admin\AuthController@me');
        });


        // Admin deposits routes
        Route::prefix('deposits')->group(function() {
            // get all deposits
            Route::get('/', 'Admin\DepositController@index');
            // get single deposit
            Route::get('/{deposit}', 'Admin\DepositController@show');
            // accept or reject deposit
            Route::patch('/{deposit}', 'Admin\DepositController@acceptOrReject');
        });


    });

    // Admin unauthenticated routes
    Route::prefix('auth')->group(function() {
        Route::post('/login', 'Admin\AuthController@login');
    });
});
