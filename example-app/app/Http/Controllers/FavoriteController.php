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

            Favorite::where("post_favorite", $id)
                ->delete();

            return response([
                "message" => "Пост удален из избранного!"
            ]);
        }

        $like = Favorite::create([
                "post_favorite" => $id,
                "user_favorite" => $user->id,
            ]);
        $like->save();

        return response([
            "message" => "Пост добавлен в избранное!"
        ]);
    }
}
