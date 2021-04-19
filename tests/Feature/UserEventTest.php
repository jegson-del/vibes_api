<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\PriceType;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Event;

class UserEventTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->authNormal();

        Event::factory(10)
            ->hasPayments()
            ->hasAddress()
            ->hasFlyer()
            ->create();

        Str::createUuidsNormally();

        PriceType::factory()->create();

        Ticket::factory(4)->create();
    }

    /** @test */
    public function user_can_see_all_events_they_paid_for()
    {
        $response = $this->get(
            route('api.user.events.index')
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertTrue(count($data) === 10);
        $this->assertTrue(count($data[0]['tickets']) ===  4);
    }

    /** @test */
    public function single_event_user_paid_for_can_be_seen()
    {
        $user = User::factory()->create();

        Payment::factory()->create(
            [
                'user_id' => $user->id,
                'event_id' => Event::first()->id
            ]
        );

        Payment::factory()->create(
            [
                'user_id' => User::first()->id,
                'event_id' => Event::first()->id
            ]
        );

        $response = $this->get(
            route('api.user.events.show', Event::first()->id)
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertTrue(count($data) === 2);
    }
}
