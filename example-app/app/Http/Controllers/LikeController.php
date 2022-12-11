<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likeToPost(Request $request, $id) {

        $user = $request->user();

        if($user->hasLikePost($id)) {

            Like::where("entity_id", $id)
                ->where("entity_type", Like::TYPE_POST)
                ->delete();

            return response([ "message" => "Лайк удален!" ]);
        }

        $like = Like::create([
                "entity_id" => $id,
                "user_id" => $user->id,
                "entity_type" => Favorite::TYPE_POST
            ]);
        $like->save();

        return response([ "message" => "Лайк поставлен!" ]);
    }

    public function LikeToGame(Request $request, $id) {

        $user = $request->user();

        if($user->hasLikeGame($id)) {

            Like::where("entity_id", $id)
            ->where("entity_type", Like::TYPE_GAME)
            ->delete();

            return response([ "message" => "Лайк удален!" ]);
        }

        $like = Like::create([
            "entity_id" => $id,
            "user_id" => $user->id,
            "entity_type" => Favorite::TYPE_GAME
        ]);
        $like->save();

        return response([ "message" => "Лайк поставлен!" ]);
    }
}
