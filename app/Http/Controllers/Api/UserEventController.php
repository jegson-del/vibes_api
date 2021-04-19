<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResponse;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserEventController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $event = $request->user()
            ->events()
            ->with('tickets', 'address', 'flyer', 'prices')
            ->limit(200)
            ->get();

        return EventResponse::collection($event);
    }

    public function show(Event $event, Request $request): AnonymousResourceCollection
    {
        $event = $request->user()->paidEvents($event);

        return EventResponse::collection($event);
    }
}
