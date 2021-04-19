<?php

namespace App\Http\Resources;

use App\Http\Middleware\AdminGuard;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResponse extends JsonResource
{
    private ?string $token;

    public function __construct($resource, string $token = null)
    {
        parent::__construct($resource);
        $this->token = $token;
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'token' => $this->token,
            'profile_pics' => $this->profilePics,
            'is_admin' => $this->tokenCan('admin') || (new AdminGuard())->multiLevelTokenCan($this->tokens)
        ];
    }
}
