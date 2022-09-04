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
    'as' => 'auth.',
    'prefix' => 'auth'
],function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'store'])->name('store');
});


Route::group([
    'middleware' => 'auth:sanctum',
    'as' => 'protected.',
    'prefix' => 'protected',
], function() {
    
    Route::group([
        'as' => 'user.',
        'prefix' => 'user'
    ], function() {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'delete']);
        Route::post('/logout', [UserController::class, 'logout'])->name('login');
    });

    Route::group([
        'as' => 'account.',
        'prefix' => 'account'
    ], function() {
        Route::get('/', [AccountController::class, 'getBalance']);
        Route::post('/', [AccountController::class, 'doTransaction']);
    });
});
