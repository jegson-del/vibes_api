<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const FREE_PAYMENT_NAME = 'free';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function store(Request $request, array $paymentData, float $amount): self
    {
        return Payment::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user()->id,
            'reference' => $paymentData['id'],
            'amount_paid' => $amount,
            'platform' => $request->platform,
        ]);
    }
}
