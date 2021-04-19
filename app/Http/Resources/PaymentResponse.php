<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResponse extends JsonResource
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
            'reference' => $this->reference,
            'amount_paid' => $this->amount_paid,
            'platform' => $this->platform,
            'event' => new EventResponse($this->event->load('address', 'flyer', 'prices')),
            'user' => new UserResponse($this->user),
            'tickets' => $this->relationLoaded('tickets') ? $this->tickets : null,
        ];
    }
}
