<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            "game_id" => $this->game_id,

            "game" => new GameShortResource($this->game()->first()),

            "count" => $this->count,
            "price_per_page" => $this->getGamePrice()
        ];
    }

    public function getGamePrice() {
        return $this->game()->first()->price;
    }
}
