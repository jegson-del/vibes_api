<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_prices')
            ->withPivot('price')
            ->withTimestamps();
    }
}
