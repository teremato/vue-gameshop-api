<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * @api games
 */

Route::group(["prefix" => "games"], function () {

    Route::get("/", [GameController::class, 'games']);
    Route::get("/{game:slug}", [GameController::class, 'gameItem']);

    Route::post("/create", [GameController::class, "store"]);
    Route::put("/update/{game:id}", [GameController::class, "update"]);
    Route::delete("/delete/{game:id}", [GameController::class, "destroy"]);
});

/**
 * @api users
 */

Route::group(["prefix" => "user"], function () {

});