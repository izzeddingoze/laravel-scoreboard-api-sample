<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */

Route::group(['namespace' => '\App\Http\Controllers\Api'], function () {

    Route::apiResource('/games', 'GameController');
    Route::apiResource('/players', 'PlayerController');
    Route::apiResource('/score-board', 'ScoreBoardController')->only(["store","show"]);

});
