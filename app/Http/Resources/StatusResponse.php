<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResponse extends JsonResource
{
    private array $data;

    public function __construct($resource, array $data = [])
    {
        parent::__construct($resource);

        $this->data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => $this->resource,
            'data' => $this->data,
        ];
    }
}
