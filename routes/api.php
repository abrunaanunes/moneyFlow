<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
],function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'store']);
});


Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'protected',
], function() {
    Route::post('logout', [UserController::class, 'logout']);
    
    Route::group([
        'prefix' => 'user'
    ], function() {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'delete']);
    });

    Route::group([
        'prefix' => 'account'
    ], function() {
        Route::get('/', [AccountController::class, 'getBalance']);
        Route::post('/', [AccountController::class, 'sendMoney']);
    });

    Route::group([
        'prefix' => 'transaction'
    ], function() {
        Route::post('/{transaction}', [TransactionController::class, 'rollback']);
    });
});
