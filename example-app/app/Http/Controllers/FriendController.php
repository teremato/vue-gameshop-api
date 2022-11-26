<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\UserShortResource;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;


class FriendController extends Controller
{

    public function getUserFriends(Request $request, $id) {

        $friends = Friend::where("friend_id", $id)
            ->where("accept", Friend::FRIEND_ACCEPT)
            ->get();

        return FriendResource::collection($friends);
    }

    public function getUserAcceptFriends(Request $request) {

        $user = $request->user();
        $friends = Friend::where("friend_id", $user->id)
            ->where("accept", Friend::FRIEND_NOT_ACCEPT)
            ->get();

        return FriendResource::collection($friends);
    }

    public function addFriend(Request $request, $id) {
        
        $user = $request->user();

        $friend = Friend::create([
            "user_id" => $user->id,
            "friend_id" => $id
        ]);

        $friend->save();
            
        return response([
            "message" => "Заявка отправлена!",
            "id" => $id
        ], 201);
    }

    public function acceptFriend(Request $request, $id) {
        
        $friend = Friend::where("user_id", $id)
            ->where("friend_id", $request->user()->id)
            ->first();

        $friend->update([ "accept" => true ]);
        $friend->save();

        $user_friend = Friend::create([
            "user_id" => $request->user()->id,
            "friend_id" => $id,
            "accept" => true
        ]);
        $user_friend->save();

        return response([
            "message" => "Пользователь добавлен в друзья!",
            "friend" => new FriendResource($friend)
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
