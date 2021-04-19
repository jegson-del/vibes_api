<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\PriceType;
use App\Models\User;
use App\Models\UserBlockEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\Event;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_events()
    {
        $this->eventMake();

        PriceType::factory()->create();

        Event::all()->each(function ($event) {
            $event->prices()->attach(PriceType::first()->id, ['price' => 100]);
        });

        $response = $this->get(
            route('api.events.index')
        )->decodeResponseJson();

        $meta = $response->json('meta');
        $data = $response->json('data');

        $this->assertEquals(25, $meta['per_page']);
        $this->assertEquals(2, $meta['last_page']);
        $this->assertEquals(50, $meta['total']);
        $this->assertEquals(100, $data[0]['prices'][0]['pivot']['price']);
    }

    /** @test */
    public function user_can_see_featured_events()
    {
        $this->eventMake();

        $response = $this->get(
            route('api.events.index', ['featured' => 'yes'])
        )->decodeResponseJson();

        $meta = $response->json('meta');

        $this->assertEquals(50, $meta['per_page']);
        $this->assertEquals(1, $meta['last_page']);
        $this->assertEquals(50, $meta['total']);
    }

    /** @test */
    public function users_wont_see_expired_and_cancelled_events()
    {
        Event::factory()->create(['ends' => Carbon::now()->subDays(30)]);

        $response = $this->get(
            route('api.events.index')
        )->decodeResponseJson();

        $this->assertTrue(Event::count() === 1);
        $this->assertTrue(empty($response->json('data')));

        Event::factory()->create(['status' => Event::CANCELLED]);

        $response = $this->get(
            route('api.events.index')
        )->decodeResponseJson();

        $this->assertTrue(Event::count() === 2);
        $this->assertTrue(empty($response->json('data')));
    }

    /** @test */
    public function user_can_search_for_events()
    {
        $this->eventMake();

        Event::factory()
            ->hasAddress()
            ->hasFlyer()
            ->create([
                'name' => 'Bunga Bunga Banger'
            ]);

        $response = $this->get(
            route('api.events.search', ['keyword' => 'Bunga Bunga Banger'])
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertTrue(count($data) === 1);
    }

    /** @test */
    public function user_can_search_for_events_by_location()
    {
        $this->eventMake();

        Event::factory()
            ->hasAddress([
                'city' => 'London'
            ])
            ->hasFlyer()
            ->create(['ends' => now()->addDays(20)]);

        $response = $this->get(
            route('api.events.search', [
                'keyword' => 'London',
                'field' => 'city',
            ])
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertTrue(count($data) === 1);
    }

    /** @test */
    public function user_will_not_see_blocked_events()
    {
        $this->authNormal();

        $this->eventMake();

        Event::all()->each(function ($event, $index) {
            if ($index <= 9) {
                UserBlockEvent::factory()->create([
                    'user_id' => User::first()->id,
                    'event_id' => $event->id
                ]);
            }
        });

        $response = $this->get(
            route('api.events.index')
        )->decodeResponseJson();

        $meta = $response->json('meta');

        $this->assertEquals(40, $meta['total']);
    }

    /** @test */
    public function show_top_locations()
    {
        $this->withoutExceptionHandling();
        $this->authNormal();

        $this->eventMake();

        $address = Address::first();
        $address->city = 'Lagos FAKE';
        $address->save();

        $address = Address::orderBy('id', 'desc')->first();
        $address->city = 'Lagos FAKE';
        $address->save();

        $response = $this->get(
            route('api.events.top_locations')
        )->decodeResponseJson();

        $topLocations = $response->json('data');

        $this->assertEquals('Lagos FAKE', $topLocations[0]['city']);
        $this->assertEquals(2, $topLocations[0]['total']);
    }

    /** @test */
    public function user_can_see_single_event_by_id()
    {
        $this->eventMake();

        $event = Event::first();

        $response = $this->get(
            route('api.events.show', ['event' => $event->id])
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertEquals($event->id, $data['id']);
    }

    private function eventMake(): void
    {
        Event::factory(50)
            ->hasAddress()
            ->hasFlyer()
            ->create(['ends' => now()->addDay(5)]);
    }

    private function eventPriceMake(): void
    {
        Event::factory(50)
            ->hasAddress()
            ->hasFlyer()
            ->create(['ends' => now()->addDay(5)]);
    }
}
