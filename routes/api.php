<?php

use App\Http\Controllers\Api\User\UserDuelStartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserDataController;
use App\Http\Controllers\Api\User\UserNewCardController;
use App\Http\Controllers\Api\User\UserDuelActionController;
use App\Http\Controllers\Api\User\UserDuelActiveController;
use App\Http\Controllers\Api\User\UserDuelsHistoryController;
use App\Http\Controllers\Api\Authorization\LoginController;


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

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'duels'], function() {
        Route::post('', [UserDuelStartController::class, 'storeNewDuel']);
        Route::get('active', [UserDuelActiveController::class, 'getUserDuelActive']);
        Route::post('action', [UserDuelActionController::class, 'storeUserDuelAction']);
        Route::get('', [UserDuelsHistoryController::class, 'getDuelsHistory']);
    });
    Route::post('cards', [UserNewCardController::class, 'storeNewCard']);
    Route::get('user-data', [UserDataController::class, 'getUserData']);
});
