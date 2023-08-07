<?php

use App\Http\Controllers\API\GemStoneAPIController;
use App\Http\Controllers\API\ProductsAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\UserLoginAPIController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
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

Route::group(['middleware' => ['APIToken']], function () {
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductsAPIController::class, 'index']);
        Route::post('/create', [ProductsAPIController::class, 'store']);
        Route::get('/show/{id}', [ProductsAPIController::class, 'show']);
        Route::post('/update/{id}', [ProductsAPIController::class, 'update']);
        Route::delete('/delete/{id}', [ProductsAPIController::class, 'destroy']);
    });
});


// basic 
Route::prefix('user')->group(function () {
    Route::get('/', [UserAPIController::class, 'index']);
    Route::get('/show/{id}', [UserAPIController::class, 'show']);
    Route::post('/update/{id}', [UserAPIController::class, 'update']);
    Route::delete('/delete/{id}', [UserAPIController::class, 'destroy']);
});

// Auth 
Route::prefix('user')->group(function () {
    Route::post('/register', [UserLoginAPIController::class, 'register']);
    Route::post('/login', [UserLoginAPIController::class, 'login']);
    Route::get('/logout/{id}', [UserLoginAPIController::class, 'logout']);

    Route::post('/changePassword', [UserLoginAPIController::class, 'changePassword']);
    // Route::post('/userForgotPassword', [UserLoginAPIController::class, 'userForgotPassword']);
});
