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

    //START THE DUEL
    Route::post('duels', [UserDuelStartController::class, 'storeNewDuel']);

    //CURRENT GAME DATA
    // Route::get('duels/active', function (Request $request) {
    //     return [
    //         'round' => 4,
    //         'your_points' => 260,
    //         'opponent_points' => 100,
    //         'status' => 'active',
    //         'cards' => config('game.cards'),
    //     ];
    // });

    Route::get('duels/active', [UserDuelActiveController::class, 'getUserDuelActive']);
    

    //User has just selected a card
    // Route::post('duels/action', function (Request $request) {
    //     return response()->json();
    // });

    Route::post('duels/action', [UserDuelActionController::class, 'storeUserDuelAction']);

    //DUELS HISTORY
    // Route::get('duels', function (Request $request) {
    //     return [
    //         [
    //             "id" => 1,
    //             "player_name" => "Jan Kowalski",
    //             "opponent_name" => "Piotr Nowak",
    //             "won" => 0
    //         ],
    //         [
    //             "id" => 2,
    //             "player_name" => "Jan Kowalski",
    //             "opponent_name" => "Tomasz Kaczyński",
    //             "won" => 1
    //         ],
    //         [
    //             "id" => 3,
    //             "player_name" => "Jan Kowalski",
    //             "opponent_name" => "Agnieszka Tomczak",
    //             "won" => 1
    //         ],
    //         [
    //             "id" => 4,
    //             "player_name" => "Jan Kowalski",
    //             "opponent_name" => "Michał Bladowski",
    //             "won" => 1
    //         ],
    //     ];
    // });

    //DUELS HISTORY
    Route::get('duels', [UserDuelsHistoryController::class, 'getDuelsHistory']);
    
    // CARDS
    Route::post('cards', [UserNewCardController::class, 'storeNewCard']);

    //USER DATA
    Route::get('user-data', [UserDataController::class, 'getUserData']);
});
