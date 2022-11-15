<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameSettingsResource extends JsonResource
{

    public static $wrap = 'settings';

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
            "OC" => $this->OC,
            "RAM" => $this->RAM,
            "processor" => $this->processor,
            "videocard" => $this->videocard,
            "memory" => $this->memory,
            "directX" => $this->directX,
        ];
    }
}
