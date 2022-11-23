<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: *');
// header('Access-Control-Allow-Headers: *');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * @api auth
 */

Route::group(["prefix" => "auth"], function () {

    Route::post("/registration", [App\Http\Controllers\AuthController::class, "registration"]);
    Route::post("/login", [AuthController::class, "login"]);

    /** @param "Barear asdsad2123..." */
    Route::group(['middleware' =>'auth:sanctum'], function () {
    
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

/**
 * @api games
 */

Route::group(["prefix" => "games"], function () {

    /** @var game | @method GET */
    Route::get("/", [GameController::class, 'games']);
    Route::get("/{game:slug}", [GameController::class, 'gameItem']);

    /** @param "Barear asdsad2123..." */
    Route::group(['middleware' =>'auth:sanctum'], function () {

        /** @var game | @method CREATE | UPDATE | DELETE */
        Route::post("/create", [GameController::class, "store"]);
        Route::post("/update/{game:id}", [GameController::class, "update"]);
        Route::delete("/delete/{game:id}", [GameController::class, "destroy"]);
    
        /** @var game_media | @method CREATE | UPDATE | DELETE */
        Route::post("/media/create/{game:id}", [MediaController::class, "addGameMedia"]);
        Route::delete("media/delete/{media:id}", [MediaController::class, "deleteGameMedia"]);
    });

});

/**
 * @api users
 */

Route::group(["prefix" => "user"], function () {

    /** 
     * @var user_media | @method CREATE | DELETE
     * @param "Barear asdsad2123..."
     */

    Route::get("/{user:id}", [UserController::class, "getUserById"]);
    
    Route::group(['middleware' =>'auth:sanctum'], function () {
        
        /** @var user | @method GET */
        Route::get("/", [UserController::class, "getUser"]);

        /** @var user | @method POST */
        Route::post("/avatar", [UserController::class, "changeUserAvatar"]);
        Route::post("/status", [UserController::class, "changeUserStatus"]);
        
        /** @var user_media | @method POST | DELETE */
        Route::post("/media/create/{user:id}", [MediaController::class, "addUserMedia"]);
        Route::delete("/media/delete/{media:id}", [MediaController::class, "deleteUserMedia"]);
    });
});

Route::group(["prefix" => "posts"], function() {

    /** 
     * @var user_media | @method CREATE | DELETE
     * @param "Barear asdsad2123..."
     */

    Route::group(['middleware' =>'auth:sanctum'], function() {
        
        /** @var post | @method POST | GET */
        Route::get("/user", [PostController::class, "getUserPost"]);
        Route::post("/create", [PostController::class, "store"]);
    });
});