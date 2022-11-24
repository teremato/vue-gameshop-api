<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserShortResource;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;


class FriendController extends Controller
{

    public function getUserFriends(Request $request, $id) {

        $friends = Friend::where("user_id", $id)
            ->get();

        return UserShortResource::collection($friends);
    }

    public function getUserFriendsAccept(Request $request) {

        $friends = $request->user()
            ->friends()
            ->where("accept", false)
            ->get();

        return UserShortResource::collection($friends);
    }

    public function addFriend(Request $request, User $friend) {
        
        $user = $request->user();

        $user->friends()
            ->create([
                "user_id" => $user->id,
                "friend_id" => $friend->id
            ]);
            
        return response(["message" => "Заявка отправлена!"], 201);
    }

    public function acceptFriend(Request $request, User $friend) {

        $user = $request->user();

        $user->friends()->where("friend_id", $friend->id)
            ->update([ "accept" => true ]);

        return response([
            "message" => "Пользователь добавлен в друзья!",
            "friend" => new UserShortResource($friend)
        ], 201);
    }

    public function removeFriend(Request $request, User $friend) {

        Friend::where("user_id", $request->user()->id)
            ->where("friend_id", $friend->id)
            ->delete();

        return response([
            "message" => "Удален из друзей!"
        ], 201);
    }
}
