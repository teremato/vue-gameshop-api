<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likeToPost(Request $request, $id) {

        $user = $request->user();

        if($user->hasLikePost($id)) {

            Like::where("post_like", $id)
                ->delete();

            return response([
                "message" => "Лайк удален!"
            ]);
        }

        $like = Like::create([
                "post_like" => $id,
                "user_likes" => $user->id,
                "type" => "post"
            ]);
        $like->save();

        return response([
            "message" => "Лайк поставлен!"
        ]);
    }
}
