<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\UserController;
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
Route::namespace('V1')->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('AuthTokenHeader')->get('refresh_token', [AuthController::class, 'refresh']);
        Route::middleware('auth:api')->group(function () {
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('authenticated', [AuthController::class, 'authenticated']);
        });
        Route::group(['prefix' => 'users'], function () {
            Route::post('verify', [UserController::class, 'verify']);
            Route::post('register', [UserController::class, 'register']);
            // Route::get('', [UserController::class, 'index']);
            // Route::get('/{id}', [UserController::class, 'findById']);
            Route::middleware('auth:api')->group(function () {
                Route::middleware('VerifiedAndEnabled')->group(function () {
                    Route::delete('/me', [UserController::class, 'delete']);
                    Route::put('/me', [UserController::class, 'update']);
                    Route::put('/avatar', [UserController::class, 'updateAvatar']);
                });
            });
        });
    });
});
