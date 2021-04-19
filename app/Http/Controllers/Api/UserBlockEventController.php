<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResponse;
use App\Models\Event;
use App\Models\UserBlockEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserBlockEventController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return EventResponse::collection($request->user()->blockedEvents);
    }

    public function store(Request $request): EventResponse
    {
        $event = Event::findOrFail($request->event_id);

        if (!UserBlockEvent::isBlocked($event)->first()) {
            $request->user()->blockedEvents()->save($event);
        }

        return new EventResponse($event);
    }

    public function destroy(Event $event): EventResponse
    {
        UserBlockEvent::isBlocked($event)->first()->delete();

        return new EventResponse($event);
    }
}
