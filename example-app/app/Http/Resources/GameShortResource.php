<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameShortResource extends JsonResource
{

    public static $wrap = 'games';

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
            "title" => $this->title,
            "description" => $this->description,
            "main_photo" => $this->main_photo,
            "slug" => $this->slug,
            "price" => $this->price,
            "created_at" => $this->created_at
        ];
    }
}
