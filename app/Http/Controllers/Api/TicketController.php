<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResponse;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(Ticket $ticket): TicketResponse
    {
        return new TicketResponse($ticket);
    }

    //Todo: continue
    public function updateOwner(Request $request): TicketResponse
    {
        $request->validate([
            'id' => 'required|string',
            'owner' => 'required|string'
        ]);

        $ticket = Ticket::findOrFail($request->id);

        $ticket->owner = $request->owner;
        $ticket->save();

        return new TicketResponse($ticket);
    }
}
