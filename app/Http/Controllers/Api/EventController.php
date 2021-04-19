<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResponse;
use App\Http\Resources\EventResponse;
use App\Models\Address;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $events = Event::notExpired()
            ->notCancelled()
            ->notBlocked()
            ->with('address', 'flyer', 'prices');

        if ($request->featured) {
            $events = $events->inRandomOrder()
                ->paginate(50);
        } else {
            $events = $events->orderBy('starts')
                ->paginate(25);
        }

        return EventResponse::collection($events);
    }

    public function show(Event $event): EventResponse
    {
        return new EventResponse($event);
    }

    public function search(string $keyword): AnonymousResourceCollection
    {
        $events = Event::notExpired()
            ->notCancelled()
            ->with('address', 'flyer', 'prices')
            ->withSearch($keyword)
            ->orderBy('starts')
            ->get();

        return EventResponse::collection($events);
    }

    public function topLocations(): AnonymousResourceCollection
    {
         $topLocation = Address::select('city', DB::raw('count(*) as total'))
             ->groupBy('city')
             ->orderBy('total', 'DESC')
             ->limit(4)
             ->get();

        return AddressResponse::collection($topLocation);
    }
}
