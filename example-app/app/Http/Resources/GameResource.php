<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\GameSettingsResource;
use App\Http\Resources\GamePhotosResource;

class GameResource extends JsonResource
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
            "price" => $this->price,
            "slug" => $this->slug,
            "created_at" => $this->created_at,

            "photos" => GamePhotosResource::collection($this->gamePhotos()->get()),
            "settings" => new GameSettingsResource($this->gameSettings()->first()),
            "publisher" => new PublisherShortResource($this->publisher()->first()),
        ];
    }
}
