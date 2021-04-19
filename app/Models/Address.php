<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['addressable_id', 'addressable_type'];

    /**
     * Get all of the owning commentable models.
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    public static function newAddress(Request $request): self
    {
        return new Address([
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'post_code' => $request->post_code,
        ]);
    }
}
