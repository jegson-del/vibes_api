<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlockEvent extends Model
{
    use HasFactory;

    public function scopeIsBlocked($query, Event $event)
    {
        return $query->where('event_id', $event->id)
            ->where('user_id', request()->user()->id);
    }
}
