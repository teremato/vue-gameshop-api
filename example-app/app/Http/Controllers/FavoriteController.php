<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Like;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function favoritePost(Request $request, $id) {
        
        $user = $request->user();

        if($user->hasFavoritePost($id)) {

            Favorite::where("entity_id", $id)
                ->where("entity_type", Favorite::TYPE_POST)
                ->delete();

            return response([ "message" => "Пост удален из избранного!" ]);
        }

        $like = Favorite::create([
                "user_id" => $user->id,
                "entity_id" => $id,
                "entity_type" => Favorite::TYPE_POST
            ]);
        $like->save();

        return response([ "message" => "Пост добавлен в избранное!" ]);
    }

    public function favoriteGame(Request $request, $id) {

        $user = $request->user();

        if($user->hasFavoriteGame($id)) {

            Favorite::where("entity_id", $id)
                ->where("entity_type", Favorite::TYPE_GAME)
                ->delete();

            return response([ "message" => "Игра удалена из избранного!" ]);
        }

        $like = Favorite::create([
                "user_id" => $user->id,
                "entity_id" => $id,
                "entity_type" => Favorite::TYPE_GAME
            ]);
        $like->save();

        return response([ "message" => "Игра добавлена в избранное!" ]);
    }
}
