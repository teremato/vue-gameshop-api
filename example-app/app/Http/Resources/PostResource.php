<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            "id" => $this->id,
            "user_id" => $this->user_id,
            "user" => $this->user("user_id", $this->user_id)->first(),
            "text" => $this->text,
            "photo" => $this->media("photo_id", $this->photo_id)->first(),
            "created_at" => $this->created_at
        ];
    }
}
