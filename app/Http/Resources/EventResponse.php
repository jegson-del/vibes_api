<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'currency' => $this->currency,
            'starts' => $this->starts,
            'ends' => $this->ends,
            'genre' => $this->genre,
            'status' => $this->status,
            'location' => $this->address,
            'flyer' => $this->flyer,
            'tickets' => $this->relationLoaded('tickets') ? $this->tickets : null,
            'prices' => $this->relationLoaded('prices') ? $this->prices : null,
        ];
    }
}
