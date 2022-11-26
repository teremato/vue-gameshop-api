<?php

namespace App\Http\Resources;

use App\Models\Friend;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{

    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            
            "name" => $this->name,
            "username" => $this->username,
            "status" => $this->status,
            "role" => $this->role,
            "avatar" => $this->avatar,
            "status" => $this->status,
            "country" => $this->country,
            "favorite_game" => $this->favorite_game,

            "media" => PhotoResource::collection($this->media()
                ->take(4)->get()),
            "posts" => PostResource::collection($this->posts()
                ->latest()->get()),

            "friends" => FriendResource::collection($this->friends()
                ->where("accept", Friend::FRIEND_ACCEPT)
                ->get()),
            
            "is_friend" => optional(auth()->user(), function ($user) {
                return $this->hasFriend($user->id);
            })
        ];
    }
}
