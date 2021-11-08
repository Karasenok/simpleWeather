<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
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
            'wind' => $this->wind,
            'main' => $this->main,
            'weather' => $this->weather[0],
            'clouds' => $this->clouds,
            'name' => $this->name,

        ];

    }
}
