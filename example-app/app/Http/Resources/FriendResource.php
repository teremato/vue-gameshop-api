<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "user_id" => $this->setUser()->id,
            "avatar" => $this->setUser()->avatar,
            "name" => $this->setUser()->name,
            "is_accept" => $this->accept,
        ];
    }

    public function setUser() {
        return $this->user()->first();
    }
}
