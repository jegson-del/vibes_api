<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\UserBlockEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserBlockEventTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->authNormal();
    }

    /** @test */
    public function user_can_see_blocked_event()
    {
        Event::factory(3)->create();

        Event::all()->each(function (Event $event) {
            UserBlockEvent::factory()->create([
                'event_id' => $event->id
            ]);
        });

        $response = $this->get(route('api.block.see_events'))->decodeResponseJson();

        $this->assertTrue(3 === count($response->json('data')));
    }

    /** @test */
    public function user_can_block_event()
    {
        Event::factory()->create(['name' => 'Zumba']);

        $this->post(
            route('api.block.block_event'),
            ['event_id' => Event::first()->id]
        )->decodeResponseJson();

        $this->assertTrue(1 === UserBlockEvent::count());
    }

    /** @test */
    public function user_can_unblock_event()
    {
        Event::factory(3)->create();

        Event::all()->each(function (Event $event) {
            UserBlockEvent::factory()->create([
                'event_id' => $event->id
            ]);
        });

        $this->delete(
            route('api.block.unblock_event', ['event' => Event::first()->id])
        )->decodeResponseJson();

        $this->assertTrue(2 === UserBlockEvent::count());
    }
}
